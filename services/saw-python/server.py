"""Small standard-library HTTP SAW service for local PLC development.

No third-party Python dependencies. Start with SAW_SERVICE_TOKEN set.
"""
import hmac
import json
import math
import os
from http.server import BaseHTTPRequestHandler, ThreadingHTTPServer


def calculate(payload):
    criteria = payload.get('criteria')
    alternatives = payload.get('alternatives')
    if not isinstance(criteria, list) or not criteria or not isinstance(alternatives, list) or not alternatives:
        raise ValueError('Kriteria dan alternatif harus tersedia.')
    codes = [c.get('code') for c in criteria]
    if len(codes) != len(set(codes)) or not all(isinstance(c, str) for c in codes):
        raise ValueError('Kode kriteria tidak valid.')
    weights = [float(c['weight']) for c in criteria]
    if any(not math.isfinite(w) or w < 0 for w in weights) or abs(sum(weights) - 1) > 0.0001:
        raise ValueError('Total bobot harus 1.')
    for c in criteria:
        if c.get('type') not in ('benefit', 'cost'):
            raise ValueError('Jenis kriteria tidak dikenal.')
    for row in alternatives:
        if not isinstance(row, dict) or not isinstance(row.get('values'), dict):
            raise ValueError('Alternatif tidak valid.')
        for code in codes:
            value = row['values'].get(code)
            if not isinstance(value, (int, float)) or isinstance(value, bool) or not math.isfinite(value) or value < 0:
                raise ValueError('Nilai alternatif harus numerik nonnegatif.')
    if len(set(row.get('id') for row in alternatives)) != len(alternatives):
        raise ValueError('ID alternatif harus unik.')
    limits = {}
    for c in criteria:
        v = [row['values'][c['code']] for row in alternatives]
        limits[c['code']] = max(v) if c['type'] == 'benefit' else min(v)
    scored = []
    for row in alternatives:
        total = 0.0
        for c, weight in zip(criteria, weights):
            x = row['values'][c['code']]
            limit = limits[c['code']]
            if c['type'] == 'benefit':
                normalized = x / limit if limit > 0 else 1.0
            elif limit == 0:
                # Cost zero: a free alternative outranks a positive-cost one.
                normalized = 1.0 if x == 0 else 0.0
            else:
                normalized = limit / x
            total += weight * normalized
        scored.append({'id': row['id'], 'score': round(total, 8)})
    scored.sort(key=lambda r: (-r['score'], str(r['id'])))
    return [{'id': r['id'], 'score': r['score'], 'rank': i + 1} for i, r in enumerate(scored)]


class Handler(BaseHTTPRequestHandler):
    def do_POST(self):
        if self.path != '/calculate':
            return self.reply(404, {'message': 'Not found'})
        expected = os.environ.get('SAW_SERVICE_TOKEN', '')
        provided = self.headers.get('Authorization', '').removeprefix('Bearer ')
        if not expected or not hmac.compare_digest(provided, expected):
            return self.reply(401, {'message': 'Unauthorized'})
        try:
            length = int(self.headers.get('Content-Length', 0))
            if length < 1 or length > 1024 * 1024:
                return self.reply(413, {'message': 'Invalid payload length'})
            payload = json.loads(self.rfile.read(length))
            result = calculate(payload)
        except (ValueError, KeyError, TypeError, OverflowError) as error:
            return self.reply(422, {'message': str(error)})
        self.reply(200, {'results': result})

    def reply(self, status, data):
        body = json.dumps(data).encode('utf-8')
        self.send_response(status)
        self.send_header('Content-Type', 'application/json; charset=utf-8')
        self.send_header('Content-Length', str(len(body)))
        self.end_headers()
        self.wfile.write(body)


if __name__ == '__main__':
    if not os.environ.get('SAW_SERVICE_TOKEN'):
        raise SystemExit('Set SAW_SERVICE_TOKEN before starting the service.')
    port = int(os.environ.get('SAW_PORT', '8010'))
    print(f'SAW service: http://127.0.0.1:{port}', flush=True)
    ThreadingHTTPServer(('127.0.0.1', port), Handler).serve_forever()

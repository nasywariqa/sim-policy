<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgramWork;
use App\Models\SawAssessment;
use App\Models\SawCriterion;
use App\Models\SawRun;
use App\Support\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class SawController extends Controller
{
    public function index(Request $request)
    {
        $member = Access::require($request->user(),'saw.view');
        $period = $member->period_id;
        $runs = SawRun::with('results.program:id,name')->where('period_id',$period)->latest()->limit(15)->get();
        return response()->json(['data'=>[
            'criteria'=>SawCriterion::where('period_id',$period)->orderBy('id')->get(),
            'programs'=>ProgramWork::where('period_id',$period)->whereIn('status',['submitted','approved'])->orderBy('name')->get(['id','name','budget','status']),
            'assessments'=>SawAssessment::whereIn('program_work_id', ProgramWork::where('period_id',$period)->select('id'))
                ->get(),
            'runs'=>$runs,
        ]]);
    }

    public function weights(Request $request)
    {
        $member = Access::require($request->user(),'saw.weights.manage');
        $data = $request->validate([
            'weights'=>'required|array|size:5',
            'weights.*.id'=>'required|integer|distinct|exists:saw_criteria,id',
            'weights.*.weight'=>'required|numeric|min:0|max:1',
        ]);
        $criteria = SawCriterion::where('period_id',$member->period_id)->get();
        $ids = array_map(fn($x) => (int)$x['id'], $data['weights']);
        if ($criteria->count() !== 5 || $criteria->pluck('id')->diff($ids)->isNotEmpty()) {
            throw ValidationException::withMessages(['weights'=>'Kriteria tidak sesuai periode aktif.']);
        }
        if (abs(array_sum(array_column($data['weights'],'weight')) - 1.0) > 0.0001) {
            throw ValidationException::withMessages(['weights'=>'Total bobot harus 100%.']);
        }
        DB::transaction(function() use ($data) {
            foreach ($data['weights'] as $row) {
                SawCriterion::whereKey($row['id'])->update(['weight'=>$row['weight']]);
            }
        });
        return response()->json(['message'=>'Bobot berhasil disimpan.']);
    }

    public function assessment(Request $request)
    {
        $member = Access::require($request->user(),'saw.assess');
        $data = $request->validate([
            'program_work_id'=>'required|exists:program_works,id',
            'saw_criterion_id'=>'required|exists:saw_criteria,id',
            'value'=>'required|numeric|min:1|max:5',
        ]);
        $criterion = SawCriterion::findOrFail($data['saw_criterion_id']);
        $program = ProgramWork::findOrFail($data['program_work_id']);
        if ($criterion->period_id !== $member->period_id || $program->period_id !== $member->period_id ||
            $criterion->scale !== 'score' || ! in_array($program->status,['submitted','approved'],true)) {
            throw ValidationException::withMessages(['value'=>'Penilaian hanya berlaku untuk kriteria skor pada program yang diajukan/disetujui di periode aktif.']);
        }
        $record = SawAssessment::updateOrCreate([
            'program_work_id'=>$program->id,'saw_criterion_id'=>$criterion->id,'membership_id'=>$member->id,
        ],['value'=>$data['value']]);
        return response()->json(['data'=>$record]);
    }

    public function calculate(Request $request)
    {
        $member = Access::require($request->user(),'saw.calculate');
        $criteria = SawCriterion::where('period_id',$member->period_id)->orderBy('id')->get();
        $programs = ProgramWork::where('period_id',$member->period_id)->whereIn('status',['submitted','approved'])->orderBy('id')->get();
        if ($criteria->count() !== 5 || $programs->isEmpty()) {
            throw ValidationException::withMessages(['saw'=>'Diperlukan lima kriteria dan minimal satu program diajukan/disetujui.']);
        }
        if (abs((float)$criteria->sum('weight') - 1.0) > 0.0001) {
            throw ValidationException::withMessages(['weights'=>'Total bobot SAW harus 100%.']);
        }
        $rows = [];
        foreach ($programs as $program) {
            $values = [];
            foreach ($criteria as $criterion) {
                if ($criterion->scale === 'currency') {
                    $values[$criterion->code] = (float)$program->budget;
                } else {
                    $avg = SawAssessment::where('program_work_id',$program->id)
                        ->where('saw_criterion_id',$criterion->id)->avg('value');
                    if ($avg === null) {
                        throw ValidationException::withMessages(['saw'=>"Penilaian {$criterion->name} untuk {$program->name} belum lengkap."]);
                    }
                    $values[$criterion->code] = (float)$avg;
                }
            }
            $rows[]=['id'=>$program->id,'name'=>$program->name,'values'=>$values];
        }
        $payload=['criteria'=>$criteria->map(fn($c)=>[
            'code'=>$c->code,'name'=>$c->name,'type'=>$c->type,'weight'=>(float)$c->weight,
        ])->values()->all(),'alternatives'=>$rows];
        $baseUrl = config('services.saw.url');
        $token = config('services.saw.token');
        if (! $baseUrl || ! $token) return response()->json(['message'=>'Layanan SAW belum dikonfigurasi.'],503);
        try {
            $response = Http::withToken($token)->acceptJson()->timeout(8)->post(rtrim($baseUrl,'/').'/calculate',$payload);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            report($e);
            return response()->json(['message'=>'Layanan perhitungan SAW tidak dapat dihubungi.'],503);
        }
        if (! $response->successful() || ! is_array($response->json('results'))) {
            return response()->json(['message'=>'Hasil perhitungan SAW tidak valid.'],502);
        }
        $results = $response->json('results');
        $ids = array_column($rows,'id');
        $seen=[];
        if (count($results) !== count($rows)) return response()->json(['message'=>'Jumlah hasil SAW tidak sesuai.'],502);
        foreach ($results as $r) {
            if (! is_array($r) || ! isset($r['id'],$r['score'],$r['rank']) ||
                ! in_array((int)$r['id'],$ids,true) || in_array((int)$r['id'],$seen,true) ||
                ! is_numeric($r['score']) || ! is_finite((float)$r['score']) ||
                (float)$r['score'] < 0 || (float)$r['score'] > 1.0000001 ||
                ! is_int($r['rank']) || $r['rank'] < 1 || $r['rank'] > count($rows)) {
                return response()->json(['message'=>'Isi hasil SAW tidak valid.'],502);
            }
            $seen[]=(int)$r['id'];
        }
        $run = DB::transaction(function() use ($member,$payload,$results) {
            $run = SawRun::create(['period_id'=>$member->period_id,'membership_id'=>$member->id,'input_snapshot'=>$payload]);
            foreach ($results as $r) {
                $run->results()->create(['program_work_id'=>(int)$r['id'],'score'=>$r['score'],'rank'=>$r['rank']]);
            }
            return $run;
        });
        return response()->json(['data'=>$run->load('results.program:id,name')],201);
    }
}

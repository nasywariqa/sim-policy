import importlib.util
import pathlib
import unittest

module = pathlib.Path(__file__).with_name('server.py')
spec = importlib.util.spec_from_file_location('saw_server', module)
saw = importlib.util.module_from_spec(spec)
spec.loader.exec_module(saw)


class SawCalculationTests(unittest.TestCase):
    def setUp(self):
        self.criteria = [
            {'code': 'a', 'type': 'benefit', 'weight': 0.6},
            {'code': 'b', 'type': 'cost', 'weight': 0.4},
        ]

    def test_ranking(self):
        rows = [{'id': 1, 'values': {'a': 5, 'b': 100}},
                {'id': 2, 'values': {'a': 3, 'b': 200}}]
        result = saw.calculate({'criteria': self.criteria, 'alternatives': rows})
        self.assertEqual(result[0]['id'], 1)
        self.assertAlmostEqual(result[0]['score'], 1.0)
        self.assertAlmostEqual(result[1]['score'], 0.56)

    def test_free_cost(self):
        rows = [{'id': 1, 'values': {'a': 4, 'b': 0}},
                {'id': 2, 'values': {'a': 4, 'b': 100}}]
        result = saw.calculate({'criteria': self.criteria, 'alternatives': rows})
        self.assertEqual([x['score'] for x in result], [1, 0.6])

    def test_missing_value(self):
        with self.assertRaises(ValueError):
            saw.calculate({'criteria': self.criteria, 'alternatives': [{'id': 1, 'values': {'a': 3}}]})


if __name__ == '__main__':
    unittest.main()

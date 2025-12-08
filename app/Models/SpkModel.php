<?php
namespace App\Models;

use CodeIgniter\Model;

class SpkModel extends Model
{
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function calculateWP()
    {
        // Ambil data kriteria
        $criteria = $this->db->table('criteria')->get()->getResultArray();
        if (empty($criteria)) {
            return ['error' => 'Data kriteria belum tersedia.'];
        }

        // Ambil nilai alternatif
        $values = $this->db->table('nilai AS v')
            ->select('v.alternative_id, a.name AS alt_name, c.id AS crit_id, c.weight, c.type, v.value')
            ->join('alternatives AS a', 'a.id = v.alternative_id')
            ->join('criteria AS c', 'c.id = v.criteria_id')
            ->get()
            ->getResultArray();

        if (empty($values)) {
            return ['error' => 'Data nilai alternatif belum tersedia.'];
        }

        // --- Hitung total bobot normalisasi ---
        $sumWeight = array_sum(array_column($criteria, 'weight'));

        // --- Hitung nilai S tiap alternatif ---
        $alternatifS = [];
        foreach ($values as $v) {
            $alt = $v['alternative_id'];
            $w = $v['weight'] / $sumWeight;
            $nilai = ($v['type'] === 'cost') ? pow($v['value'], -$w) : pow($v['value'], $w);
            $alternatifS[$alt]['name'] = $v['alt_name'];
            $alternatifS[$alt]['S'][] = $nilai;
        }

        // --- Jumlahkan nilai S ---
        foreach ($alternatifS as $id => &$a) {
            $a['S'] = array_product($a['S']);
        }

        // --- Hitung total untuk normalisasi (V) ---
        $totalS = array_sum(array_column($alternatifS, 'S'));
        foreach ($alternatifS as &$a) {
            $a['V'] = $a['S'] / $totalS;
        }

        // --- Urutkan berdasarkan V (ranking) ---
        usort($alternatifS, function ($a, $b) {
            return $b['V'] <=> $a['V'];
        });

        // --- Format hasil agar cocok dengan view ---
        $results = [];
        foreach ($alternatifS as $alt) {
            $results[] = [
                'name' => $alt['name'],
                'score' => $alt['V'] // Ganti key agar sama dengan view
            ];
        }

        return [
            'criteria' => $criteria,
            'results' => $results
        ];
    }
}

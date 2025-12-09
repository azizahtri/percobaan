<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlternativesModel;
use App\Models\PekerjaanModel;
use App\Models\DataModel;
use App\Models\CriteriaModel;
use App\Models\SubcriteriaModel;

class AlternativesController extends BaseController
{
    protected $alternativesModel;
    protected $pekerjaanModel;
    protected $dataModel;
    protected $criteriaModel;
    protected $subcriteriaModel;

    public function __construct()
    {
        $this->alternativesModel = new AlternativesModel();
        $this->pekerjaanModel    = new PekerjaanModel();
        $this->dataModel         = new DataModel();
        $this->criteriaModel     = new CriteriaModel();
        $this->subcriteriaModel  = new SubcriteriaModel();
    }

    public function index()
    {
        // Join ke tabel pekerjaan
        $data = $this->alternativesModel
            ->select('alternatives.*, pekerjaan.divisi, pekerjaan.posisi')
            ->join('pekerjaan', 'pekerjaan.id = alternatives.pekerjaan_id', 'left')
            ->orderBy('alternatives.id', 'DESC')
            ->findAll();

        return view('admin/alternatives/index', [
            'title'        => 'Data Alternatif',
            'alternatives' => $data
        ]);
    }

    // --- FUNGSI DETAIL BARU ---
    public function detail($id)
    {
        // 1. Ambil Data Alternatif (Karyawan)
        $karyawan = $this->alternativesModel
            ->select('alternatives.*, pekerjaan.posisi')
            ->join('pekerjaan', 'pekerjaan.id = alternatives.pekerjaan_id', 'left')
            ->find($id);

        if (!$karyawan) {
            return redirect()->to('admin/alternatives')->with('error', 'Data tidak ditemukan');
        }

        // 2. Cari Data Asli di Tabel Pelamar (DataModel)
        // Kita cari berdasarkan Nama yang sama.
        $biodata = $this->dataModel
            ->where('nama', $karyawan['nama'])
            ->orderBy('id', 'DESC') // Ambil yang paling baru jika ada nama sama
            ->first();

        return view('admin/alternatives/detail', [
            'title'    => 'Detail Karyawan',
            'karyawan' => $karyawan,
            'biodata'  => $biodata // Ini bisa null jika data pelamar sudah dihapus
        ]);
    }

    public function penilaian($id)
    {
        // 1. Ambil Data Karyawan
        $karyawan = $this->alternativesModel
            ->select('alternatives.*, pekerjaan.divisi, pekerjaan.posisi, pekerjaan.id as pekerjaan_id')
            ->join('pekerjaan', 'pekerjaan.id = alternatives.pekerjaan_id', 'left')
            ->find($id);

        if (!$karyawan) return redirect()->to('admin/alternatives');

        // 2. Ambil Kriteria Berdasarkan Posisi Pekerjaan Karyawan
        $criteria = $this->criteriaModel
            ->where('pekerjaan_id', $karyawan['pekerjaan_id'])
            ->findAll();

        // 3. Ambil Subkriteria
        $subcriteria = [];
        $criteriaIds = array_column($criteria, 'id');

        if (!empty($criteriaIds)) {
            $rawSub = $this->subcriteriaModel->whereIn('criteria_id', $criteriaIds)->findAll();
            foreach ($rawSub as $s) {
                $subcriteria[$s['criteria_id']][] = $s;
            }
        }

        // 4. Ambil Nilai Sebelumnya (Jika mau edit)
        $savedValues = json_decode($karyawan['detail_nilai'] ?? '[]', true);

        return view('admin/alternatives/penilaian', [
            'title'       => 'Evaluasi Kinerja',
            'karyawan'    => $karyawan,
            'criteria'    => $criteria,
            'subcriteria' => $subcriteria,
            'savedValues' => $savedValues
        ]);
    }

    public function hitung($id)
    {
        // 1. Tangkap Input
        $input_criteria_ids = $this->request->getPost('criteria_id');
        $input_values       = $this->request->getPost('value');

        // Mapping Input
        $submittedValues = [];
        if (!empty($input_criteria_ids)) {
            foreach ($input_criteria_ids as $k => $cid) {
                $submittedValues[$cid] = $input_values[$k];
            }
        }

        // 2. Ambil Karyawan & Kriteria
        $karyawan = $this->alternativesModel->find($id);
        $allCriteria = $this->criteriaModel->where('pekerjaan_id', $karyawan['pekerjaan_id'])->findAll();

        if (empty($allCriteria)) {
            return redirect()->back()->with('error', 'Kriteria penilaian belum diatur untuk posisi ini.');
        }

        // 3. Hitung Total Bobot
        $totalBobot = 0;
        foreach ($allCriteria as $c) {
            $totalBobot += $c['bobot'];
        }

        // 4. Hitung Vector S
        $vectorS = 1;
        foreach ($allCriteria as $c) {
            $nilai = isset($submittedValues[$c['id']]) ? (float)$submittedValues[$c['id']] : 1;
            
            // Normalisasi Pangkat
            $bobotNormal = ($totalBobot > 0) ? ($c['bobot'] / $totalBobot) : 0;
            $pangkat = (strtolower($c['tipe']) == 'cost') ? -$bobotNormal : $bobotNormal;

            if ($nilai <= 0) $nilai = 1;
            $vectorS *= pow($nilai, $pangkat);
        }

        // 5. Simpan Hasil ke Tabel Alternatives
        $this->alternativesModel->update($id, [
            'skor_akhir'   => $vectorS,
            'detail_nilai' => json_encode($submittedValues) // Simpan inputan agar form tidak reset
        ]);

        return redirect()->to(base_url('admin/alternatives'))->with('success', 'Evaluasi berhasil! Skor Kinerja: ' . number_format($vectorS, 4));
    }
    
    public function delete($id)
    {
        $this->alternativesModel->delete($id);
        return redirect()->to(base_url('admin/alternatives'))->with('success', 'Data berhasil dihapus');
    }
    
}
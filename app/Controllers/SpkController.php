<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LowonganModel;
use App\Models\DataModel;
use App\Models\PekerjaanModel;

class SpkController extends BaseController
{
    public function index()
    {
        $lowonganModel  = new LowonganModel();
        $dataModel      = new DataModel();
        $pekerjaanModel = new PekerjaanModel();

        // 1. Ambil Data Divisi untuk Filter
        $divisiList = $pekerjaanModel->groupBy('divisi')->orderBy('divisi', 'ASC')->findAll();

        // 2. Tangkap Filter
        $selectedDivisi = $this->request->getGet('field') ?? 'all';

        // 3. Query Lowongan (PERBAIKAN DISINI)
        // Pastikan 'pekerjaan.standar_spk' tertulis di dalam select
        $builder = $lowonganModel->select('lowongan.*, pekerjaan.divisi, pekerjaan.posisi, pekerjaan.standar_spk');
        $builder->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id');

        if ($selectedDivisi != 'all') {
            $builder->where('pekerjaan.divisi', $selectedDivisi);
        }

        $lowongans = $builder->orderBy('lowongan.id', 'DESC')->findAll();
        
        // 4. Proses Ranking
        $rankingData = [];

        foreach ($lowongans as $job) {
            $pelamar = $dataModel->where('id_lowongan', $job['id'])
                                 ->where('spk_score !=', null)
                                 ->where('spk_score >', 0) 
                                 ->orderBy('spk_score', 'DESC')
                                 ->findAll();
            
            if (!empty($pelamar)) {
                $rankingData[] = [
                    'job'        => $job, // $job sekarang sudah punya index 'standar_spk'
                    'candidates' => $pelamar
                ];
            }
        }

        return view('admin/spk/index', [
            'title'         => 'Peringkat Seleksi (Ranking)',
            'rankingData'   => $rankingData,
            'divisiList'    => $divisiList,
            'selectedField' => $selectedDivisi
        ]);
    }
}
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LowonganModel;
use App\Models\DataModel;
use App\Models\AlternativesModel;

class AdminController extends BaseController
{
    public function dashboard()
    {
        // 1. Panggil Model
        $lowonganModel     = new LowonganModel();
        $dataModel         = new DataModel();
        $alternativesModel = new AlternativesModel();

        // 2. Hitung Statistik (Untuk 4 Kartu di Atas)
        $totalLowongan  = $lowonganModel->countAllResults();
        $totalPelamar   = $dataModel->countAllResults(); // Total semua pelamar (masuk & arsip)
        $totalKaryawan  = $alternativesModel->countAllResults(); // Total di tabel Alternatives
        
        // Hitung pelamar 'Pending' (Status Proses & Belum Masuk History)
        $butuhTindakan  = $dataModel->where('status', 'proses')
                                    ->where('is_history', 0)
                                    ->countAllResults();

        // 3. Ambil 5 Pelamar Terbaru (Untuk Tabel)
        // Join ke tabel lowongan agar kita bisa menampilkan judul lowongan yang dilamar
        $pelamarTerbaru = $dataModel
            ->select('data.*, lowongan.judul_lowongan')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->orderBy('data.id', 'DESC')
            ->findAll(5);

        // 4. Ambil 3 Lowongan Terkini (Untuk List di Kanan)
        $lowonganBaru = $lowonganModel
            ->orderBy('tanggal_posting', 'DESC')
            ->findAll(3);

        // 5. Kirim Data ke View
        $data = [
            'title'          => 'Dashboard Admin',
            'user_name'      => session()->get('name') ?? 'Admin', // Ambil nama dari Session Login
            'stats' => [
                'lowongan' => $totalLowongan,
                'pelamar'  => $totalPelamar,
                'karyawan' => $totalKaryawan,
                'pending'  => $butuhTindakan
            ],
            'recent_pelamar' => $pelamarTerbaru,
            'recent_jobs'    => $lowonganBaru
        ];

        return view('admin/dashboard', $data);
    }

    // File: app/Controllers/Admin/DashboardController.php

    public function countPelamar()
    {
        $pelamarModel = new \App\Models\PelamarModel();
        
        // Hitung pelamar yang masih diproses (belum masuk history/arsip)
        // Sesuaikan logika 'is_history' = 0 atau status = 'proses' sesuai database Anda
        $count = $pelamarModel->where('is_history', 0)->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'count'  => $count
        ]);
    }
}
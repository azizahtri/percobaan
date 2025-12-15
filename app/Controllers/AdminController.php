<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LowonganModel;
use App\Models\DataModel;
use App\Models\AlternativesModel;
use App\Models\AdminModel;

class AdminController extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        // Load AdminModel untuk dipakai di fungsi akun
        $this->adminModel = new AdminModel();
    }

    // =================================================================
    // 1. DASHBOARD AREA
    // =================================================================
    public function dashboard()
    {
        $lowonganModel     = new LowonganModel();
        $dataModel         = new DataModel();
        $alternativesModel = new AlternativesModel();
        // Load PelamarModel untuk menghitung total orang (bukan total lamaran)
        $pelamarModel      = new \App\Models\PelamarModel(); 

        // Ambil data admin yang sedang login
        $adminId   = session()->get('id');
        $adminData = $this->adminModel->find($adminId);
        $namaAdmin = $adminData ? $adminData['name'] : 'Administrator';

        // Statistik Dashboard
        $stats = [
            'lowongan' => $lowonganModel->countAllResults(),
            // Hitung total pelamar unik dari tabel Master Pelamar
            'pelamar'  => $pelamarModel->countAllResults(), 
            'karyawan' => $alternativesModel->countAllResults(),
            'pending'  => $dataModel->where('status', 'proses')->where('is_history', 0)->countAllResults()
        ];

        // Tabel & List Pelamar Terbaru
        // PERBAIKAN: Tambahkan JOIN ke tabel 'pelamar' dan ambil 'nama_lengkap' sebagai 'nama'
        $pelamarTerbaru = $dataModel->select('data.*, lowongan.judul_lowongan, pelamar.nama_lengkap as nama')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->join('pelamar', 'pelamar.id = data.pelamar_id') // <--- JOIN PENTING
            ->orderBy('data.id', 'DESC')
            ->findAll(5); // Batasi 5 saja biar tidak berat

        $lowonganBaru = $lowonganModel->orderBy('tanggal_posting', 'DESC')->findAll(3);

        $data = [
            'title'          => 'Dashboard Admin',
            'user_name'      => $namaAdmin, 
            'stats'          => $stats,
            'recent_pelamar' => $pelamarTerbaru,
            'recent_jobs'    => $lowonganBaru
        ];

        return view('admin/dashboard', $data);
    }

    // API Notifikasi
    public function countPelamar()
    {
        $pelamarModel = new DataModel();
        $count = $pelamarModel->where('is_history', 0)->countAllResults();
        return $this->response->setJSON(['status' => 'success', 'count' => $count]);
    }


    // =================================================================
    // 2. MANAJEMEN AKUN AREA (CRUD ADMIN)
    // =================================================================
    
    public function akunIndex()
    {
        // Cek Role (Hanya Super Admin)
        if (session()->get('role') !== 'Super Admin') {
            return redirect()->to('admin/dashboard')->with('error', 'Akses ditolak.');
        }

        $data = [
            'title' => 'Manajemen Akun',
            'users' => $this->adminModel->orderBy('id', 'DESC')->findAll()
        ];
        return view('admin/akun/index', $data);
    }

    public function akunCreate()
    {
        if (session()->get('role') !== 'Super Admin') return redirect()->back();
        return view('admin/akun/create', ['title' => 'Tambah Akun']);
    }

    public function akunStore()
    {
        if (!$this->validate([
            'name'     => 'required',
            'username' => 'required|min_length[4]|is_unique[admin.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $this->adminModel->save([
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role'),
        ]);

        return redirect()->to('admin/akun')->with('success', 'Akun berhasil dibuat.');
    }

    public function akunEdit($id)
    {
        if (session()->get('role') !== 'Super Admin') return redirect()->back();
        
        $user = $this->adminModel->find($id);
        return view('admin/akun/edit', ['title' => 'Edit Akun', 'user' => $user]);
    }

    public function akunUpdate($id)
    {
        $data = [
            'id'       => $id,
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
        ];

        // Update password jika diisi
        $pass = $this->request->getPost('password');
        if (!empty($pass)) {
            $data['password'] = $pass;
        }

        $this->adminModel->save($data);
        if ($id == session()->get('id')) {
            // Timpa session 'name' yang lama dengan yang baru
            session()->set('name', $data['name']);
            
            // Jika Anda menampilkan username juga, update sekalian
            session()->set('username', $data['username']);
        }
        return redirect()->to('admin/akun')->with('success', 'Akun diperbarui.');
    }

    public function akunDelete($id)
    {
        if (session()->get('role') !== 'Super Admin') return redirect()->back();
        if (session()->get('id') == $id) return redirect()->back()->with('error', 'Tidak bisa hapus akun sendiri.');

        $this->adminModel->delete($id);
        return redirect()->to('admin/akun')->with('success', 'Akun dihapus.');
    }
}
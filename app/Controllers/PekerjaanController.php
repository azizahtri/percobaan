<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PekerjaanModel;

class PekerjaanController extends BaseController
{
    protected $pekerjaanModel;

    public function __construct()
    {
        $this->pekerjaanModel = new PekerjaanModel();
    }

    // 1. HALAMAN UTAMA (LIST DIVISI)
    public function index()
    {
        $db = \Config\Database::connect();
        // Hitung jumlah posisi, bukan jabatan
        $query = $db->query("SELECT divisi, COUNT(id) as total_posisi FROM pekerjaan GROUP BY divisi ORDER BY divisi ASC");
        $divisiList = $query->getResultArray();

        return view('admin/pekerjaan/index', [
            'title'  => 'Data Pekerjaan',
            'divisi' => $divisiList
        ]);
    }

    // 2. HALAMAN DETAIL (LIST POSISI DI DALAM DIVISI)
    public function detailDivisi($namaDivisi)
    {
        $namaDivisi = urldecode($namaDivisi);

        $posisi = $this->pekerjaanModel
            ->where('divisi', $namaDivisi)
            ->findAll();

        if (empty($posisi)) {
            return redirect()->to('admin/pekerjaan')->with('error', 'Divisi tidak ditemukan.');
        }

        return view('admin/pekerjaan/detail', [
            'title'      => 'Daftar Posisi: ' . $namaDivisi,
            'namaDivisi' => $namaDivisi,
            'posisi'     => $posisi
        ]);
    }

    // 3. PROSES SIMPAN POSISI BARU (Dari Modal Detail)
    public function store()
    {
        // Validasi HANYA divisi dan posisi. Standar tidak perlu.
        if (!$this->validate([
            'divisi' => 'required',
            'posisi' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Nama Divisi dan Posisi wajib diisi.');
        }

        $this->pekerjaanModel->save([
            'divisi'      => $this->request->getPost('divisi'),
            'posisi'      => $this->request->getPost('posisi'),
            // standar_spk tidak diisi, biarkan default database (0.00)
        ]);

        return redirect()->to('admin/pekerjaan/detail/' . urlencode($this->request->getPost('divisi')))
                         ->with('success', 'Posisi berhasil ditambahkan.');
    }

    // 4. PROSES UPDATE POSISI (Satu Baris)
    public function updatePosisi($id)
    {
        // Validasi HANYA posisi
        if (!$this->validate([
            'posisi' => 'required',
        ])) {
            return redirect()->back()->with('error', 'Nama posisi tidak boleh kosong.');
        }

        $this->pekerjaanModel->update($id, [
            'posisi' => $this->request->getPost('posisi'),
        ]);

        $data = $this->pekerjaanModel->find($id);
        
        return redirect()->to('admin/pekerjaan/detail/' . urlencode($data['divisi']))
                         ->with('success', 'Data posisi berhasil diperbarui.');
    }

    // 5. PROSES UPDATE NAMA DIVISI (Massal)
    public function updateDivisi()
    {
        $oldName = $this->request->getPost('old_divisi');
        $newName = $this->request->getPost('divisi');

        if (!$oldName || !$newName) {
            return redirect()->back()->with('error', 'Nama divisi tidak boleh kosong.');
        }

        $this->pekerjaanModel->where('divisi', $oldName)
                             ->set(['divisi' => $newName])
                             ->update();

        return redirect()->to('admin/pekerjaan')->with('success', 'Nama Divisi berhasil diperbarui.');
    }

    // 6. PROSES HAPUS
    public function delete($id)
    {
        $data = $this->pekerjaanModel->find($id);
        $this->pekerjaanModel->delete($id);
        
        $sisa = $this->pekerjaanModel->where('divisi', $data['divisi'])->countAllResults();

        if ($sisa > 0) {
            return redirect()->to('admin/pekerjaan/detail/' . urlencode($data['divisi']))->with('success', 'Posisi dihapus.');
        } else {
            return redirect()->to('admin/pekerjaan')->with('success', 'Posisi dihapus. Divisi kosong dan otomatis terhapus.');
        }
    }
    
    // (Optional) Untuk Create Divisi Baru dari halaman Index
    // Ini sebenarnya "Create Posisi Pertama" untuk Divisi Baru
    public function create()
    {
        return view('admin/pekerjaan/create', ['title' => 'Tambah Divisi Baru']);
    }
}
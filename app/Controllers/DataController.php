<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\DataModel;
use App\Models\AlternativesModel;
use App\Models\LowonganModel;

class DataController extends BaseController
{
    protected $dataModel;
    protected $alternativesModel;
    protected $lowonganModel;

    public function __construct()
    {
        $this->dataModel = new DataModel();
        $this->alternativesModel = new AlternativesModel();
        $this->lowonganModel = new LowonganModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');

        $diproses = $this->dataModel
            ->select('data.*, lowongan.judul_lowongan')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->groupStart()
                ->where('data.is_history', 0)
                ->orWhere('data.is_history', null)
            ->groupEnd()
            ->orderBy('data.id', 'DESC')
            ->findAll();

        $builder = $this->dataModel
            ->select('data.*, lowongan.judul_lowongan')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->where('data.is_history', 1);

        if ($keyword) {
            $builder->groupStart()
                ->like('data.nama', $keyword)
                ->orLike('lowongan.judul_lowongan', $keyword)
                ->groupEnd();
        }

        $history = $builder->orderBy('data.id', 'DESC')->findAll();
            
        return view('admin/data/index', [
            'diproses' => $diproses,
            'history'  => $history,
            'keyword'  => $keyword
        ]);
    }
    public function detail($id)
    {
        
        $pelamar = $this->dataModel
            ->select('data.*, lowongan.judul_lowongan, pekerjaan.divisi, pekerjaan.posisi, pekerjaan.standar_spk')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id') 
            ->find($id);

        if (!$pelamar) {
            return redirect()->to('admin/data')->with('error', 'Data tidak ditemukan');
        }

        // Cek status rekrut
        $isRekrut = $this->alternativesModel->where('nama', $pelamar['nama'])->first();

        return view('admin/data/detail', [
            'title'    => 'Detail Arsip Pelamar',
            'data'     => $pelamar,
            'isRekrut' => $isRekrut
        ]);
    }

    public function onboard($id)
    {
        // 1. Ambil Data Pelamar
        $pelamar = $this->dataModel->find($id);
        if (!$pelamar || $pelamar['status'] != 'memenuhi') {
            return redirect()->back()->with('error', 'Data tidak valid atau status belum memenuhi.');
        }

        // 2. Ambil Data Lowongan (Jembatan ke Master Pekerjaan)
        $lowongan = $this->lowonganModel->find($pelamar['id_lowongan']);
        if (!$lowongan) {
            return redirect()->back()->with('error', 'Data Lowongan tidak ditemukan.');
        }

        // 3. Cek apakah Pelamar sudah ada di Alternatives?
        $cek = $this->alternativesModel->where('nama', $pelamar['nama'])->first();
        
        if (!$cek) {
            // 4. SIMPAN KE ALTERNATIVES (Karyawan)
            // Perhatikan nama kolomnya sesuai database baru: pekerjaan_id, kode, nama
            $this->alternativesModel->save([
                'pekerjaan_id' => $lowongan['pekerjaan_id'], // Ambil dari lowongan
                'kode'         => 'K' . sprintf("%03d", $pelamar['id']), // Kolom 'kode' bukan 'code'
                'nama'         => $pelamar['nama'],
                'status'       => 'Aktif'
            ]);
            
            return redirect()->back()->with('success', 'Berhasil! Pelamar resmi menjadi Karyawan.');
        } else {
            return redirect()->back()->with('error', 'Pelamar ini sudah terdaftar.');
        }
    }
}
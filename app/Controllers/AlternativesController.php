<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlternativesModel;
use App\Models\PekerjaanModel;
use App\Models\DataModel;

class AlternativesController extends BaseController
{
    protected $alternativesModel;
    protected $pekerjaanModel;
    protected $dataModel;

    public function __construct()
    {
        $this->alternativesModel = new AlternativesModel();
        $this->pekerjaanModel    = new PekerjaanModel();
        $this->dataModel         = new DataModel();
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

    public function delete($id)
    {
        $this->alternativesModel->delete($id);
        return redirect()->to(base_url('admin/alternatives'))->with('success', 'Data berhasil dihapus');
    }
    
}
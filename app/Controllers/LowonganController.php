<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LowonganModel;
use App\Models\PekerjaanModel;
use App\Models\DataModel;
use App\Models\CriteriaModel;
use App\Models\SubcriteriaModel;
use App\Models\AlternativesModel;

class LowonganController extends BaseController
{
    protected $lowonganModel;
    protected $pekerjaanModel;
    protected $dataModel;
    protected $criteriaModel;
    protected $subcriteriaModel;
    protected $alternativesModel;

    public function __construct()
    {
        $this->lowonganModel     = new LowonganModel();
        $this->pekerjaanModel    = new PekerjaanModel();
        $this->dataModel         = new DataModel();
        $this->criteriaModel     = new CriteriaModel();
        $this->subcriteriaModel  = new SubcriteriaModel();
        $this->alternativesModel = new AlternativesModel();
        helper('text');
    }

    // =========================================================================
    // BAGIAN 1: MANAJEMEN LOWONGAN (CRUD)
    // =========================================================================

    public function index()
    {
        $divisiFilter = $this->request->getGet('field');

        // 1. Ambil Daftar Divisi Unik untuk Dropdown Filter
        // Menggunakan 'divisi' sesuai struktur baru
        $divisiList = $this->pekerjaanModel->groupBy('divisi')->orderBy('divisi', 'ASC')->findAll();

        // 2. Setup Query dengan Join ke Tabel Pekerjaan
        $builder = $this->lowonganModel->select('lowongan.*, pekerjaan.divisi, pekerjaan.posisi, COUNT(data.id) as jumlah_pelamar');
        $builder->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id');
        $builder->join('data', 'data.id_lowongan = lowongan.id', 'left');
        $builder->groupBy('lowongan.id');

        // 3. Filter Logic
        if ($divisiFilter && $divisiFilter != 'all') {
            $builder->where('pekerjaan.divisi', $divisiFilter);
        }

        $lowongan = $builder->orderBy('lowongan.id', 'DESC')->findAll();

        return view('admin/lowongan/index', [
            'title'         => 'Kelola Lowongan',
            'lowongan'      => $lowongan,
            'divisiList'    => $divisiList,
            'selectedField' => $divisiFilter ?? 'all'
        ]);
    }

    public function create()
    {
        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $formulirModel  = new \App\Models\FormulirModel(); // Load Model Formulir

        $pekerjaanList = $pekerjaanModel->orderBy('divisi', 'ASC')->orderBy('posisi', 'ASC')->findAll();
        $formulirList  = $formulirModel->findAll(); // Ambil semua template

        return view('admin/lowongan/create', [
            'title'     => 'Tambah Lowongan',
            'pekerjaan' => $pekerjaanList,
            'formulir'  => $formulirList // Kirim ke view
        ]);
    }

    public function store()
    {
        if (!$this->validate([
            'judul_lowongan' => 'required',
            'pekerjaan_id'   => 'required', 
            'jenis'          => 'required',
            'deskripsi'      => 'required',
            'tanggal_posting'=> 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Harap lengkapi data wajib.');
        }

        // --- 1. PROSES DATA PERTANYAAN KHUSUS ---
        $q_text    = $this->request->getPost('q_text');
        $q_type    = $this->request->getPost('q_type');
        $q_options = $this->request->getPost('q_options');

        $finalConfig = [];

        if (!empty($q_text)) {
            foreach ($q_text as $key => $val) {
                // Hanya simpan jika pertanyaan tidak kosong
                if(!empty($val)) {
                    $finalConfig[] = [
                        'label'   => $val,
                        'type'    => $q_type[$key],
                        'options' => $q_options[$key] ?? '' 
                    ];
                }
            }
        }
        
        $jsonConfig = !empty($finalConfig) ? json_encode($finalConfig) : null;

        // --- 2. SIMPAN SEMUA DATA KE DATABASE ---
        $this->lowonganModel->save([
            'pekerjaan_id'     => $this->request->getPost('pekerjaan_id'), 
            'judul_lowongan'   => $this->request->getPost('judul_lowongan'),
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'jenis'            => $this->request->getPost('jenis'),
            'link_google_form' => $this->request->getPost('link_google_form'),
            'tanggal_posting'  => $this->request->getPost('tanggal_posting'),
            
            // --- TAMBAHAN PENTING ---
            'formulir_id'      => $this->request->getPost('formulir_id'), // Tangkap ID Template
            // 'form_config'   => $jsonConfig, // Opsional: Jika masih pakai form manual
        ]);

        return redirect()->to(base_url('admin/lowongan'))->with('success', 'Lowongan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $lowongan = $this->lowonganModel->find($id);
        if (!$lowongan) return redirect()->to('admin/lowongan');

        $pekerjaanList = $this->pekerjaanModel->orderBy('divisi', 'ASC')->orderBy('posisi', 'ASC')->findAll();
        
        // TAMBAHAN: AMBIL DATA FORMULIR
        $formulirModel = new \App\Models\FormulirModel();
        $formulirList  = $formulirModel->findAll();

        return view('admin/lowongan/edit', [
            'title'     => 'Edit Lowongan',
            'lowongan'  => $lowongan,
            'pekerjaan' => $pekerjaanList,
            'formulir'  => $formulirList // Kirim ke view
        ]);
    }

    public function update($id)
    {
        // Validasi Input (Sama seperti store)
        if (!$this->validate([
            'judul_lowongan' => 'required',
            'pekerjaan_id'   => 'required',
            'jenis'          => 'required',
            'deskripsi'      => 'required',
            'tanggal_posting'=> 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Gagal update. Lengkapi data.');
        }

        $this->lowonganModel->update($id, [
            'pekerjaan_id'     => $this->request->getPost('pekerjaan_id'), 
            'judul_lowongan'   => $this->request->getPost('judul_lowongan'),
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'jenis'            => $this->request->getPost('jenis'),
            'link_google_form' => $this->request->getPost('link_google_form'),
            'tanggal_posting'  => $this->request->getPost('tanggal_posting'),
            'formulir_id'      => $this->request->getPost('formulir_id'), 
        ]);

        return redirect()->to(base_url('admin/lowongan'))->with('success', 'Lowongan berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->lowonganModel->delete($id);
        return redirect()->to(base_url('admin/lowongan'))->with('success', 'Lowongan berhasil dihapus!');
    }

    // =========================================================================
    // BAGIAN 2: DETAIL LOWONGAN & DAFTAR PELAMAR
    // =========================================================================

    public function detail($id)
    {
        // Cari lowongan
        $lowongan = $this->lowonganModel->find($id);
        if (!$lowongan) {
            return redirect()->to('admin/lowongan')->with('error', 'Lowongan tidak ditemukan.');
        }

        // Ambil nama pekerjaan manual (Join manual karena find() default CI4 tidak join)
        // Menggunakan 'pekerjaan_id'
        $pk = $this->pekerjaanModel->find($lowongan['pekerjaan_id']);
        
        // Gabungkan Divisi dan Posisi untuk tampilan
        $lowongan['nama_pekerjaan'] = $pk ? ($pk['divisi'] . ' - ' . $pk['posisi']) : '-';

        // Ambil pelamar berdasarkan id_lowongan
        $pelamar = $this->dataModel
            ->where('id_lowongan', $id)
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('admin/lowongan/detail', [
            'title'    => 'Detail Lowongan',
            'lowongan' => $lowongan,
            'pelamar'  => $pelamar
        ]);
    }

    // =========================================================================
    // BAGIAN 3: PROSES SELEKSI & PENILAIAN SPK (METODE WP)
    // =========================================================================

    // Halaman Form Penilaian Pelamar
    public function detailPelamar($id_data)
    {
        // 1. Ambil Data Pelamar & Info Standar SPK dari Tabel Pekerjaan
        $pelamar = $this->dataModel
            ->select('data.*, lowongan.judul_lowongan, lowongan.pekerjaan_id as job_field_id, pekerjaan.divisi, pekerjaan.posisi as nama_pekerjaan, pekerjaan.standar_spk')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id')
            ->find($id_data);

        if (!$pelamar) return redirect()->back()->with('error', 'Data pelamar tidak ditemukan.');

        // 2. Ambil Kriteria sesuai ID Pekerjaan (Posisi)
        $criteria = $this->criteriaModel
            ->where('pekerjaan_id', $pelamar['job_field_id'])
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

        return view('admin/lowongan/detail_pelamar', [
            'title'       => 'Penilaian Pelamar',
            'data'        => $pelamar,
            'criteria'    => $criteria,
            'subcriteria' => $subcriteria
        ]);
    }


    public function hitungSPK($id_data)
    {
        // 1. Ambil Inputan dari Form
        $input_criteria_ids = $this->request->getPost('criteria_id'); // Array ID [1, 2, 5]
        $input_values       = $this->request->getPost('value');       // Array Nilai [3, 4, 2]

        // Buat array assosiatif untuk memudahkan pencarian nilai: [id_kriteria => nilai]
        // Contoh: [1 => 3, 2 => 4, 5 => 2]
        $submittedValues = [];
        if (!empty($input_criteria_ids)) {
            foreach ($input_criteria_ids as $k => $id) {
                $submittedValues[$id] = $input_values[$k];
            }
        }

        // 2. Ambil Data Pelamar
        $pelamar = $this->dataModel
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->find($id_data);

        if (!$pelamar) return redirect()->back()->with('error', 'Data pelamar tidak valid.');

        // 3. Ambil SEMUA Kriteria Master untuk Pekerjaan Ini
        $allCriteria = $this->criteriaModel->where('pekerjaan_id', $pelamar['pekerjaan_id'])->findAll();
        
        if (empty($allCriteria)) {
            return redirect()->back()->with('error', 'Kriteria penilaian belum diatur untuk posisi ini.');
        }

        // 4. Hitung Total Bobot (Untuk Normalisasi)
        $totalBobot = 0;
        foreach ($allCriteria as $c) {
            $totalBobot += $c['bobot'];
        }

        // 5. Mulai Perhitungan Vector S
        $vectorS = 1;
        
        // Kita Looping berdasarkan KRITERIA MASTER, bukan inputan. 
        // Agar urutan dan kelengkapannya terjamin.
        foreach ($allCriteria as $c) {
            
            // Ambil nilai yang diinputkan user untuk kriteria ini
            // Jika tidak ada input (lupa isi), default ke 1 (nilai netral perkalian) atau bisa throw error
            $nilai = isset($submittedValues[$c['id']]) ? (float)$submittedValues[$c['id']] : 1;

            // A. Normalisasi Pangkat (Bobot / Total)
            $bobotNormal = ($totalBobot > 0) ? ($c['bobot'] / $totalBobot) : 0;

            // B. Cek Tipe (Cost = Pangkat Negatif)
            $pangkat = (strtolower($c['tipe']) == 'cost') ? -$bobotNormal : $bobotNormal;

            // C. Cegah nilai 0 (Math Error pada pemangkatan)
            if ($nilai <= 0) $nilai = 1; // Default nilai terkecil aman

            // D. Rumus: S *= Nilai ^ Pangkat
            $vectorS *= pow($nilai, $pangkat);
        }

        // 6. Update Skor
        $this->dataModel->update($id_data, ['spk_score' => $vectorS]);

        return redirect()->to(base_url('admin/lowongan/pelamar/' . $id_data))
            ->with('success', 'Skor WP (Vector S) berhasil dihitung: ' . number_format($vectorS, 4));
    }

    // Finalisasi (Tombol Selesai)
    public function finalisasi($id_data)
    {
        $status = $this->request->getGet('status'); // Ambil status dari URL

        // Update Status & Pindahkan ke History
        $this->dataModel->update($id_data, [
            'status'     => $status,
            'is_history' => 1 // Tandai sebagai arsip
        ]);

        // Ambil ID Lowongan untuk redirect
        $dataPelamar = $this->dataModel->find($id_data);
        
        return redirect()->to(base_url('admin/lowongan/detail/' . $dataPelamar['id_lowongan']))
            ->with('success', 'Pelamar dipindahkan ke Menu Data (Arsip).');
    }
}
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

    public function index()
    {
        $divisiFilter = $this->request->getGet('field');

        // 1. Ambil Daftar Divisi
        $divisiList = $this->pekerjaanModel->groupBy('divisi')->orderBy('divisi', 'ASC')->findAll();

        // 2. Setup Query
        $builder = $this->lowonganModel->select('lowongan.*, pekerjaan.divisi, pekerjaan.posisi, COUNT(data.id) as jumlah_pelamar');
        $builder->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id');
        
        // --- PERBAIKAN DISINI ---
        // Tambahkan kondisi: AND data.is_history = 0
        // Artinya: Hanya hitung data yang is_history-nya 0 (Belum Selesai/Masih Aktif)
        $builder->join('data', 'data.id_lowongan = lowongan.id AND data.is_history = 0', 'left');
        
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
        // PERBAIKAN: Tambahkan SELECT dan JOIN agar 'nama_pekerjaan' terbaca
        $lowongan = $this->lowonganModel
            ->select('lowongan.*, pekerjaan.divisi, pekerjaan.posisi as nama_pekerjaan') // Ambil kolom posisi sebagai nama_pekerjaan
            ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id')
            ->find($id);

        if (!$lowongan) return redirect()->to('admin/lowongan');

        // FILTER: Hanya tampilkan yang BELUM HISTORY (Aktif)
        $pelamar = $this->dataModel
            ->where('id_lowongan', $id)
            ->where('is_history', 0) 
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
        // 1. Ambil Data Pelamar
        $pelamar = $this->dataModel
            ->select('data.*, lowongan.judul_lowongan, lowongan.pekerjaan_id, pekerjaan.divisi, pekerjaan.posisi as nama_pekerjaan, pekerjaan.standar_spk')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id')
            ->find($id_data);

        if (!$pelamar) return redirect()->back()->with('error', 'Data pelamar tidak ditemukan.');

        // 2. LOGIKA BARU: Ambil Kriteria Berdasarkan DIVISI
        // Cari ID Pekerjaan Pertama di Divisi tersebut (sebagai perwakilan tempat simpan kriteria)
        $divisiName = $pelamar['divisi'];
        
        $repJob = $this->pekerjaanModel
            ->where('divisi', $divisiName)
            ->orderBy('id', 'ASC')
            ->first();

        $criteria = [];
        if ($repJob) {
            $criteria = $this->criteriaModel->where('pekerjaan_id', $repJob['id'])->findAll();
        }

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
            'criteria'    => $criteria, // Ini Kriteria Divisi
            'subcriteria' => $subcriteria
        ]);
    }

    // PROSES HITUNG SKOR
    public function hitungSPK($id_data)
    {
        $input_criteria_ids = $this->request->getPost('criteria_id'); 
        $input_values       = $this->request->getPost('value');       

        $submittedValues = [];
        if (!empty($input_criteria_ids)) {
            foreach ($input_criteria_ids as $k => $id) {
                $submittedValues[$id] = $input_values[$k];
            }
        }

        // Ambil Data Pelamar & Divisinya
        $pelamar = $this->dataModel
            ->select('data.*, pekerjaan.divisi')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id')
            ->find($id_data);

        // Ambil Kriteria Divisi (Logic sama)
        $divisiName = $pelamar['divisi'];
        $repJob = $this->pekerjaanModel->where('divisi', $divisiName)->orderBy('id', 'ASC')->first();
        
        if (!$repJob) return redirect()->back()->with('error', 'Divisi tidak valid.');

        $allCriteria = $this->criteriaModel->where('pekerjaan_id', $repJob['id'])->findAll();
        
        if (empty($allCriteria)) {
            return redirect()->back()->with('error', 'Kriteria belum diatur untuk Divisi: ' . $divisiName);
        }

        // Hitung Total Bobot
        $totalBobot = 0;
        foreach ($allCriteria as $c) {
            $totalBobot += $c['bobot'];
        }

        // Hitung Vector S
        $vectorS = 1;
        $formData = []; // Untuk simpan history jawaban

        foreach ($allCriteria as $c) {
            $nilai = isset($submittedValues[$c['id']]) ? (float)$submittedValues[$c['id']] : 1;

            $bobotNormal = ($totalBobot > 0) ? ($c['bobot'] / $totalBobot) : 0;
            $pangkat = (strtolower($c['tipe']) == 'cost') ? -$bobotNormal : $bobotNormal;

            if ($nilai <= 0) $nilai = 1; 
            $vectorS *= pow($nilai, $pangkat);

            // Simpan label untuk history
            $subText = $nilai;
            $subData = $this->subcriteriaModel->where('criteria_id', $c['id'])->where('bobot_sub', $nilai)->first();
            if($subData) $subText = $subData['keterangan'] . " (Nilai: $nilai)";
            $formData[$c['nama']] = $subText;
        }

        // Update Database
        $this->dataModel->update($id_data, [
            'spk_score' => $vectorS,
            'form_data' => json_encode($formData)
        ]);

        return redirect()->to(base_url('admin/lowongan/pelamar/' . $id_data))
            ->with('success', 'Skor WP berhasil dihitung: ' . number_format($vectorS, 4));
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
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LowonganModel;
use App\Models\PekerjaanModel;
use App\Models\DataModel;
use App\Models\CriteriaModel;
use App\Models\SubcriteriaModel;
use App\Models\AlternativesModel;
use App\Models\PelamarModel;

class LowonganController extends BaseController
{
    protected $lowonganModel;
    protected $pekerjaanModel;
    protected $dataModel;
    protected $criteriaModel;
    protected $subcriteriaModel;
    protected $alternativesModel;
    protected $pelamarModel;

    public function __construct()
    {
        $this->lowonganModel     = new LowonganModel();
        $this->pekerjaanModel    = new PekerjaanModel();
        $this->dataModel         = new DataModel();
        $this->criteriaModel     = new CriteriaModel();
        $this->subcriteriaModel  = new SubcriteriaModel();
        $this->alternativesModel = new AlternativesModel();
        $this->pelamarModel      = new PelamarModel();
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

    public function toggleStatus($id)
    {
        $lowongan = $this->lowonganModel->find($id);
        if ($lowongan) {
            $newStatus = ($lowongan['status'] == 'open') ? 'closed' : 'open';
            $this->lowonganModel->update($id, ['status' => $newStatus]);
        }
        return redirect()->to(base_url('admin/lowongan'))->with('success', 'Status lowongan diperbarui.');
    }
    
    public function create()
    {
        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $formulirModel  = new \App\Models\FormulirModel(); 

        $pekerjaanList = $pekerjaanModel->orderBy('divisi', 'ASC')->orderBy('posisi', 'ASC')->findAll();
        $allFormulir   = $formulirModel->findAll();

        // --- FILTER TEMPLATE ---
        $templateInternal  = []; // Untuk Pertanyaan Kustom
        $templateEksternal = []; // Untuk Google Form

        foreach($allFormulir as $f) {
            // Cek Pertanyaan
            $cfg = json_decode($f['config'] ?? '[]', true);
            if (!empty($cfg)) {
                $templateInternal[] = $f;
            }
            
            // Cek Link GForm
            if (!empty($f['link_google_form'])) {
                $templateEksternal[] = $f;
            }
        }

        return view('admin/lowongan/create', [
            'title'             => 'Tambah Lowongan',
            'pekerjaan'         => $pekerjaanList,
            'templateInternal'  => $templateInternal, // Kirim ke view
            'templateEksternal' => $templateEksternal // Kirim ke view
        ]);
    }

    public function edit($id)
    {
        $lowongan = $this->lowonganModel->find($id);
        if (!$lowongan) return redirect()->to('admin/lowongan');

        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $formulirModel  = new \App\Models\FormulirModel();

        $pekerjaanList = $pekerjaanModel->orderBy('divisi', 'ASC')->orderBy('posisi', 'ASC')->findAll();
        $allFormulir   = $formulirModel->findAll();

        // --- FILTER TEMPLATE (SAMA SEPERTI CREATE) ---
        $templateInternal  = [];
        $templateEksternal = [];

        foreach($allFormulir as $f) {
            $cfg = json_decode($f['config'] ?? '[]', true);
            if (!empty($cfg)) $templateInternal[] = $f;
            if (!empty($f['link_google_form'])) $templateEksternal[] = $f;
        }

        return view('admin/lowongan/edit', [
            'title'             => 'Edit Lowongan',
            'lowongan'          => $lowongan,
            'pekerjaan'         => $pekerjaanList,
            'templateInternal'  => $templateInternal,
            'templateEksternal' => $templateEksternal
        ]);
    }

    public function store()
    {
        // Validasi (formulir_id dihapus dari required)
        if (!$this->validate([
            'judul_lowongan' => 'required',
            'pekerjaan_id'   => 'required', 
            'jenis'          => 'required',
            'deskripsi'      => 'required',
            'tanggal_mulai'  => 'required',
            'tanggal_selesai'=> 'required',
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
        $formulirId = $this->request->getPost('formulir_id');
        if (empty($formulirId)) {
            $formulirId = null;
        }

        $this->lowonganModel->save([
            'pekerjaan_id'     => $this->request->getPost('pekerjaan_id'), 
            'judul_lowongan'   => $this->request->getPost('judul_lowongan'),
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'jenis'            => $this->request->getPost('jenis'),
            'status'           => 'open', // Default Open saat buat baru
            'tanggal_mulai'    => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai'  => $this->request->getPost('tanggal_selesai'),
            'link_google_form' => $this->request->getPost('link_google_form'),
            'tanggal_posting'  => date('Y-m-d'), // Tetap simpan tgl pembuatan
            'formulir_id'      => $formulirId, 
        ]);

        return redirect()->to(base_url('admin/lowongan'))->with('success', 'Lowongan berhasil ditambahkan!');
    }

    public function update($id)
    {
        if (!$this->validate([
            'judul_lowongan' => 'required',
            'pekerjaan_id'   => 'required',
            'jenis'          => 'required',
            'deskripsi'      => 'required',
            'tanggal_mulai'  => 'required',
            'tanggal_selesai'=> 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Gagal update. Lengkapi data.');
        }

        $formulirId = $this->request->getPost('formulir_id');
        if (empty($formulirId)) {
            $formulirId = null;
        }

        $this->lowonganModel->update($id, [
            'pekerjaan_id'     => $this->request->getPost('pekerjaan_id'), 
            'judul_lowongan'   => $this->request->getPost('judul_lowongan'),
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'jenis'            => $this->request->getPost('jenis'),
            'status'           => $this->request->getPost('status'), // Bisa edit status manual juga
            'tanggal_mulai'    => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai'  => $this->request->getPost('tanggal_selesai'),
            'link_google_form' => $this->request->getPost('link_google_form'),
            'formulir_id'      => $formulirId, 
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
        $lowongan = $this->lowonganModel
            ->select('lowongan.*, pekerjaan.divisi, pekerjaan.posisi as nama_pekerjaan') 
            ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id')
            ->find($id);

        if (!$lowongan) return redirect()->to('admin/lowongan');

        $pelamar = $this->dataModel
            // PERBAIKAN: Tambahkan 'pelamar.no_ktp' di sini
            ->select('data.*, 
                      pelamar.id as pelamar_id, 
                      pelamar.no_ktp, 
                      pelamar.nama_lengkap as nama, 
                      pelamar.no_hp, 
                      pelamar.is_blacklisted, 
                      data.tanggal_daftar as created_at') 
            ->join('pelamar', 'pelamar.id = data.pelamar_id')
            ->where('data.id_lowongan', $id)
            ->where('data.is_history', 0) 
            ->orderBy('data.id', 'DESC')
            ->findAll();

        return view('admin/lowongan/detail', [
            'title'    => 'Detail Lowongan',
            'lowongan' => $lowongan,
            'pelamar'  => $pelamar
        ]);
    }

    // 2. TAMBAHKAN METHOD BARU UNTUK PROSES BLACKLIST
    public function blacklist()
    {
        $idPelamar = $this->request->getPost('id_pelamar');
        $alasan    = $this->request->getPost('alasan');
        $tipe      = $this->request->getPost('tipe_blacklist'); // Input Baru

        if ($idPelamar && $tipe) {
            
            // 1. UPDATE MASTER PELAMAR
            $this->pelamarModel->update($idPelamar, [
                'is_blacklisted'   => 1,
                'blacklist_type'   => $tipe, // Simpan Tipe (permanent/temporary)
                'alasan_blacklist' => $alasan
            ]);

            // 2. UPDATE TRANSAKSI LAMARAN (Arsipkan lamaran aktif)
            $this->dataModel
                ->where('pelamar_id', $idPelamar)
                ->where('status', 'blacklist') 
                ->orWhere('is_history', 0) // Ambil semua yang aktif
                ->where('pelamar_id', $idPelamar) // Pastikan ID pelamar lagi agar orWhere aman
                ->set([
                    'status'     => 'blacklist',
                    'is_history' => 1
                ])
                ->update();

            return redirect()->back()->with('success', 'Pelamar telah di-blacklist (' . ucfirst($tipe) . ').');
        }

        return redirect()->back()->with('error', 'Gagal. Pastikan data lengkap.');
    }

    // =========================================================================
    // BAGIAN 3: PROSES SELEKSI & PENILAIAN SPK (METODE WP)
    // =========================================================================

    // Halaman Form Penilaian Pelamar
    public function detailPelamar($id_data)
    {
        // PERBAIKAN: Select bersih tanpa komentar & Alias yang membingungkan
        $pelamar = $this->dataModel
            ->select('data.*, 
                      pelamar.no_ktp,
                      pelamar.nama_lengkap, 
                      pelamar.email, 
                      pelamar.no_hp, 
                      pelamar.foto_profil,
                      pelamar.file_cv,
                      pelamar.is_blacklisted,
                      pelamar.alasan_blacklist,
                      lowongan.judul_lowongan, 
                      lowongan.pekerjaan_id, 
                      pekerjaan.divisi, 
                      pekerjaan.posisi as nama_pekerjaan, 
                      pekerjaan.standar_spk')
            ->join('pelamar', 'pelamar.id = data.pelamar_id')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id')
            ->find($id_data);

        if (!$pelamar) return redirect()->back()->with('error', 'Data pelamar tidak ditemukan.');

        
        // 2. LOGIKA BARU: Ambil Kriteria Berdasarkan DIVISI
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
            'criteria'    => $criteria,
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

        // Ambil Data Pelamar & Divisi
        $pelamar = $this->dataModel
            ->select('data.*, pekerjaan.divisi')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id')
            ->find($id_data);

        // Ambil Kriteria Divisi
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
        $logData = []; // Variabel baru untuk LOG PENILAIAN (Bukan jawaban pelamar)

        foreach ($allCriteria as $c) {
            $nilai = isset($submittedValues[$c['id']]) ? (float)$submittedValues[$c['id']] : 1;

            $bobotNormal = ($totalBobot > 0) ? ($c['bobot'] / $totalBobot) : 0;
            $pangkat = (strtolower($c['tipe']) == 'cost') ? -$bobotNormal : $bobotNormal;

            if ($nilai <= 0) $nilai = 1; 
            $vectorS *= pow($nilai, $pangkat);

            // Simpan label untuk history admin
            $subText = $nilai;
            $subData = $this->subcriteriaModel->where('criteria_id', $c['id'])->where('bobot_sub', $nilai)->first();
            if($subData) $subText = $subData['keterangan'] . " (Nilai: $nilai)";
            
            $logData[$c['nama']] = $subText;
        }

        // UPDATE DATABASE
        // PERBAIKAN UTAMA: Simpan ke 'spk_log', JANGAN sentuh 'form_data'
        $this->dataModel->update($id_data, [
            'spk_score' => $vectorS,
            'spk_log'   => json_encode($logData) // Simpan rincian penilaian disini
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
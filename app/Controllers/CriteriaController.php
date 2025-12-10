<?php
namespace App\Controllers;

use App\Models\CriteriaModel;

class CriteriaController extends BaseController
{
    protected $criteriaModel;

    public function __construct()
    {
        $this->criteriaModel = new CriteriaModel();
    }

    public function index()
    {
        $field = $this->request->getGet('field') ?? 'all';

        $pekerjaanModel = new \App\Models\PekerjaanModel();
        
        // --- PERBAIKAN: AMBIL DIVISI UNIK UNTUK FILTER ---
        // Kita gunakan GROUP BY agar nama divisi tidak duplikat di dropdown
        $divisiList = $pekerjaanModel->groupBy('divisi')->orderBy('divisi', 'ASC')->findAll();

        // --- LOGIKA FILTER DATA ---
        if ($field !== 'all') {
            // Jika filter dipilih (field berisi Nama Divisi, bukan ID)
            // 1. Cari semua ID Pekerjaan yang termasuk dalam divisi tersebut
            $jobsInDivisi = $pekerjaanModel->where('divisi', $field)->findColumn('id');
            
            if (!empty($jobsInDivisi)) {
                $criteria = $this->criteriaModel->whereIn('pekerjaan_id', $jobsInDivisi)->findAll();
            } else {
                $criteria = [];
            }
            
            $selectedFieldName = $field; // Nama Divisi
        } else {
            $criteria = $this->criteriaModel->findAll();
            $selectedFieldName = 'Semua Divisi';
        }

        return view('admin/criteria/index', [
            'title'             => 'Data Kriteria',
            'criteria'          => $criteria,
            'divisiList'        => $divisiList, // Kirim list divisi unik
            'pekerjaanFull'     => $pekerjaanModel->findAll(), // Kirim data lengkap untuk lookup nama jabatan di tabel
            'selectedField'     => $field,
            'selectedFieldName' => $selectedFieldName
        ]);
    }

    public function create()
    {
        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $selectedDivisi = $this->request->getGet('field'); // Ini berisi Nama Divisi (misal: IT)
        
        // Ambil hanya jabatan yang sesuai dengan divisi yang dipilih
        if ($selectedDivisi && $selectedDivisi != 'all') {
            $pekerjaanList = $pekerjaanModel->where('divisi', $selectedDivisi)->findAll();
        } else {
            $pekerjaanList = $pekerjaanModel->orderBy('divisi', 'ASC')->findAll();
        }
        
        return view('admin/criteria/create', [
            'title'         => 'Tambah Kriteria',
            'pekerjaan'     => $pekerjaanList,
            'selectedField' => null // Tidak dipakai lagi karena logic sudah di filter query atas
        ]);
    }

    public function store()
    {
        // Ambil nama divisi dari form
        $divisiName = $this->request->getPost('nama_divisi');
        
        // Cari ID pekerjaan pertama yang punya divisi ini (sebagai perwakilan)
        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $job = $pekerjaanModel->where('divisi', $divisiName)->first();

        if (!$job) {
            return redirect()->back()->with('error', 'Divisi tidak valid.');
        }

        $this->criteriaModel->insert([
            'pekerjaan_id' => $job['id'], // Simpan ID perwakilan
            'kode'         => $this->request->getPost('kode'),
            'nama'         => $this->request->getPost('nama'),
            'bobot'        => $this->request->getPost('bobot'),
            'tipe'         => $this->request->getPost('tipe')
        ]);

        return redirect()->to(base_url('admin/criteria?field=' . urlencode($divisiName)))
                         ->with('success', 'Kriteria Divisi berhasil ditambahkan.');
    }


    public function edit($id)
    {
    $pekerjaanModel = new \App\Models\PekerjaanModel();

    $data = [
        'title' => 'Edit Kriteria',
        'criteria' => $this->criteriaModel->find($id),
        'pekerjaan' => $pekerjaanModel->findAll() 
    ];

    return view('admin/criteria/edit', $data);
    }


    public function update($id)
    {
    $this->criteriaModel->update($id, [
        'pekerjaan_id'  => $this->request->getPost('pekerjaan_id'),
        'kode'          => $this->request->getPost('kode'),
        'nama'          => $this->request->getPost('nama'),
        'bobot'         => $this->request->getPost('bobot'),
        'tipe'          => $this->request->getPost('tipe')
    ]);

    return redirect()->to(base_url('admin/criteria'))
        ->with('success', 'Kriteria berhasil diperbarui.');
    }


    public function delete($id)
    {
        $this->criteriaModel->delete($id);
        return redirect()->to(base_url('admin/criteria'))->with('success', 'Kriteria berhasil dihapus.');
    }

    //ini set standar penilaian tiap jenis pekerjaan
    public function standar()
    {
        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $criteriaModel  = new \App\Models\CriteriaModel();
        $subModel       = new \App\Models\SubcriteriaModel();

        // 1. Tangkap Input
        $jobId  = $this->request->getGet('pekerjaan'); // Jika user memilih lewat dropdown di halaman ini
        $divisi = $this->request->getGet('divisi');    // Jika user melempar filter dari halaman index

        $selectedJob = null;

        // 2. LOGIKA AUTO-SELECT
        if ($jobId) {
            // Prioritas 1: Jika ada ID Pekerjaan (user baru saja memilih dropdown)
            $selectedJob = $pekerjaanModel->find($jobId);
        } elseif ($divisi && $divisi != 'all') {
            // Prioritas 2: Jika dari halaman index (bawa parameter divisi)
            // Otomatis ambil posisi PERTAMA di divisi tersebut agar user tidak perlu "klik pilih lagi"
            $selectedJob = $pekerjaanModel->where('divisi', urldecode($divisi))
                                          ->orderBy('posisi', 'ASC')
                                          ->first();
        }

        // 3. LOGIKA LIST DROPDOWN
        // Jika sudah ada posisi terpilih (atau divisi terpilih), 
        // dropdown HANYA menampilkan teman-teman satu divisinya.
        if ($selectedJob) {
            $targetDivisi = $selectedJob['divisi'];
            $pekerjaanList = $pekerjaanModel->where('divisi', $targetDivisi)
                                            ->orderBy('posisi', 'ASC')
                                            ->findAll();
        } else {
            // Jika belum ada apa-apa (akses langsung menu), tampilkan semua (dikelompokkan)
            $pekerjaanList = $pekerjaanModel->orderBy('divisi', 'ASC')
                                            ->orderBy('posisi', 'ASC')
                                            ->findAll();
        }

        // 4. AMBIL KRITERIA (Berdasarkan Divisi dari Posisi Terpilih)
        $criteria = [];
        $sub      = [];

        if ($selectedJob) {
            $divisiName = $selectedJob['divisi'];
            // Cari perwakilan divisi untuk ambil kriteria
            $repJob = $pekerjaanModel->where('divisi', $divisiName)->orderBy('id', 'ASC')->first();

            if ($repJob) {
                $criteria = $criteriaModel->where('pekerjaan_id', $repJob['id'])->findAll();
                foreach ($criteria as $c) {
                    $sub[$c['id']] = $subModel->where('criteria_id', $c['id'])->findAll();
                }
            }
        }

        return view('admin/criteria/standar', [
            'title'         => 'Set Standar Nilai',
            'pekerjaanList' => $pekerjaanList,
            'selected'      => $selectedJob,
            'criteria'      => $criteria,
            'sub'           => $sub
        ]);
    }

    // ==========================================================
    // SIMPAN NILAI STANDAR (KE POSISI SPESIFIK)
    // ==========================================================
    public function savestandar()
    {
        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $criteriaModel  = new \App\Models\CriteriaModel();
        $subModel       = new \App\Models\SubcriteriaModel();

        // 1. Validasi ID Posisi
        $jobId = $this->request->getPost('pekerjaan_id'); 
        
        if (!$jobId) return redirect()->back()->with('error', 'Posisi tidak ditemukan.');

        // 2. Ambil Data Posisi & Divisinya
        $job = $pekerjaanModel->find($jobId);
        if (!$job) return redirect()->back()->with('error', 'Data posisi invalid.');

        // 3. Ambil Kriteria Divisi (Logic sama seperti method standar)
        $divisiName = $job['divisi'];
        $repJob     = $pekerjaanModel->where('divisi', $divisiName)->orderBy('id', 'ASC')->first();
        
        // Cek kriteria
        $criteria = [];
        if ($repJob) {
            $criteria = $criteriaModel->where('pekerjaan_id', $repJob['id'])->findAll();
        }

        if (empty($criteria)) return redirect()->back()->with('error', 'Tidak ada kriteria di divisi ini.');

        // 4. Hitung Vector S (Standar Kelulusan)
        $totalBobotCriteria = 0;
        foreach ($criteria as $c) {
            $totalBobotCriteria += $c['bobot'];
        }

        $vectorS = 1; 

        foreach ($criteria as $c) {
            // Ambil input nilai standar (dari form)
            $subId = $this->request->getPost('sub_' . $c['id']);
            
            if (!$subId) return redirect()->back()->with('error', 'Lengkapi nilai standar untuk: ' . $c['nama']);
            
            $subData    = $subModel->find($subId);
            $nilaiInput = (float) $subData['bobot_sub'];

            // Normalisasi Pangkat
            $bobotNormal = ($totalBobotCriteria > 0) ? ($c['bobot'] / $totalBobotCriteria) : 0;
            $tipe        = strtolower($c['tipe']);
            $pangkat     = ($tipe == 'cost') ? -$bobotNormal : $bobotNormal;

            // Rumus WP
            $nilaiInput = ($nilaiInput == 0) ? 1 : $nilaiInput; 
            $vectorS   *= pow($nilaiInput, $pangkat);
        }

        // 5. SIMPAN HASIL KE TABEL PEKERJAAN (POSISI SPESIFIK)
        $pekerjaanModel->update($jobId, ['standar_spk' => $vectorS]);

        return redirect()->to('/admin/criteria/standar?pekerjaan=' . $jobId)
                         ->with('success', 'Standar untuk posisi <b>' . esc($job['posisi']) . '</b> berhasil disimpan.');
    }
}

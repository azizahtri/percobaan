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

        // 1. Cek Inputan: Bisa berupa ID Pekerjaan ATAU Nama Divisi
        $jobId      = $this->request->getGet('pekerjaan'); // Dari dropdown di halaman standar
        $divisiName = $this->request->getGet('divisi');    // Dari tombol di halaman index

        $selectedJob = $pekerjaanModel->find($jobId);

        if ($jobId) {
            // Skenario A: User memilih spesifik jabatan dari dropdown
            $selectedJob = $pekerjaanModel->find($jobId);
        } elseif ($divisiName) {
            // Skenario B: User klik tombol "Atur Standar" dari Index (bawa nama divisi)
            // Kita ambil jabatan PERTAMA yang ada di divisi tersebut sebagai default
            $divisiName = urldecode($divisiName);
            $selectedJob = $pekerjaanModel->where('divisi', $divisiName)
                                          ->orderBy('posisi', 'ASC')
                                          ->first();
        }

        // Jika data tidak ditemukan sama sekali
        if (!$selectedJob) {
            return redirect()->to('admin/criteria')->with('error', 'Silakan pilih posisi atau divisi terlebih dahulu.');
        }

        // 2. Ambil SEMUA data untuk Dropdown (Group by Divisi)
        $pekerjaanList = $pekerjaanModel->where('divisi', $selectedJob['divisi'])
                                        ->orderBy('posisi', 'ASC')
                                        ->findAll();

        // 3. Ambil Kriteria & Subkriteria untuk posisi yang SEDANG AKTIF
        $criteria = $criteriaModel->where('pekerjaan_id', $selectedJob['id'])->findAll();
        
        $sub = [];
        foreach ($criteria as $c) {
            $sub[$c['id']] = $subModel->where('criteria_id', $c['id'])->findAll();
        }

        return view('admin/criteria/standar', [
            'title'         => 'Set Standar Nilai',
            'selected'      => $selectedJob,
            'pekerjaanList' => $pekerjaanList,
            'criteria'      => $criteria,
            'sub'           => $sub
        ]);
    }

    public function savestandar()
    {
        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $criteriaModel  = new \App\Models\CriteriaModel();
        $subModel       = new \App\Models\SubcriteriaModel();

        // 1. Validasi Input Dasar
        $field = $this->request->getPost('pekerjaan_id');
        
        if (!$field) {
            return redirect()->back()->with('error', 'Gagal menyimpan: ID Pekerjaan tidak ditemukan.');
        }

        $criteria = $criteriaModel->where('pekerjaan_id', $field)->findAll();
        if (empty($criteria)) {
            return redirect()->back()->with('error', 'Gagal: Tidak ada kriteria untuk dihitung.');
        }

        // 2. Siapkan Data Nilai & Hitung Total Bobot
        $nilai = [];
        $totalBobotCriteria = 0;

        foreach ($criteria as $c) {
            $totalBobotCriteria += $c['bobot']; // Hitung total bobot dulu

            // Ambil input subkriteria (name="sub_ID")
            $subId = $this->request->getPost('sub_' . $c['id']);
            
            if (!$subId) {
                return redirect()->back()->with('error', 'Mohon pilih nilai standar untuk kriteria: ' . $c['nama']);
            }
            
            $sub = $subModel->find($subId);
            $nilai[] = [
                'criteria' => $c,
                'sub'      => $sub
            ];
        }

        // --- MULAI PERHITUNGAN WP ---
        $vectorS = 1; 

        foreach ($nilai as $item) {
            $c = $item['criteria'];
            $s = $item['sub'];
            
            $nilaiInput = (float) $s['bobot_sub'];

            // A. Normalisasi Bobot
            $bobotNormal = ($totalBobotCriteria > 0) ? ($c['bobot'] / $totalBobotCriteria) : 0;

            // B. Tentukan Pangkat (Benefit = Positif, Cost = Negatif)
            $tipe = strtolower($c['tipe']); // pastikan 'tipe' sesuai kolom database
            $pangkat = ($tipe == 'cost') ? -$bobotNormal : $bobotNormal;

            // C. Rumus WP
            $nilaiInput = ($nilaiInput == 0) ? 1 : $nilaiInput; 
            $vectorS *= pow($nilaiInput, $pangkat);
        }

        // 5. Simpan Hasil ke Database
        // Gunakan try-catch untuk menangkap error database
        try {
            $pekerjaanModel->update($field, ['standar_spk' => $vectorS]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database Error: ' . $e->getMessage());
        }

        return redirect()->to('/admin/criteria/standar?pekerjaan=' . $field)
                         ->with('success', 'Standar SPK berhasil dihitung & disimpan: ' . number_format($vectorS, 4));
    }
}

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


    public function update($id) {
    
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

    // =========================================================================
    // 1. PROSES DUPLICATE (PERBAIKAN LOGIKA COPY)
    // =========================================================================
    public function duplicate()
    {
        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $subModel       = new \App\Models\SubcriteriaModel();

        $sourceName = $this->request->getPost('source_divisi');
        $targetName = $this->request->getPost('target_divisi');

        if ($sourceName == $targetName) {
            return redirect()->back()->with('error', 'Divisi asal dan tujuan tidak boleh sama.');
        }

        // Cari ID Perwakilan
        $sourceJob = $pekerjaanModel->where('divisi', $sourceName)->orderBy('id', 'ASC')->first();
        $targetJob = $pekerjaanModel->where('divisi', $targetName)->orderBy('id', 'ASC')->first();

        if (!$sourceJob || !$targetJob) {
            return redirect()->back()->with('error', 'Data divisi tidak valid.');
        }

        // Copy Standar Nilai
        $pekerjaanModel->where('divisi', $targetName)->set(['standar_spk' => $sourceJob['standar_spk']])->update();

        // Ambil Kriteria Sumber
        $sourceCriteria = $this->criteriaModel->where('pekerjaan_id', $sourceJob['id'])->findAll();

        if (empty($sourceCriteria)) {
            return redirect()->back()->with('error', 'Tidak ada kriteria untuk disalin.');
        }

        // --- MULAI PROSES COPY ---
        foreach ($sourceCriteria as $c) {
            // A. Insert Kriteria ke Tujuan
            $this->criteriaModel->insert([
                'pekerjaan_id' => $targetJob['id'],
                'kode'         => $c['kode'],
                'nama'         => $c['nama'],
                'bobot'        => (float)$c['bobot'], // Pastikan float
                'tipe'         => $c['tipe']
            ]);
            
            $newCriteriaId = $this->criteriaModel->getInsertID();

            // B. Insert Subkriteria ke ID Baru
            $sourceSubs = $subModel->where('criteria_id', $c['id'])->findAll();
            foreach ($sourceSubs as $sub) {
                // AMBIL BOBOT DARI SUMBER (Gunakan ?? 0 untuk jaga-jaga)
                $bobotAsal = $sub['bobot_sub'] ?? $sub['bobot'] ?? 0;

                $subModel->insert([
                    'criteria_id' => $newCriteriaId,
                    'keterangan'  => $sub['keterangan'],
                    'bobot_sub'   => (float)$bobotAsal // Pastikan masuk ke kolom bobot_sub
                ]);
            }
        }

        return redirect()->to(base_url('admin/criteria/editDivisi?divisi=' . urlencode($targetName)))
                         ->with('success', "Duplikasi selesai. Silakan cek data di bawah.");
    }

    // =========================================================================
    // 2. PROSES SIMPAN EDIT MASSAL (PERBAIKAN ERROR UNDEFINED KEY)
    // =========================================================================
    public function updateDivisi()
    {
        
        $criteriaModel = new \App\Models\CriteriaModel();
        $subModel      = new \App\Models\SubcriteriaModel();
        $divisiName    = $this->request->getPost('divisi_name');

        // A. UPDATE KRITERIA
        $postCriteria = $this->request->getPost('criteria'); 
        if ($postCriteria) {
            foreach ($postCriteria as $id => $data) {
                $criteriaModel->update($id, [
                    'kode'  => $data['kode'],
                    'nama'  => $data['nama'],
                    'bobot' => (float)$data['bobot'],
                    'tipe'  => $data['tipe']
                ]);
            }
        }

        // B. UPDATE SUBKRITERIA (Data Lama)
        $postSubs = $this->request->getPost('subs'); 
        if ($postSubs) {
            foreach ($postSubs as $subId => $data) {
                // LOGIKA ANTI ERROR: Jika key 'bobot_sub' tidak ada, gunakan 0.
                // Ini mencegah error "Undefined array key"
                $nilaiBobot = $data['bobot_sub'] ?? $data['bobot'] ?? 0;

                $subModel->update($subId, [
                    'keterangan' => $data['keterangan'],
                    'bobot_sub'  => (float)$nilaiBobot 
                ]);
            }
        }

        // C. INSERT SUBKRITERIA BARU
        $newSubs = $this->request->getPost('new_subs');
        if ($newSubs) {
            foreach ($newSubs as $criteriaId => $items) { 
                foreach ($items as $item) {
                    if (!empty($item['keterangan'])) {
                        
                        // LOGIKA ANTI ERROR
                        $nilaiBobot = $item['bobot_sub'] ?? $item['bobot'] ?? 0;

                        $subModel->insert([
                            'criteria_id' => $criteriaId,
                            'keterangan'  => $item['keterangan'],
                            'bobot_sub'   => (float)$nilaiBobot
                        ]);
                    }
                }
            }
        }

        return redirect()->to(base_url('admin/criteria?field=' . urlencode($divisiName)))
                         ->with('success', 'Perubahan berhasil disimpan.');
    }

    // 2. HALAMAN EDIT MASSAL (BULK EDIT)
    // -------------------------------------------------------------------------
    public function editDivisi()
    {
        $divisiName = $this->request->getGet('divisi');
        if (!$divisiName) return redirect()->to('admin/criteria');

        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $criteriaModel  = new \App\Models\CriteriaModel();
        $subModel       = new \App\Models\SubcriteriaModel();

        // Cari perwakilan pekerjaan untuk divisi ini
        $repJob = $pekerjaanModel->where('divisi', $divisiName)->orderBy('id', 'ASC')->first();

        if (!$repJob) return redirect()->back()->with('error', 'Divisi tidak ditemukan.');

        // Ambil semua Kriteria milik divisi ini
        $criteria = $criteriaModel->where('pekerjaan_id', $repJob['id'])->findAll();
        
        // Ambil Subkriteria untuk setiap kriteria
        $subs = [];
        foreach ($criteria as $c) {
            $subs[$c['id']] = $subModel->where('criteria_id', $c['id'])->orderBy('bobot_sub', 'DESC')->findAll();
        }

        return view('admin/criteria/edit_divisi', [
            'title'      => 'Edit Kriteria Divisi: ' . $divisiName,
            'divisiName' => $divisiName,
            'criteria'   => $criteria,
            'subs'       => $subs
        ]);
    }
    // FUNGSI HAPUS SUBKRITERIA (Direct Link)
    public function deleteSub($id)
    {
        $subModel = new \App\Models\SubcriteriaModel();
        $sub      = $subModel->find($id);
        
        if ($sub) {
            // Kita perlu cari nama divisi untuk redirect, agak tricky karena tidak tersimpan langsung di sub
            // Jadi kita redirect back saja
            $subModel->delete($id);
            return redirect()->back()->with('success', 'Subkriteria dihapus.');
        }
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    public function addSingle()
    {
        $pekerjaanModel = new \App\Models\PekerjaanModel();
        $divisiName     = $this->request->getPost('divisi_name'); // Nama Divisi untuk redirect

        // Cari ID perwakilan divisi
        $job = $pekerjaanModel->where('divisi', $divisiName)->first();
        if (!$job) return redirect()->back()->with('error', 'Divisi tidak valid.');

        // Simpan Kriteria Baru
        $this->criteriaModel->insert([
            'pekerjaan_id' => $job['id'],
            'kode'         => $this->request->getPost('kode'),
            'nama'         => $this->request->getPost('nama'),
            'bobot'        => $this->request->getPost('bobot'),
            'tipe'         => $this->request->getPost('tipe')
        ]);

        // Redirect KEMBALI ke halaman Edit Divisi
        return redirect()->to(base_url('admin/criteria/editDivisi?divisi=' . urlencode($divisiName)))
                         ->with('success', 'Kriteria baru berhasil ditambahkan.');
    }

    // -------------------------------------------------------------------------
    // 5. HAPUS SATU KRITERIA (KHUSUS HALAMAN EDIT DIVISI)
    // -------------------------------------------------------------------------
    public function deleteSingle($id)
    {
        $divisiName = $this->request->getGet('divisi'); // Ambil nama divisi dari URL untuk redirect

        $this->criteriaModel->delete($id);

        return redirect()->to(base_url('admin/criteria/editDivisi?divisi=' . urlencode($divisiName)))
                         ->with('success', 'Kriteria berhasil dihapus.');
    }
}

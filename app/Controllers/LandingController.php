<?php
namespace App\Controllers;

use App\Models\LowonganModel;
use App\Models\DataModel;
use App\Models\PelamarModel; // Jangan lupa load ini

class LandingController extends BaseController
{
    protected $lowonganModel;
    protected $dataModel;
    protected $pelamarModel;

    public function __construct()
    {
        $this->lowonganModel = new LowonganModel();
        $this->dataModel     = new DataModel();
        $this->pelamarModel  = new PelamarModel();
        helper('text');
    }

    public function index()
    {
        $data = [
            'title'    => 'Beranda | Karir',
            'lowongan' => $this->lowonganModel->where('status', 'open')->orderBy('tanggal_posting', 'DESC')->findAll(),
        ];
        return view('landing/index', $data);
    }

    public function detail($id)
    {
        // Join ke formulir agar config pertanyaan terbaca (jika ada)
        $lowongan = $this->lowonganModel
            ->select('lowongan.*, formulir.config as form_config')
            ->join('formulir', 'formulir.id = lowongan.formulir_id', 'left')
            ->find($id);

        if (!$lowongan) throw new \CodeIgniter\Exceptions\PageNotFoundException('Lowongan tidak ditemukan');

        return view('landing/detail', ['title' => 'Detail Lowongan', 'lowongan' => $lowongan]);
    }

    // --- PROSES SUBMIT LAMARAN (PERBAIKAN VALIDASI) ---
    public function submitLamaran()
    {
        // 1. SANITASI INPUT
        $ktp        = preg_replace('/[^0-9]/', '', $this->request->getPost('no_ktp')); // Hapus karakter aneh di KTP
        $email      = strtolower(trim($this->request->getPost('email')));
        $idLowongan = $this->request->getPost('id_lowongan');
        
        if(!$ktp || !$idLowongan) {
            return redirect()->back()->withInput()->with('error', 'Data identitas tidak lengkap.');
        }

        // 2. CEK BLACKLIST (KTP & EMAIL) - LEVEL 1
        // Cek apakah data ini terdaftar sebagai blacklist di database
        $cekBlacklist = $this->pelamarModel
            ->groupStart()
                ->where('no_ktp', $ktp)
                ->orWhere('email', $email)
            ->groupEnd()
            ->where('is_blacklisted', 1)
            ->first();

        if ($cekBlacklist) {
            // Jika ketemu, langsung tolak!
            return redirect()->back()->withInput()->with('error', "Mohon maaf, Anda tidak dapat melamar karena masuk dalam daftar Blacklist perusahaan.");
        }

        // 3. CEK DUPLIKASI LAMARAN (SPAM PROTECTION)
        // Cari ID Pelamar berdasarkan KTP atau Email
        $existingPelamar = $this->pelamarModel
            ->groupStart()
                ->where('no_ktp', $ktp)
                ->orWhere('email', $email)
            ->groupEnd()
            ->first();

        if ($existingPelamar) {
            $pelamarId = $existingPelamar['id'];

            // Cek di tabel DATA (Riwayat Lamaran)
            $lastLamaran = $this->dataModel
                ->where('pelamar_id', $pelamarId)
                ->where('id_lowongan', $idLowongan)
                ->orderBy('tanggal_daftar', 'DESC')
                ->first();

            if ($lastLamaran) {
                // Hitung selisih waktu
                $tglLama = new \DateTime($lastLamaran['tanggal_daftar']);
                $diff    = $tglLama->diff(new \DateTime());
                $bulan   = ($diff->y * 12) + $diff->m;

                // Jika kurang dari 6 bulan, tolak
                if ($bulan < 6) {
                    return redirect()->back()->with('error', "Anda sudah melamar posisi ini pada tanggal " . date('d M Y', strtotime($lastLamaran['tanggal_daftar'])) . ". Silakan coba lagi setelah 6 bulan.");
                }
            }
        }

        // --- 4. PROSES UPLOAD FILE (Jika lolos validasi di atas) ---
        $uploadFile = function($fieldName) {
            $file = $this->request->getFile($fieldName);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/berkas', $newName); 
                return $newName;
            }
            return null;
        };

        $cv   = $uploadFile('file_cv');
        $foto = $uploadFile('foto_profil');

        // Data Pelamar
        $dataPelamar = [
            'no_ktp'            => $ktp,
            'nama_lengkap'      => $this->request->getPost('nama_lengkap'),
            'email'             => $email, // Pakai email yang sudah dibersihkan
            'no_hp'             => $this->request->getPost('no_hp'),
            'tempat_lahir'      => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'     => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin'     => $this->request->getPost('jenis_kelamin'),
            'status_pernikahan' => $this->request->getPost('status_pernikahan'),
            'alamat'            => $this->request->getPost('alamat'),
            'link_drive'        => $this->request->getPost('link_drive'),
        ];

        // 5. SIMPAN / UPDATE PELAMAR
        $finalPelamarId = null;

        if ($existingPelamar) {
            // Update data lama
            $finalPelamarId = $existingPelamar['id'];
            if ($cv) $dataPelamar['file_cv'] = $cv;
            if ($foto) $dataPelamar['foto_profil'] = $foto;
            
            $this->pelamarModel->update($finalPelamarId, $dataPelamar);
        } else {
            // Insert baru (Wajib upload)
            if (!$cv || !$foto) {
                return redirect()->back()->withInput()->with('error', 'CV dan Foto Profil wajib diupload untuk pelamar baru.');
            }
            $dataPelamar['file_cv']        = $cv;
            $dataPelamar['foto_profil']    = $foto;
            $dataPelamar['is_blacklisted'] = 0;

            $this->pelamarModel->insert($dataPelamar);
            $finalPelamarId = $this->pelamarModel->getInsertID();
        }

        // 6. SIMPAN TRANSAKSI LAMARAN
        $jawabanForm = $this->request->getPost('jawaban') ?? [];

        $this->dataModel->save([
            'pelamar_id'     => $finalPelamarId,
            'id_lowongan'    => $idLowongan,
            'tanggal_daftar' => date('Y-m-d'),
            'form_data'      => json_encode($jawabanForm),
            'status'         => 'seleksi',
            'is_history'     => 0
        ]);

        return redirect()->to('/')->with('success', 'Lamaran berhasil dikirim! Pantau WhatsApp/Email Anda untuk info selanjutnya.');
    }
}
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DataModel;
use App\Models\LowonganModel;
use App\Models\PelamarModel;
use App\Models\AlternativesModel;

class DataController extends BaseController
{
    protected $dataModel;
    protected $lowonganModel;
    protected $pelamarModel;
    protected $alternativesModel;

    public function __construct()
    {
        $this->dataModel     = new DataModel();
        $this->lowonganModel = new LowonganModel();
        $this->pelamarModel  = new PelamarModel();
        $this->alternativesModel  = new AlternativesModel();
    }

    public function index()
    {
        // 1. PELAMAR BARU ... (Kode lama)
        $belumDinilai = $this->dataModel
            ->select('data.*, pelamar.nama_lengkap, lowongan.judul_lowongan')
            ->join('pelamar', 'pelamar.id = data.pelamar_id')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->where('data.spk_score', 0)
            ->where('data.is_history', 0)
            ->orderBy('data.id', 'DESC')
            ->findAll();

        // 2. HASIL PENILAIAN ... (Kode lama)
        $sudahDinilai = $this->dataModel
            ->select('data.*, pelamar.nama_lengkap, lowongan.judul_lowongan')
            ->join('pelamar', 'pelamar.id = data.pelamar_id')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->where('data.spk_score >', 0)
            ->where('data.is_history', 0)
            ->orderBy('data.spk_score', 'DESC')
            ->findAll();

        // 3. HISTORY ... (Kode lama)
        $history = $this->dataModel
            ->select('data.*, pelamar.nama_lengkap, lowongan.judul_lowongan')
            ->join('pelamar', 'pelamar.id = data.pelamar_id')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->where('data.is_history', 1)
            ->orderBy('data.id', 'DESC')
            ->findAll();

        // 4. BLACKLIST (BARU)
        // Ambil langsung dari Master Pelamar
        $blacklist = $this->pelamarModel
            ->where('is_blacklisted', 1)
            ->orderBy('updated_at', 'DESC')
            ->findAll();

        return view('admin/data/index', [
            'title'        => 'Manajemen Data Pelamar',
            'belumDinilai' => $belumDinilai,
            'sudahDinilai' => $sudahDinilai,
            'history'      => $history,
            'blacklist'    => $blacklist // Kirim data blacklist
        ]);
    }

    // Detail Pelamar (Untuk Admin Data)
    public function detail($id)
    {
        // 1. Ambil Data Lengkap
        $pelamar = $this->dataModel
            ->select('data.*, 
                      pelamar.nama_lengkap, pelamar.no_ktp, pelamar.email, pelamar.no_hp, pelamar.foto_profil, pelamar.file_cv,
                      lowongan.judul_lowongan, 
                      pekerjaan.posisi as nama_pekerjaan, pekerjaan.standar_spk')
            ->join('pelamar', 'pelamar.id = data.pelamar_id')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id')
            ->find($id);

        if (!$pelamar) return redirect()->to('admin/data');

        // 2. Cek apakah sudah direkrut jadi karyawan?
        $isRekrut = $this->alternativesModel->where('pelamar_id', $pelamar['pelamar_id'])->first();

        // 3. Kirim dengan nama variabel 'data' (bukan 'p')
        return view('admin/data/detail', [
            'title'    => 'Detail Keputusan',
            'data'     => $pelamar,  // <-- INI YANG PENTING
            'isRekrut' => $isRekrut  // <-- INI JUGA PENTING UNTUK VIEW
        ]);
    }

    // --- HELPER: Ubah 08xxx jadi 628xxx ---
    private function formatPhoneWA($nomor)
    {
        // Hapus spasi, strip, atau karakter non-angka
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        // Jika diawali 0, ganti dengan 62
        if (substr($nomor, 0, 1) == '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }

    public function updateStatus($id, $status)
    {
        $status = urldecode($status);

        if (!in_array($status, ['memenuhi', 'tidak memenuhi'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        // Ambil data pelamar
        $lamaran = $this->dataModel
            ->select('data.*, pelamar.nama_lengkap, pelamar.no_hp, lowongan.judul_lowongan') // Ambil NO_HP
            ->join('pelamar', 'pelamar.id = data.pelamar_id')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->find($id);

        if (!$lamaran) return redirect()->back();

        // 1. Update Database
        $this->dataModel->update($id, ['status' => $status]);

        // 2. Siapkan Pesan WhatsApp
        $no_hp = $this->formatPhoneWA($lamaran['no_hp']);
        $sapaan = "Halo " . $lamaran['nama_lengkap'] . ",\n\n";
        
        if ($status == 'memenuhi') {
            $pesan = $sapaan .
                     "Selamat! Berdasarkan hasil seleksi, kualifikasi Anda *MEMENUHI* standar kami untuk posisi *" . $lamaran['judul_lowongan'] . "*.\n\n" .
                     "Kami akan segera menghubungi Anda kembali untuk menjadwalkan tahap selanjutnya (Offering/Interview).\n\n" .
                     "Terima kasih,\n*Tim HRD Cartenz Technology*";
        } else {
            $pesan = $sapaan .
                     "Terima kasih telah mengikuti proses seleksi untuk posisi *" . $lamaran['judul_lowongan'] . "*.\n\n" .
                     "Mohon maaf, saat ini kualifikasi Anda belum sesuai dengan kebutuhan kami. Data Anda telah kami simpan untuk kesempatan di masa depan.\n\n" .
                     "Terima kasih,\n*Tim HRD Cartenz Technology*";
        }

        // Encode pesan agar bisa jadi URL
        $waUrl = "https://wa.me/" . $no_hp . "?text=" . urlencode($pesan);

        // 3. Redirect ke WhatsApp
        // Status di database sudah berubah, sekarang arahkan HRD ke WA
        return redirect()->to($waUrl);
    }

    // --- METHOD BARU: REKRUT KARYAWAN (Onboard) ---
    public function onboard($id_data)
    {
        $lamaran = $this->dataModel->find($id_data);
        if (!$lamaran) return redirect()->back();

        $pelamar = $this->pelamarModel->find($lamaran['pelamar_id']);

        // Cek duplikasi
        if ($this->alternativesModel->where('pelamar_id', $lamaran['pelamar_id'])->first()) {
             return redirect()->back()->with('error', 'Pelamar ini sudah terdaftar sebagai karyawan.');
        }

        // Masukkan ke tabel Karyawan (Alternatives)
        $this->alternativesModel->save([
            'pelamar_id' => $lamaran['pelamar_id'],
            'kode'       => 'K-' . date('ym') . $lamaran['pelamar_id'], 
            'nama'       => $pelamar['nama_lengkap'],
            'jabatan'    => 'Staff Baru',
            // sesuaikan field lain dengan tabel alternatives Anda
        ]);

        return redirect()->back()->with('success', 'Selamat! Pelamar berhasil direkrut.');
    }

    // Fungsi Arsipkan (Selesai)
    public function arsipkan($id)
    {
        // Pindahkan ke history
        $this->dataModel->update($id, ['is_history' => 1]);
        return redirect()->to('admin/data')->with('success', 'Data berhasil diarsipkan.');
    }

    public function pulihkanBlacklist($idPelamar)
    {
        // Ambil data pelamar dulu untuk cek tipe
        $pelamar = $this->pelamarModel->find($idPelamar);

        // CEK 1: JIKA PERMANEN, TOLAK AKSES
        if ($pelamar['blacklist_type'] == 'permanent') {
            return redirect()->back()->with('error', 'GAGAL: Pelamar ini di-blacklist PERMANEN dan tidak dapat dipulihkan.');
        }

        // JIKA TEMPORARY, LANJUTKAN PROSES PULIH
        
        // 1. Update Master Pelamar
        $this->pelamarModel->update($idPelamar, [
            'is_blacklisted'   => 0,
            'blacklist_type'   => null, // Reset tipe
            'alasan_blacklist' => null
        ]);

        // 2. Update Data Lamaran (Kembalikan ke aktif)
        $this->dataModel
            ->where('pelamar_id', $idPelamar)
            ->where('status', 'blacklist')
            ->set([
                'status'     => 'proses',
                'is_history' => 0
            ])
            ->update();

        return redirect()->to('admin/data')->with('success', 'Status Blacklist Sementara telah dicabut.');
    }

    // --- 3. KIRIM OFFERING LETTER (Tahap Rekrutmen) ---
    public function offering($id)
    {
        $lamaran = $this->dataModel
            ->select('data.*, pelamar.nama_lengkap, pelamar.no_hp, lowongan.judul_lowongan')
            ->join('pelamar', 'pelamar.id = data.pelamar_id')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->find($id);

        // 1. Update Status
        $this->dataModel->update($id, ['status' => 'offering']);

        // 2. Siapkan Pesan WhatsApp
        $no_hp = $this->formatPhoneWA($lamaran['no_hp']);
        
        // Link Konfirmasi (Pastikan route ini bisa diakses publik/tanpa login nanti)
        $linkTerima = base_url('konfirmasi/terima/' . $id);
        $linkTolak  = base_url('konfirmasi/tolak/' . $id);

        $pesan = "Halo " . $lamaran['nama_lengkap'] . ",\n\n" .
                 "Kami dengan senang hati menawarkan Anda posisi *" . $lamaran['judul_lowongan'] . "* di Cartenz Technology.\n\n" .
                 "Silakan konfirmasi penerimaan tawaran ini melalui link berikut:\n" .
                 "✅ *TERIMA:* " . $linkTerima . "\n" .
                 "❌ *TOLAK:* " . $linkTolak . "\n\n" .
                 "Atau balas chat ini jika ada pertanyaan.\n\n" .
                 "Salam Sukses,\n*Tim HRD*";

        $waUrl = "https://wa.me/" . $no_hp . "?text=" . urlencode($pesan);

        // 3. Redirect ke WhatsApp
        return redirect()->to($waUrl);
    }

    // --- 4. KONFIRMASI AKHIR (Terima / Tolak Tawaran) ---
    public function confirmOffer($id, $jawaban)
    {
        // Join ke Lowongan untuk ambil Judul Posisi
        $lamaran = $this->dataModel
            ->select('data.*, lowongan.pekerjaan_id, lowongan.judul_lowongan') 
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->find($id);
        
        // Data untuk dikirim ke View (Default Error)
        $dataView = [
            'status' => 'error',
            'nama'   => 'Kandidat',
            'posisi' => '-'
        ];

        if ($lamaran) {
            $pelamar = $this->pelamarModel->find($lamaran['pelamar_id']);
            
            // Siapkan data untuk view
            $dataView['nama']   = $pelamar['nama_lengkap'];
            $dataView['posisi'] = $lamaran['judul_lowongan'];

            if ($jawaban == 'terima') {
                $dataView['status'] = 'terima';
                
                // 1. Cek Duplikasi & Simpan Karyawan
                if (!$this->alternativesModel->where('pelamar_id', $lamaran['pelamar_id'])->first()) {
                    $this->alternativesModel->save([
                        'pelamar_id'   => $lamaran['pelamar_id'],
                        'pekerjaan_id' => $lamaran['pekerjaan_id'],
                        'kode'         => 'K-' . date('ym') . $lamaran['pelamar_id'],
                        'nama'         => $pelamar['nama_lengkap'],
                        'jabatan'      => 'Staff Baru', 
                    ]);
                }
                
                // 2. Update Status HIRED
                $this->dataModel->update($id, ['status' => 'hired', 'is_history' => 1]);

            } else {
                $dataView['status'] = 'tolak';

                // Pelamar Menolak
                $this->dataModel->update($id, ['status' => 'rejected_offer', 'is_history' => 1]);
            }
        }

        // Panggil View yang Bagus tadi
        return view('public/response_offering', $dataView);
    }
}
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

        // --- QUERY 1: BELUM DINILAI ---
        // Kita definisikan ulang query-nya agar 'select' dan 'join' pasti tereksekusi
        $this->dataModel->select('data.*, lowongan.judul_lowongan')
                        ->join('lowongan', 'lowongan.id = data.id_lowongan')
                        ->orderBy('data.id', 'DESC')
                        ->where('data.spk_score', null)
                        ->where('data.is_history', 0);
        
        if ($keyword) {
            $this->dataModel->groupStart()
                            ->like('data.nama', $keyword)
                            ->orLike('lowongan.judul_lowongan', $keyword)
                            ->groupEnd();
        }
        $belumDinilai = $this->dataModel->findAll();


        // --- QUERY 2: SUDAH DINILAI ---
        // Panggil lagi select & join untuk query kedua
        $this->dataModel->select('data.*, lowongan.judul_lowongan')
                        ->join('lowongan', 'lowongan.id = data.id_lowongan')
                        ->orderBy('data.id', 'DESC')
                        ->where('data.spk_score !=', null)
                        ->where('data.is_history', 0);

        if ($keyword) {
            $this->dataModel->groupStart()
                            ->like('data.nama', $keyword)
                            ->orLike('lowongan.judul_lowongan', $keyword)
                            ->groupEnd();
        }
        $sudahDinilai = $this->dataModel->findAll();


        // --- QUERY 3: HISTORY ---
        // Panggil lagi select & join untuk query ketiga
        $this->dataModel->select('data.*, lowongan.judul_lowongan')
                        ->join('lowongan', 'lowongan.id = data.id_lowongan')
                        ->orderBy('data.id', 'DESC')
                        ->where('data.is_history', 1);

        if ($keyword) {
            $this->dataModel->groupStart()
                            ->like('data.nama', $keyword)
                            ->orLike('lowongan.judul_lowongan', $keyword)
                            ->groupEnd();
        }
        $history = $this->dataModel->findAll();
            
        return view('admin/data/index', [
            'belumDinilai' => $belumDinilai,
            'sudahDinilai' => $sudahDinilai,
            'history'      => $history,
            'keyword'      => $keyword
        ]);
    }

    public function detail($id)
    {
        $pelamar = $this->dataModel
            ->select('data.*, lowongan.judul_lowongan, lowongan.pekerjaan_id, pekerjaan.divisi, pekerjaan.posisi, pekerjaan.standar_spk')
            ->join('lowongan', 'lowongan.id = data.id_lowongan')
            ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id') 
            ->find($id);

        if (!$pelamar) return redirect()->to('admin/data');

        // PERBAIKAN LOGIKA CEK REKRUT
        // Cek apakah pelamar ini sudah ada di tabel alternatives
        // Kita gunakan kombinasi Nama + Pekerjaan ID agar lebih spesifik
        $isRekrut = $this->alternativesModel
            ->where('nama', $pelamar['nama'])
            ->where('pekerjaan_id', $pelamar['pekerjaan_id']) 
            ->first();

        return view('admin/data/detail', [
            'title'    => 'Detail Pelamar',
            'data'     => $pelamar,
            'isRekrut' => $isRekrut
        ]);
    }

    // Update Status (Memenuhi/Tidak) tanpa pindah ke history
    public function updateStatus($id, $status)
    {
        $allowed = ['memenuhi', 'tidak memenuhi'];
        $status = urldecode($status);

        if (!in_array($status, $allowed)) return redirect()->back()->with('error', 'Status invalid.');

        $this->dataModel->update($id, ['status' => $status]);
        return redirect()->back()->with('success', 'Status diperbarui. Silakan "Selesai & Arsipkan" jika sudah final.');
    }

    // FUNGSI BARU: Pindah ke History
    public function arsipkan($id)
    {
        $this->dataModel->update($id, ['is_history' => 1]);
        return redirect()->to('admin/data')->with('success', 'Data pelamar berhasil diarsipkan ke History.');
    }

    public function onboard($id)
    {
        $pelamar = $this->dataModel->find($id);
        
        // Validasi
        if (!$pelamar || $pelamar['status'] != 'memenuhi') {
            return redirect()->back()->with('error', 'Status pelamar belum memenuhi syarat.');
        }

        $lowongan = $this->lowonganModel->find($pelamar['id_lowongan']);
        
        // Cek lagi agar tidak duplikat
        $cek = $this->alternativesModel
            ->where('nama', $pelamar['nama'])
            ->where('pekerjaan_id', $lowongan['pekerjaan_id'])
            ->first();
        
        if (!$cek) {
            // Simpan ke Alternatives
            $this->alternativesModel->save([
                'pekerjaan_id' => $lowongan['pekerjaan_id'], 
                'kode'         => 'K' . sprintf("%03d", $pelamar['id']),
                'nama'         => $pelamar['nama'],
                'status'       => 'Aktif'
            ]);
            
            // Opsional: Langsung arsipkan juga setelah direkrut
            $this->dataModel->update($id, ['is_history' => 1]); 
            
            return redirect()->back()->with('success', 'Pelamar berhasil direkrut menjadi karyawan!');
        }
        
        return redirect()->back()->with('error', 'Pelamar ini sudah terdaftar sebagai karyawan.');
    }
}
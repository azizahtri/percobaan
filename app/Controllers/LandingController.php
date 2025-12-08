<?php
namespace App\Controllers;

use App\Models\LowonganModel;
use App\Models\DataModel;

class LandingController extends BaseController
{
    protected $lowonganModel;
    protected $dataModel;

    public function __construct()
    {
        $this->lowonganModel = new LowonganModel();
        $this->dataModel = new DataModel();
        helper('text');
    }

    public function index()
    {
        $data = [
            'title' => 'Beranda | SPK Rekrutmen',
            'lowongan' => $this->lowonganModel->orderBy('tanggal_posting', 'DESC')->findAll(),
        ];

        return view('landing/index', $data);
    }

    public function detail($id)
    {
        $lowonganModel = new \App\Models\LowonganModel();
        
        // Join ke tabel formulir untuk ambil config pertanyaan
        $lowongan = $lowonganModel
            ->select('lowongan.*, formulir.config as form_config') // Ambil config dari tabel formulir
            ->join('formulir', 'formulir.id = lowongan.formulir_id', 'left')
            ->find($id);

        if (!$lowongan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Lowongan tidak ditemukan');
        }

        $data = [
            'title'    => 'Detail Lowongan',
            'lowongan' => $lowongan, // Sekarang $lowongan punya index 'form_config'
        ];

        return view('landing/detail', $data);
    }

    // --- SUBMIT LAMARAN (VERSI LINK G-DRIVE) ---
    public function submit()
    {
        // 1. Validasi Input
        // Pastikan kurung kurawal validasi tertutup dengan benar
        if (!$this->validate([
            'nama'        => 'required',
            'email'       => 'required|valid_email',
            'no_hp'       => 'required',
            'link'        => 'required', // Wajib nama field 'link'
            'id_lowongan' => 'required'
        ])) {
            // Jika validasi gagal, kembalikan ke halaman sebelumnya
            return redirect()->back()->withInput()->with('error', 'Gagal mengirim! Mohon lengkapi data.');
        }

        // 2. Simpan ke Database
        $answers = $this->request->getPost('custom_answers');
        $jsonAnswers = !empty($answers) ? json_encode($answers) : null;

        // 2. Simpan
        $this->dataModel->save([
            'id_lowongan' => $this->request->getPost('id_lowongan'),
            'nama'        => $this->request->getPost('nama'),
            'email'       => $this->request->getPost('email'),
            'no_hp'       => $this->request->getPost('no_hp'),
            'link'        => $this->request->getPost('link'),
            'pesan'       => $this->request->getPost('pesan'),
            
            'form_data'   => $jsonAnswers, // <-- Simpan Jawaban di sini
            'status'      => 'proses'
        ]);

        return redirect()->to(base_url('/#lowongan'))->with('success', 'Lamaran terkirim!');
    }
}
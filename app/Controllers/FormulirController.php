<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FormulirModel;

class FormulirController extends BaseController
{
    protected $formulirModel;

    public function __construct()
    {
        $this->formulirModel = new FormulirModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Kelola Template Formulir',
            'formulir' => $this->formulirModel->findAll()
        ];
        return view('admin/formulir/index', $data);
    }

    public function create()
    {
        return view('admin/formulir/create', ['title' => 'Buat Template Baru']);
    }

    public function store()
    {
        // 1. Tangkap Data Pertanyaan Kustom
        $q_text    = $this->request->getPost('q_text');
        $q_type    = $this->request->getPost('q_type');
        $q_options = $this->request->getPost('q_options');

        $finalConfig = [];

        if (!empty($q_text)) {
            foreach ($q_text as $key => $val) {
                if(!empty($val)) {
                    $finalConfig[] = [
                        'label'   => $val,
                        'type'    => $q_type[$key],
                        'options' => $q_options[$key] ?? '' 
                    ];
                }
            }
        }
        
        $jsonConfig = !empty($finalConfig) ? json_encode($finalConfig) : '[]';

        $this->formulirModel->save([
            'nama_template'    => $this->request->getPost('nama_template'),
            'link_google_form' => $this->request->getPost('link_google_form'), // Pastikan ini ada
            'config'           => $jsonConfig
        ]);

        return redirect()->to('admin/formulir')->with('success', 'Template formulir berhasil dibuat!');
    }

    public function edit($id)
    {
        $formulir = $this->formulirModel->find($id);
        
        if (!$formulir) {
            return redirect()->to('admin/formulir')->with('error', 'Template tidak ditemukan.');
        }

        return view('admin/formulir/edit', [
            'title'    => 'Edit Template Formulir',
            'formulir' => $formulir
        ]);
    }

    public function update($id)
    {
        // 1. Tangkap Data Pertanyaan (Logic sama dengan Store)
        $q_text    = $this->request->getPost('q_text');
        $q_type    = $this->request->getPost('q_type');
        $q_options = $this->request->getPost('q_options');

        $finalConfig = [];

        if (!empty($q_text)) {
            foreach ($q_text as $key => $val) {
                if(!empty($val)) {
                    $finalConfig[] = [
                        'label'   => $val,
                        'type'    => $q_type[$key],
                        'options' => $q_options[$key] ?? '' 
                    ];
                }
            }
        }
        
        $jsonConfig = !empty($finalConfig) ? json_encode($finalConfig) : '[]';

        $this->formulirModel->update($id, [
            'nama_template'    => $this->request->getPost('nama_template'),
            'link_google_form' => $this->request->getPost('link_google_form'), // Pastikan ini ada
            'config'           => $jsonConfig
        ]);

        return redirect()->to('admin/formulir')->with('success', 'Template formulir berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->formulirModel->delete($id);
        return redirect()->to('admin/formulir')->with('success', 'Template dihapus.');
    }
}
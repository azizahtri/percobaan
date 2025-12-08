<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SubcriteriaModel;
use App\Models\CriteriaModel;
use App\Models\PekerjaanModel;

class SubcriteriaController extends BaseController
{
    protected $subModel;
    protected $criteriaModel;
    protected $pekerjaanModel;

    public function __construct()
    {
        $this->subModel = new SubcriteriaModel();
        $this->criteriaModel = new CriteriaModel();
        $this->pekerjaanModel = new PekerjaanModel();
    }

    public function index($criteria_id)
    {
        $criteria = $this->criteriaModel->find($criteria_id);

        // Ambil field pekerjaan
        $pekerjaan = $this->pekerjaanModel->find($criteria['pekerjaan_id']);

        // Ambil subkriteria untuk kriteria ini
        $sub = $this->subModel
            ->where('criteria_id', $criteria_id)
            ->findAll();

        return view('admin/subcriteria/index', [
            'title'      => 'Subkriteria',
            'criteria'   => $criteria,
            'pekerjaan'  => $pekerjaan,
            'subcriteria'=> $sub
        ]);
    }

    public function create($criteria_id)
    {
        $criteria = $this->criteriaModel->find($criteria_id);

        return view('admin/subcriteria/create', [
            'criteria' => $criteria
        ]);
    }

    public function store()
    {
        $criteria_id = $this->request->getPost('criteria_id');

        $criteria = $this->criteriaModel->find($criteria_id);

        $this->subModel->insert([
            'criteria_id'  => $criteria_id,
            'pekerjaan_id'    => $criteria['pekerjaan_id'],
            'keterangan' => $this->request->getPost('keterangan'),
            'bobot_sub'       => $this->request->getPost('bobot_sub'),
            'tipe'        => $this->request->getPost('tipe')
        ]);

        return redirect()->to(base_url("admin/subcriteria/$criteria_id"))
            ->with('success', 'Subkriteria berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $sub = $this->subModel->find($id);

        return view('admin/subcriteria/edit', [
            'sub' => $sub
        ]);
    }

    public function update($id)
    {
        $criteria_id = $this->request->getPost('criteria_id');

        $this->subModel->update($id, [
            'keterangan' => $this->request->getPost('keterangan'),
            'bobot_sub'       => $this->request->getPost('bobot_sub'),
            'tipe'        => $this->request->getPost('tipe')
        ]);

        return redirect()->to(base_url("admin/subcriteria/$criteria_id"))
            ->with('success', 'Subkriteria berhasil diperbarui!');
    }

    public function delete($id)
    {
        $sub = $this->subModel->find($id);
        $criteria_id = $sub['criteria_id'];

        $this->subModel->delete($id);

        return redirect()->to(base_url("admin/subcriteria/$criteria_id"))
            ->with('success', 'Subkriteria berhasil dihapus!');
    }
}

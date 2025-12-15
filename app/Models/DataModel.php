<?php

namespace App\Models;

use CodeIgniter\Model;

class DataModel extends Model
{
    protected $table            = 'data';      // Nama tabel transaksi lamaran
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;        // Agar created_at otomatis terisi
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields    = [
        'pelamar_id', 
        'id_lowongan', 
        'tanggal_daftar',
        'link', 
        'form_data',    
        'spk_score',
        'spk_log', 
        'status', 
        'is_history'
    ];

    public function getLengkap($id = null)
    {
        $builder = $this->select('data.*, pelamar.nama_lengkap, pelamar.no_ktp, pelamar.email, pelamar.no_hp, pelamar.file_cv, lowongan.judul_lowongan, pekerjaan.divisi')
                        ->join('pelamar', 'pelamar.id = data.pelamar_id')
                        ->join('lowongan', 'lowongan.id = data.id_lowongan')
                        ->join('pekerjaan', 'pekerjaan.id = lowongan.pekerjaan_id');

        if ($id) {
            return $builder->where('data.id', $id)->first();
        }
        return $builder->findAll();
    }
}
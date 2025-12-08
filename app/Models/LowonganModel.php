<?php
namespace App\Models;

use CodeIgniter\Model;

class LowonganModel extends Model
{
    protected $table = 'lowongan';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'pekerjaan_id', 
        'judul_lowongan', 
        'deskripsi', 
        'jenis', 
        'link_google_form', 
        'tanggal_posting',
        'formulir_id'
    ];

    protected $useTimestamps = false;
}
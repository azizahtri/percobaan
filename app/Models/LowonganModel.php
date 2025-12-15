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
        'status',           // BARU
        'tanggal_mulai',    // BARU
        'tanggal_selesai',  // BARU
        'link_google_form', 
        'tanggal_posting',  // Bisa tetap dipakai sebagai 'created_at'
        'formulir_id'
    ];

    protected $useTimestamps = false;
}
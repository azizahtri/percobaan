<?php
namespace App\Models;

use CodeIgniter\Model;

class DataModel extends Model
{
    protected $table = 'data';
    protected $primaryKey = 'id';
    
    // Field yang boleh diisi
    protected $allowedFields = [
        'id_lowongan', 
        'nama', 
        'email', 
        'no_hp', 
        'pesan', 
        'link',   
        'status',       
        'spk_score', 
        'is_history',
        'form_data'
    ];
    
    protected $useTimestamps = false; 
}
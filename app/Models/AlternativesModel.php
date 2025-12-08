<?php
namespace App\Models;

use CodeIgniter\Model;

class AlternativesModel extends Model
{
    protected $table = 'alternatives';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pekerjaan_id', 'kode', 'nama', 'status', 'created_at'];
    protected $useTimestamps = false;
}

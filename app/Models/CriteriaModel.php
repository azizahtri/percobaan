<?php
namespace App\Models;

use CodeIgniter\Model;

class CriteriaModel extends Model
{
    protected $table = 'criteria';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pekerjaan_id', 'kode', 'nama', 'bobot', 'tipe'];
}

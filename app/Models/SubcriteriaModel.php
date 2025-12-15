<?php

namespace App\Models;

use CodeIgniter\Model;

class SubcriteriaModel extends Model
{
    protected $table = 'subcriteria';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'criteria_id',
        'pekerjaan_id',
        'keterangan',
        'bobot_sub',
        'tipe'
    ];
}

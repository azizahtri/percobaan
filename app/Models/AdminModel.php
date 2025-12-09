<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admin'; // FOKUS KE TABEL ADMIN
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['name', 'username', 'password', 'role'];

    // Fitur Tanggal Otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Callback: Hash Password Otomatis Saat Insert/Update
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            // Ubah password biasa menjadi Hash (Acak)
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }
        return $data;
    }
}
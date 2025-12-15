<?php

namespace App\Models;

use CodeIgniter\Model;

class PelamarModel extends Model
{
    protected $table            = 'pelamar';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    
    protected $allowedFields    = [
        'no_ktp', 
        'nama_lengkap', 
        'email', 
        'no_hp',
        'tempat_lahir', 
        'tanggal_lahir', 
        'alamat',
        'jenis_kelamin', 
        'status_pernikahan',
        'foto_profil', 
        'file_cv',
        'link_drive',
        'is_blacklisted', 
        'alasan_blacklist'
    ];

    // Fungsi Cek Blacklist
    public function isBlacklisted($ktp)
    {
        $pelamar = $this->where('no_ktp', $ktp)->first();
        if ($pelamar && $pelamar['is_blacklisted'] == 1) {
            return $pelamar['alasan_blacklist']; // Kembalikan alasannya
        }
        return false; // Aman
    }
}
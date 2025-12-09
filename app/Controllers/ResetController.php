<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class ResetController extends BaseController
{
    public function index()
    {
        $model = new AdminModel();
        
        // Data akun superadmin
        $username = 'superadmin';
        $newPassword = 'password123'; // Password baru yang PASTI BENAR

        // Cari user
        $user = $model->where('username', $username)->first();

        if ($user) {
            // Update password
            // KITA HASH MANUAL DI SINI AGAR YAKIN
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Bypass model callback, update langsung via query builder
            $db = \Config\Database::connect();
            $db->table('admin')->where('username', $username)->update(['password' => $hashed]);

            echo "<h1>BERHASIL!</h1>";
            echo "Password untuk <b>$username</b> telah direset menjadi: <b>$newPassword</b><br>";
            echo "Hash baru: " . $hashed . "<br><br>";
            echo "<a href='".base_url('login')."'>LOGIN SEKARANG</a>";
        } else {
            echo "<h1>GAGAL :(</h1>";
            echo "User <b>$username</b> tidak ditemukan di database.<br>";
            echo "Silakan buat user baru lewat phpMyAdmin atau Seeder.";
        }
    }
}
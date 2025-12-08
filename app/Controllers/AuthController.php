<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    // Menampilkan Halaman Login
    public function login()
    {
        return view('auth/login');
    }

    // Proses Login
    public function doLogin()
    {
        $session = session();
        $model = new AdminModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // Cari user di database
        $data = $model->where('username', $username)->first();

        if ($data) {
            // Cek Password (sesuaikan dengan database Anda, ini contoh plain text)
            if ($data['password'] === $password) {
                
                // Simpan data ke session
                $sessData = [
                    'id'         => $data['id'],
                    'name'       => $data['name'],
                    'username'   => $data['username'],
                    'isLoggedIn' => true
                ];
                
                $session->set($sessData);
                
                // Login Berhasil -> Masuk Dashboard
                return redirect()->to('admin/dashboard');
            } else {
                return redirect()->to('login')->with('error', 'Password salah.');
            }
        } else {
            return redirect()->to('login')->with('error', 'Username tidak ditemukan.');
        }
    }

    // Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}
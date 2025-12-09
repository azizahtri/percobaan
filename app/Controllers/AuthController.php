<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel; 

class AuthController extends BaseController
{
    public function login()
    {
        // Mencegah user yang sudah login masuk ke halaman login lagi
        if (session()->get('logged_in')) {
            return redirect()->to('admin/dashboard');
        }
        return view('auth/login');
    }

    public function doLogin()
    {
        $session = session();
        $model   = new AdminModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $data = $model->where('username', $username)->first();

        if ($data) {
            if (password_verify($password, $data['password'])) {
                $sessData = [
                    'id'        => $data['id'],
                    'name'      => $data['name'],
                    'role'      => $data['role'],
                    'logged_in' => true
                ];
                $session->set($sessData);
                return redirect()->to('admin/dashboard');
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
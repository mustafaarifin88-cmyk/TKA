<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\SiswaModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('is_login') && session()->get('role') == 'siswa') {
            return redirect()->to('siswa/dashboard');
        }
        return view('auth/login_siswa');
    }

    public function process()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $siswaModel = new SiswaModel();
        $siswa = $siswaModel->where('username', $username)->first();

        if ($siswa) {
            if (password_verify($password, $siswa['password'])) {
                $data = [
                    'id' => $siswa['id'],
                    'username' => $siswa['username'],
                    'nama_lengkap' => $siswa['nama_lengkap'],
                    'role' => 'siswa',
                    'sekolah_id' => $siswa['sekolah_id'],
                    'foto' => $siswa['foto'] ?? 'default.jpg',
                    'is_login' => true
                ];
                session()->set($data);
                return redirect()->to('siswa/dashboard');
            }
        }

        return redirect()->back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login/siswa');
    }
}
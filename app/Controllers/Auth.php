<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\GuruModel;
use App\Models\SiswaModel;

class Auth extends BaseController
{
    // ... method login() dan prosesLogin() yang lama biarkan saja ...
    public function login()
    {
        if (session()->get('is_login')) {
            return redirect()->to(session()->get('role') . '/dashboard');
        }
        return view('auth/login');
    }

    public function prosesLogin()
    {
        // ... (Kode lama untuk Admin/Guru tetap sama) ...
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $adminModel = new AdminModel();
        $guruModel = new GuruModel();
        
        // Cek Admin
        $user = $adminModel->where('username', $username)->first();
        $role = 'admin';

        // Cek Guru
        if (!$user) {
            $user = $guruModel->where('username', $username)->first();
            $role = 'guru';
        }

        if ($user && password_verify($password, $user['password'])) {
            $sessData = [
                'id' => $user['id'],
                'username' => $user['username'],
                'nama_lengkap' => $user['nama_lengkap'],
                'role' => $role,
                'foto' => $user['foto'] ?? 'default.jpg',
                'nip' => $user['nip'] ?? null,
                'is_login' => true
            ];
            session()->set($sessData);
            return redirect()->to($role . '/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau Password salah.');
    }

    // --- FITUR BARU: LOGIN SISWA (ANBK STYLE) ---
    public function loginSiswa()
    {
        if (session()->get('is_login')) {
            return redirect()->to(session()->get('role') . '/dashboard');
        }
        return view('auth/login_siswa');
    }

    public function prosesLoginSiswa()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $siswaModel = new SiswaModel();
        
        // Khusus Siswa, cek hanya di tabel siswa
        $user = $siswaModel->where('username', $username)->first();
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $sessData = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'role' => 'siswa',
                    'foto' => $user['foto'] ?? 'default.jpg',
                    'nisn' => $user['nisn'],
                    'is_login' => true
                ];
                session()->set($sessData);
                return redirect()->to('siswa/dashboard');
            }
        }

        // Redirect kembali ke login siswa jika gagal
        return redirect()->to('login/siswa')->with('error', 'Username atau Password salah.');
    }
    // ---------------------------------------------

    public function logout()
    {
        session()->destroy();
        // Redirect ke halaman sesuai role sebelumnya atau default
        return redirect()->to('login');
    }
}
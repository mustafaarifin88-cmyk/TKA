<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\SiswaModel;

class Profile extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Profile Saya',
            'validation' => \Config\Services::validation()
        ];
        return view('siswa/profile', $data);
    }

    public function update()
    {
        $siswaModel = new SiswaModel();
        $id = session()->get('id');
        $user = $siswaModel->find($id);

        $rules = [
            'nama_lengkap' => 'required',
            'foto' => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['conf_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        ];

        if ($this->request->getPost('password')) {
            $dataUpdate['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $newName = $fileFoto->getRandomName();
            $fileFoto->move('uploads/profil', $newName);

            if ($user['foto'] != 'default.jpg' && file_exists('uploads/profil/' . $user['foto'])) {
                unlink('uploads/profil/' . $user['foto']);
            }

            $dataUpdate['foto'] = $newName;
            session()->set('foto', $newName);
        }

        $siswaModel->update($id, $dataUpdate);

        session()->set('nama_lengkap', $dataUpdate['nama_lengkap']);

        return redirect()->to('siswa/profile')->with('success', 'Profile berhasil diperbarui.');
    }
}
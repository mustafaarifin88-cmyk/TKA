<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Instansi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $instansi = $this->db->table('profil_instansi')->where('id', 1)->get()->getRowArray();

        $data = [
            'title' => 'Profil Instansi',
            'instansi' => $instansi,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/instansi/index', $data);
    }

    public function update()
    {
        if (!$this->validate([
            'nama_instansi' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kode_pos' => 'required',
            'logo' => 'max_size[logo,2048]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $instansi = $this->db->table('profil_instansi')->where('id', 1)->get()->getRowArray();
        
        $data = [
            'nama_instansi' => $this->request->getPost('nama_instansi'),
            'alamat' => $this->request->getPost('alamat'),
            'kota' => $this->request->getPost('kota'),
            'kode_pos' => $this->request->getPost('kode_pos'),
        ];

        $fileLogo = $this->request->getFile('logo');
        if ($fileLogo->isValid() && !$fileLogo->hasMoved()) {
            $newName = $fileLogo->getRandomName();
            $fileLogo->move('uploads/sekolah', $newName);

            if ($instansi && isset($instansi['logo']) && $instansi['logo'] != 'default_logo.png' && file_exists('uploads/sekolah/' . $instansi['logo'])) {
                unlink('uploads/sekolah/' . $instansi['logo']);
            }

            $data['logo'] = $newName;
        }

        if ($instansi) {
            $this->db->table('profil_instansi')->where('id', 1)->update($data);
        } else {
            if (!isset($data['logo'])) {
                $data['logo'] = 'default_logo.png';
            }
            $this->db->table('profil_instansi')->insert($data);
        }

        return redirect()->to('admin/instansi')->with('success', 'Profil instansi berhasil diperbarui.');
    }
}
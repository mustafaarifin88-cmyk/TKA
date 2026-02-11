<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Sekolah extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Sekolah',
            'sekolah' => $this->db->table('sekolah')->orderBy('nama_sekolah', 'ASC')->get()->getResultArray()
        ];
        return view('admin/sekolah/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Sekolah',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/sekolah/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nama_sekolah' => 'required',
            'npsn' => 'required|is_unique[sekolah.npsn]',
            'kecamatan' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->db->table('sekolah')->insert([
            'kecamatan' => $this->request->getPost('kecamatan'),
            'npsn' => $this->request->getPost('npsn'),
            'nama_sekolah' => $this->request->getPost('nama_sekolah'),
        ]);

        return redirect()->to('admin/sekolah')->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sekolah = $this->db->table('sekolah')->where('id', $id)->get()->getRowArray();
        
        if (!$sekolah) {
            return redirect()->to('admin/sekolah')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Sekolah',
            'sekolah' => $sekolah,
            'validation' => \Config\Services::validation()
        ];
        return view('admin/sekolah/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_sekolah' => 'required',
            'npsn' => "required|is_unique[sekolah.npsn,id,$id]",
            'kecamatan' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->db->table('sekolah')->where('id', $id)->update([
            'kecamatan' => $this->request->getPost('kecamatan'),
            'npsn' => $this->request->getPost('npsn'),
            'nama_sekolah' => $this->request->getPost('nama_sekolah'),
        ]);

        return redirect()->to('admin/sekolah')->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->db->table('sekolah')->where('id', $id)->delete();
        return redirect()->to('admin/sekolah')->with('success', 'Data sekolah berhasil dihapus.');
    }
}
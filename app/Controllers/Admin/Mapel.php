<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MapelModel;

class Mapel extends BaseController
{
    protected $mapelModel;

    public function __construct()
    {
        $this->mapelModel = new MapelModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Mata Pelajaran',
            'mapel' => $this->mapelModel->findAll()
        ];
        return view('admin/mapel/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Mata Pelajaran',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/mapel/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nama_mapel' => 'required|is_unique[mapel.nama_mapel]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->mapelModel->save([
            'nama_mapel' => $this->request->getPost('nama_mapel')
        ]);

        return redirect()->to('admin/mapel')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Mata Pelajaran',
            'mapel' => $this->mapelModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/mapel/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_mapel' => "required|is_unique[mapel.nama_mapel,id,$id]"
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->mapelModel->update($id, [
            'nama_mapel' => $this->request->getPost('nama_mapel')
        ]);

        return redirect()->to('admin/mapel')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->mapelModel->delete($id);
        return redirect()->to('admin/mapel')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
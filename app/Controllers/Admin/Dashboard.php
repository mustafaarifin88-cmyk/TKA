<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\MapelModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $guruModel = new GuruModel();
        $siswaModel = new SiswaModel();
        $kelasModel = new KelasModel();
        $mapelModel = new MapelModel();

        $data = [
            'title' => 'Dashboard Admin',
            'total_guru' => $guruModel->countAll(),
            'total_siswa' => $siswaModel->countAll(),
            'total_kelas' => $kelasModel->countAll(),
            'total_mapel' => $mapelModel->countAll(),
        ];

        return view('admin/dashboard', $data);
    }
}
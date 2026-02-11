<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data = [
            'title' => 'Dashboard Admin',
            'total_sekolah' => $db->table('sekolah')->countAll(),
            'total_pembuat_soal' => $db->table('guru')->countAll(),
            'total_siswa' => $db->table('siswa')->countAll(),
            'total_mapel' => $db->table('mapel')->countAll(),
        ];

        return view('admin/dashboard', $data);
    }
}
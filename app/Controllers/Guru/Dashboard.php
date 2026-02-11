<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $guruId = session()->get('id');

        $totalKelas = $this->db->table('guru_kelas')->where('guru_id', $guruId)->countAllResults();
        $totalMapel = $this->db->table('guru_mapel')->where('guru_id', $guruId)->countAllResults();
        $totalSoal = $this->db->table('soal')->where('guru_id', $guruId)->countAllResults();
        $totalUjian = $this->db->table('jadwal_ujian')->where('guru_id', $guruId)->countAllResults();

        $data = [
            'title' => 'Dashboard Guru',
            'total_kelas' => $totalKelas,
            'total_mapel' => $totalMapel,
            'total_soal' => $totalSoal,
            'total_ujian' => $totalUjian
        ];

        return view('guru/dashboard', $data);
    }
}
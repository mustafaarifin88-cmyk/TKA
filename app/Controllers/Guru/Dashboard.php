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

        $totalMapel = $this->db->table('guru_mapel')->where('guru_id', $guruId)->countAllResults();
        $totalSoal = $this->db->table('soal')->where('guru_id', $guruId)->countAllResults();
        
        $sekolahSaya = $this->db->table('guru')
            ->select('sekolah.nama_sekolah')
            ->join('sekolah', 'sekolah.id = guru.sekolah_id', 'left')
            ->where('guru.id', $guruId)
            ->get()->getRowArray();

        $data = [
            'title' => 'Dashboard Pembuat Soal',
            'total_mapel' => $totalMapel,
            'total_soal' => $totalSoal,
            'sekolah_saya' => $sekolahSaya
        ];

        return view('guru/dashboard', $data);
    }
}
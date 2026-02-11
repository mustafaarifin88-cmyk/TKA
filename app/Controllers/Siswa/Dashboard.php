<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $siswaId = session()->get('id');

        $siswa = $db->table('siswa')->where('id', $siswaId)->get()->getRow();
        
        $ujianAktif = 0;
        if ($siswa) {
            $ujianAktif = $db->table('jadwal_ujian')
                ->where('sekolah_id', $siswa->sekolah_id)
                ->where('tanggal_ujian >=', date('Y-m-d'))
                ->where('status', 'aktif')
                ->countAllResults();
        }

        $ujianSelesai = $db->table('status_ujian_siswa')
            ->where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->countAllResults();

        $data = [
            'title' => 'Dashboard Siswa',
            'ujian_aktif' => $ujianAktif,
            'ujian_selesai' => $ujianSelesai
        ];

        return view('siswa/dashboard', $data);
    }
}
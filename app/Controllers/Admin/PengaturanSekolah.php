<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class PengaturanSekolah extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Pengaturan Mapel Sekolah',
            // Mengambil data dari tabel sekolah (daftar unit/wilayah)
            'instansi' => $this->db->table('sekolah')->get()->getResultArray()
        ];
        // Pastikan view ada di folder admin/pengaturan_sekolah/index.php
        return view('admin/pengaturan_sekolah/index', $data);
    }

    public function manage($id)
    {
        $sekolah = $this->db->table('sekolah')->where('id', $id)->get()->getRowArray();

        if (!$sekolah) {
            return redirect()->to('admin/pengaturan_sekolah')->with('error', 'Data sekolah tidak ditemukan.');
        }

        $allMapel = $this->db->table('mapel')->get()->getResultArray();
        
        $currentMapel = $this->db->table('sekolah_mapel')->where('sekolah_id', $id)->get()->getResultArray();
        $assignedMapelIds = array_column($currentMapel, 'mapel_id');

        $data = [
            'title' => 'Atur Mapel Sekolah: ' . $sekolah['nama_sekolah'],
            'instansi' => $sekolah, // Variabel dikirim sebagai $instansi agar sesuai dengan view
            'mapel' => $allMapel,
            'assigned_mapel' => $assignedMapelIds
        ];

        return view('admin/pengaturan_sekolah/manage', $data);
    }

    public function save()
    {
        $sekolahId = $this->request->getPost('sekolah_id');
        $mapelIds = $this->request->getPost('mapel_id');

        // Hapus mapping lama
        $this->db->table('sekolah_mapel')->where('sekolah_id', $sekolahId)->delete();

        // Insert mapping baru
        if ($mapelIds && is_array($mapelIds)) {
            $dataInsert = [];
            foreach ($mapelIds as $mapelId) {
                $dataInsert[] = [
                    'sekolah_id' => $sekolahId,
                    'mapel_id' => $mapelId
                ];
            }
            $this->db->table('sekolah_mapel')->insertBatch($dataInsert);
        }

        return redirect()->to('admin/pengaturan_sekolah/manage/' . $sekolahId)->with('success', 'Mata pelajaran untuk sekolah ini berhasil diperbarui.');
    }
}
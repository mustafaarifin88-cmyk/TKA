<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class PengaturanInstansi extends BaseController
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
            'instansi' => $this->db->table('sekolah')->get()->getResultArray()
        ];
        return view('admin/pengaturan_instansi/index', $data);
    }

    public function manage($id)
    {
        $instansi = $this->db->table('sekolah')->where('id', $id)->get()->getRowArray();

        if (!$instansi) {
            return redirect()->to('admin/pengaturan_instansi')->with('error', 'Data sekolah tidak ditemukan.');
        }

        $allMapel = $this->db->table('mapel')->get()->getResultArray();
        
        $currentMapel = $this->db->table('sekolah_mapel')->where('sekolah_id', $id)->get()->getResultArray();
        $assignedMapelIds = array_column($currentMapel, 'mapel_id');

        $data = [
            'title' => 'Atur Mapel Sekolah: ' . $instansi['nama_sekolah'],
            'instansi' => $instansi,
            'mapel' => $allMapel,
            'assigned_mapel' => $assignedMapelIds
        ];

        return view('admin/pengaturan_instansi/manage', $data);
    }

    public function save()
    {
        $sekolahId = $this->request->getPost('sekolah_id');
        $mapelIds = $this->request->getPost('mapel_id');

        $this->db->table('sekolah_mapel')->where('sekolah_id', $sekolahId)->delete();

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

        return redirect()->to('admin/pengaturan_instansi/manage/' . $sekolahId)->with('success', 'Mata pelajaran untuk sekolah ini berhasil diperbarui.');
    }
}
<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\MapelModel;
use App\Models\KelasMapelModel;

class PengaturanKelas extends BaseController
{
    protected $kelasModel;
    protected $mapelModel;
    protected $kelasMapelModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->mapelModel = new MapelModel();
        $this->kelasMapelModel = new KelasMapelModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Pengaturan Mapel Kelas',
            'kelas' => $this->kelasModel->findAll()
        ];
        return view('admin/pengaturan_kelas/index', $data);
    }

    public function manage($id)
    {
        $kelas = $this->kelasModel->find($id);

        if (!$kelas) {
            return redirect()->to('admin/pengaturan_kelas')->with('error', 'Data kelas tidak ditemukan.');
        }

        $allMapel = $this->mapelModel->findAll();
        
        $currentMapel = $this->kelasMapelModel->where('kelas_id', $id)->findAll();
        $assignedMapelIds = array_column($currentMapel, 'mapel_id');

        $data = [
            'title' => 'Atur Mapel Kelas ' . $kelas['nama_kelas'],
            'kelas' => $kelas,
            'mapel' => $allMapel,
            'assigned_mapel' => $assignedMapelIds
        ];

        return view('admin/pengaturan_kelas/manage', $data);
    }

    public function save()
    {
        $kelasId = $this->request->getPost('kelas_id');
        $mapelIds = $this->request->getPost('mapel_id');

        $this->kelasMapelModel->where('kelas_id', $kelasId)->delete();

        if ($mapelIds && is_array($mapelIds)) {
            $dataInsert = [];
            foreach ($mapelIds as $mapelId) {
                $dataInsert[] = [
                    'kelas_id' => $kelasId,
                    'mapel_id' => $mapelId
                ];
            }
            $this->kelasMapelModel->insertBatch($dataInsert);
        }

        return redirect()->to('admin/pengaturan_kelas/manage/' . $kelasId)->with('success', 'Mata pelajaran untuk kelas ini berhasil diperbarui.');
    }
}
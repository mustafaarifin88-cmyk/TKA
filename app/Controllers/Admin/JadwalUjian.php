<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalUjianModel;
use App\Models\SekolahModel;

class JadwalUjian extends BaseController
{
    protected $jadwalModel;
    protected $sekolahModel;
    protected $db;

    public function __construct()
    {
        $this->jadwalModel = new JadwalUjianModel();
        $this->sekolahModel = new SekolahModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $jadwal = $this->jadwalModel->select('jadwal_ujian.*, sekolah.nama_sekolah, mapel.nama_mapel')
            ->join('sekolah', 'sekolah.id = jadwal_ujian.sekolah_id')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->orderBy('tanggal_ujian', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Manajemen Jadwal Ujian',
            'jadwal' => $jadwal
        ];
        return view('admin/jadwal_ujian/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Buat Jadwal Ujian',
            'sekolah' => $this->sekolahModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/jadwal_ujian/create', $data);
    }

    public function getMapelBySekolah($sekolahId)
    {
        $mapel = $this->db->table('sekolah_mapel')
            ->select('mapel.id, mapel.nama_mapel')
            ->join('mapel', 'mapel.id = sekolah_mapel.mapel_id')
            ->where('sekolah_mapel.sekolah_id', $sekolahId)
            ->get()->getResultArray();

        return $this->response->setJSON($mapel);
    }

    public function store()
    {
        if (!$this->validate([
            'sekolah_id' => 'required',
            'mapel_id' => 'required',
            'tanggal_ujian' => 'required',
            'jam_mulai' => 'required',
            'lama_ujian' => 'required|numeric'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $guru = $this->db->table('guru')
            ->join('guru_mapel', 'guru_mapel.guru_id = guru.id')
            ->where('guru.sekolah_id', $this->request->getPost('sekolah_id'))
            ->where('guru_mapel.mapel_id', $this->request->getPost('mapel_id'))
            ->get()->getRow();
        
        $guruId = $guru ? $guru->id : 0;

        $this->jadwalModel->save([
            'guru_id' => $guruId,
            'sekolah_id' => $this->request->getPost('sekolah_id'),
            'mapel_id' => $this->request->getPost('mapel_id'),
            'tanggal_ujian' => $this->request->getPost('tanggal_ujian'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'lama_ujian' => $this->request->getPost('lama_ujian'),
            'status' => 'aktif',
            'bobot_pg' => 25,
            'bobot_pg_kompleks' => 25,
            'bobot_benar_salah' => 25,
            'bobot_esai' => 25
        ]);

        return redirect()->to('admin/jadwal')->with('success', 'Jadwal ujian berhasil dijadwalkan.');
    }

    public function delete($id)
    {
        $this->jadwalModel->delete($id);
        return redirect()->to('admin/jadwal')->with('success', 'Jadwal dihapus.');
    }
}
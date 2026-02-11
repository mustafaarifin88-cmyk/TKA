<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\JadwalUjianModel;

class Ujian extends BaseController
{
    protected $db;
    protected $jadwalModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->jadwalModel = new JadwalUjianModel();
    }

    public function index()
    {
        $guruId = session()->get('id');

        $jadwal = $this->jadwalModel->select('jadwal_ujian.*, kelas.nama_kelas, mapel.nama_mapel')
            ->join('kelas', 'kelas.id = jadwal_ujian.kelas_id')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->where('jadwal_ujian.guru_id', $guruId)
            ->orderBy('tanggal_ujian', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Jadwal Ujian',
            'jadwal' => $jadwal
        ];
        return view('guru/ujian/index', $data);
    }

    public function create()
    {
        $guruId = session()->get('id');

        $kelas = $this->db->table('guru_kelas')
            ->select('kelas.*')
            ->join('kelas', 'kelas.id = guru_kelas.kelas_id')
            ->where('guru_kelas.guru_id', $guruId)
            ->groupBy('kelas.id')
            ->get()->getResultArray();

        $data = [
            'title' => 'Tambah Jadwal Ujian',
            'kelas' => $kelas,
            'validation' => \Config\Services::validation()
        ];
        return view('guru/ujian/create', $data);
    }

    public function getMapelByKelas($kelasId)
    {
        $guruId = session()->get('id');

        $mapel = $this->db->table('mapel')
            ->select('mapel.*')
            ->join('kelas_mapel', 'kelas_mapel.mapel_id = mapel.id')
            ->join('guru_mapel', 'guru_mapel.mapel_id = mapel.id')
            ->where('kelas_mapel.kelas_id', $kelasId)
            ->where('guru_mapel.guru_id', $guruId)
            ->groupBy('mapel.id')
            ->get()->getResultArray();

        return $this->response->setJSON($mapel);
    }

    public function store()
    {
        if (!$this->validate([
            'kelas_id' => 'required',
            'mapel_id' => 'required',
            'tanggal_ujian' => 'required',
            'jam_mulai' => 'required',
            'lama_ujian' => 'required|numeric'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->jadwalModel->save([
            'guru_id' => session()->get('id'),
            'kelas_id' => $this->request->getPost('kelas_id'),
            'mapel_id' => $this->request->getPost('mapel_id'),
            'tanggal_ujian' => $this->request->getPost('tanggal_ujian'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'lama_ujian' => $this->request->getPost('lama_ujian'),
            'status' => 'aktif'
        ]);

        return redirect()->to('guru/ujian')->with('success', 'Jadwal ujian berhasil ditambahkan.');
    }

    public function delete($id)
    {
        $this->jadwalModel->delete($id);
        return redirect()->to('guru/ujian')->with('success', 'Jadwal ujian berhasil dihapus.');
    }
}
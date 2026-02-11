<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SoalModel;
use App\Models\GuruModel;

class BankSoal extends BaseController
{
    protected $soalModel;
    protected $guruModel;
    protected $db;

    public function __construct()
    {
        $this->soalModel = new SoalModel();
        $this->guruModel = new GuruModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Bank Soal (Admin)',
            'pembuat_soal' => $this->guruModel->findAll()
        ];
        return view('admin/bank_soal/index', $data);
    }

    public function mapel($guruId)
    {
        $mapel = $this->db->table('guru_mapel')
            ->select('mapel.*')
            ->join('mapel', 'mapel.id = guru_mapel.mapel_id')
            ->where('guru_mapel.guru_id', $guruId)
            ->get()->getResultArray();

        $guru = $this->guruModel->find($guruId);

        $data = [
            'title' => 'Pilih Mapel',
            'mapel' => $mapel,
            'guru' => $guru
        ];
        return view('admin/bank_soal/mapel', $data);
    }

    public function list($guruId, $mapelId)
    {
        $soal = $this->soalModel
            ->where('guru_id', $guruId)
            ->where('mapel_id', $mapelId)
            ->orderBy('jenis', 'ASC')
            ->findAll();

        $guru = $this->guruModel->find($guruId);
        $mapel = $this->db->table('mapel')->where('id', $mapelId)->get()->getRowArray();

        $data = [
            'title' => 'Koreksi Soal',
            'soal' => $soal,
            'guru' => $guru,
            'mapel' => $mapel
        ];
        return view('admin/bank_soal/list', $data);
    }

    public function edit($id)
    {
        $soal = $this->soalModel->find($id);
        
        if (!$soal) {
            return redirect()->back()->with('error', 'Soal tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Soal Guru',
            'soal' => $soal
        ];

        if ($soal['jenis'] == 'pg_kompleks') {
            return view('admin/bank_soal/edit_pg_kompleks', $data);
        } elseif ($soal['jenis'] == 'benar_salah') {
            return view('admin/bank_soal/edit_benar_salah', $data);
        } else {
            return view('admin/bank_soal/edit', $data);
        }
    }

    public function update($id)
    {
        $jenis = $this->request->getPost('jenis');
        
        $data = [
            'pertanyaan' => $this->request->getPost('pertanyaan'),
        ];
        
        if ($jenis == 'pg') {
            $data['opsi_a'] = $this->request->getPost('opsi_a');
            $data['opsi_b'] = $this->request->getPost('opsi_b');
            $data['opsi_c'] = $this->request->getPost('opsi_c');
            $data['opsi_d'] = $this->request->getPost('opsi_d');
            $data['opsi_e'] = $this->request->getPost('opsi_e');
            $data['kunci_jawaban'] = $this->request->getPost('kunci_jawaban');
        } elseif ($jenis == 'pg_kompleks') {
            $data['opsi_a'] = $this->request->getPost('opsi_a');
            $data['opsi_b'] = $this->request->getPost('opsi_b');
            $data['opsi_c'] = $this->request->getPost('opsi_c');
            $data['opsi_d'] = $this->request->getPost('opsi_d');
            $data['opsi_e'] = $this->request->getPost('opsi_e');
            
            $kunci = $this->request->getPost('kunci_jawaban'); 
            $data['kunci_jawaban'] = json_encode($kunci);
        } elseif ($jenis == 'benar_salah') {
            $subItems = $this->request->getPost('pernyataan_sub');
            $subKeys = $this->request->getPost('kunci_sub');
            
            $data['opsi_a'] = json_encode($subItems);
            $data['kunci_jawaban'] = json_encode($subKeys);
        }

        $this->soalModel->update($id, $data);
        
        $soal = $this->soalModel->find($id);
        return redirect()->to("admin/bank_soal/list/{$soal['guru_id']}/{$soal['mapel_id']}")->with('success', 'Soal berhasil diperbarui Admin.');
    }

    public function delete($id)
    {
        $this->soalModel->delete($id);
        return redirect()->back()->with('success', 'Soal dihapus oleh Admin.');
    }
}
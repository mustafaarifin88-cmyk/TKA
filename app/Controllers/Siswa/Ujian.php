<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\JadwalUjianModel;
use App\Models\SoalModel;
use App\Models\JawabanSiswaModel;
use App\Models\StatusUjianSiswaModel;

class Ujian extends BaseController
{
    protected $jadwalModel;
    protected $soalModel;
    protected $jawabanModel;
    protected $statusUjianModel;
    protected $db;

    public function __construct()
    {
        $this->jadwalModel = new JadwalUjianModel();
        $this->soalModel = new SoalModel();
        $this->jawabanModel = new JawabanSiswaModel();
        $this->statusUjianModel = new StatusUjianSiswaModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $jadwalId = session()->get('jadwal_id_aktif');
        $siswaId = session()->get('id');

        if (!$jadwalId) {
            return redirect()->to('siswa/dashboard');
        }

        $jadwal = $this->jadwalModel
            ->select('jadwal_ujian.*, mapel.nama_mapel, sekolah.nama_sekolah, sekolah.logo')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->join('sekolah', 'sekolah.id = jadwal_ujian.sekolah_id')
            ->find($jadwalId);

        if (!$jadwal) {
            return redirect()->to('siswa/dashboard');
        }

        $status = $this->statusUjianModel
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->first();

        if ($status && $status['status'] == 'selesai') {
            return redirect()->to('siswa/ujian/result');
        }

        if (!$status) {
            $this->statusUjianModel->save([
                'jadwal_id' => $jadwalId,
                'siswa_id' => $siswaId,
                'waktu_mulai' => date('Y-m-d H:i:s'),
                'status' => 'berjalan'
            ]);
        }

        // Ambil semua soal
        $soal = $this->soalModel
            ->where('guru_id', $jadwal['guru_id'])
            ->where('mapel_id', $jadwal['mapel_id'])
            ->findAll();

        // LOGIKA SORTING: Soal Esai diletakkan paling belakang
        usort($soal, function($a, $b) {
            $isEsaiA = ($a['jenis'] === 'esai') ? 1 : 0;
            $isEsaiB = ($b['jenis'] === 'esai') ? 1 : 0;

            // Jika sama-sama esai atau sama-sama bukan esai, urutkan berdasarkan ID (urutan buat)
            if ($isEsaiA === $isEsaiB) {
                return $a['id'] <=> $b['id'];
            }

            // Jika beda, yang esai (nilai 1) ditaruh di belakang yang bukan esai (nilai 0)
            return $isEsaiA <=> $isEsaiB;
        });

        $jawabanSiswa = $this->jawabanModel
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->findAll();

        $jawabanArr = [];
        foreach ($jawabanSiswa as $j) {
            $jawabanArr[$j['soal_id']] = $j['jawaban_siswa'];
        }

        $data = [
            'title' => 'Ujian Berlangsung',
            'jadwal' => $jadwal,
            'soal' => $soal,
            'jawaban' => $jawabanArr,
            'siswa' => $this->db->table('siswa')->where('id', $siswaId)->get()->getRowArray()
        ];

        return view('siswa/ujian/index', $data);
    }

    public function simpanJawaban()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $siswaId = session()->get('id');
        $jadwalId = session()->get('jadwal_id_aktif');
        $soalId = $this->request->getPost('soal_id');
        $jawaban = $this->request->getPost('jawaban');

        if (is_array($jawaban)) {
            $jawaban = json_encode($jawaban);
        }

        $exist = $this->jawabanModel
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->where('soal_id', $soalId)
            ->first();

        if ($exist) {
            $this->jawabanModel->update($exist['id'], [
                'jawaban_siswa' => $jawaban,
                'waktu_submit' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->jawabanModel->save([
                'jadwal_id' => $jadwalId,
                'siswa_id' => $siswaId,
                'soal_id' => $soalId,
                'jawaban_siswa' => $jawaban,
                'waktu_submit' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function selesai()
    {
        $siswaId = session()->get('id');
        $jadwalId = session()->get('jadwal_id_aktif');

        if (!$jadwalId) {
            return redirect()->to('siswa/dashboard');
        }

        $jadwal = $this->jadwalModel->find($jadwalId);
        
        $soalList = $this->soalModel
            ->where('guru_id', $jadwal['guru_id'])
            ->where('mapel_id', $jadwal['mapel_id'])
            ->findAll();

        $jawabanList = $this->jawabanModel
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->findAll();
        
        $mapJawaban = [];
        foreach ($jawabanList as $j) {
            $mapJawaban[$j['soal_id']] = $j['jawaban_siswa'];
        }

        $nilaiPg = 0; $benarPg = 0; $totalPg = 0;
        $nilaiPgK = 0; $benarPgK = 0; $totalPgK = 0;
        $nilaiBS = 0; $benarBS = 0; $totalBS = 0;
        $totalEsai = 0;

        foreach ($soalList as $soal) {
            $jawab = $mapJawaban[$soal['id']] ?? null;
            $kunci = $soal['kunci_jawaban'];

            if ($soal['jenis'] == 'pg') {
                $totalPg++;
                if ($jawab && $jawab == $kunci) {
                    $benarPg++;
                }
            } elseif ($soal['jenis'] == 'pg_kompleks') {
                $totalPgK++;
                $kunciArr = json_decode($kunci, true) ?? [];
                $jawabArr = json_decode($jawab, true) ?? [];
                
                sort($kunciArr);
                sort($jawabArr);
                
                if (!empty($kunciArr) && $kunciArr === $jawabArr) {
                    $benarPgK++;
                }
            } elseif ($soal['jenis'] == 'benar_salah') {
                $totalBS++;
                $kunciArr = json_decode($kunci, true) ?? [];
                $jawabArr = json_decode($jawab, true) ?? [];
                
                if (!empty($kunciArr) && $kunciArr === $jawabArr) {
                    $benarBS++;
                }
            } elseif ($soal['jenis'] == 'esai') {
                $totalEsai++;
            }
        }

        $skorPg = ($totalPg > 0) ? ($benarPg / $totalPg) * $jadwal['bobot_pg'] : 0;
        $skorPgK = ($totalPgK > 0) ? ($benarPgK / $totalPgK) * $jadwal['bobot_pg_kompleks'] : 0;
        $skorBS = ($totalBS > 0) ? ($benarBS / $totalBS) * $jadwal['bobot_benar_salah'] : 0;
        
        $skorTotal = $skorPg + $skorPgK + $skorBS;

        $statusExisting = $this->statusUjianModel
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->first();

        $dataUpdate = [
            'status' => 'selesai',
            'nilai_pg' => $skorPg,
            'nilai_pg_kompleks' => $skorPgK,
            'nilai_benar_salah' => $skorBS,
            'nilai_esai' => 0,
            'nilai_total' => $skorTotal
        ];

        if ($statusExisting) {
            $this->statusUjianModel->update($statusExisting['id'], $dataUpdate);
        } else {
            $dataUpdate['jadwal_id'] = $jadwalId;
            $dataUpdate['siswa_id'] = $siswaId;
            $dataUpdate['waktu_mulai'] = date('Y-m-d H:i:s');
            $this->statusUjianModel->save($dataUpdate);
        }

        return redirect()->to('siswa/ujian/result');
    }

    public function result()
    {
        $siswaId = session()->get('id');
        $jadwalId = session()->get('jadwal_id_aktif');

        if (!$jadwalId) {
             return redirect()->to('siswa/dashboard');
        }

        $status = $this->statusUjianModel
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->first();
        
        if (!$status || $status['status'] != 'selesai') {
            return redirect()->to('siswa/dashboard');
        }

        $jadwal = $this->jadwalModel
             ->select('jadwal_ujian.*, mapel.nama_mapel, sekolah.nama_sekolah')
             ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
             ->join('sekolah', 'sekolah.id = jadwal_ujian.sekolah_id')
             ->find($jadwalId);

        // Ambil daftar soal
        $soalList = $this->soalModel
            ->where('guru_id', $jadwal['guru_id'])
            ->where('mapel_id', $jadwal['mapel_id'])
            ->findAll();

        // TERAPKAN SORTING YANG SAMA (Esai Paling Belakang)
        usort($soalList, function($a, $b) {
            $isEsaiA = ($a['jenis'] === 'esai') ? 1 : 0;
            $isEsaiB = ($b['jenis'] === 'esai') ? 1 : 0;
            if ($isEsaiA === $isEsaiB) {
                return $a['id'] <=> $b['id'];
            }
            return $isEsaiA <=> $isEsaiB;
        });

        $jawabanList = $this->jawabanModel
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->findAll();

        $mapJawaban = [];
        foreach ($jawabanList as $j) {
            $mapJawaban[$j['soal_id']] = $j['jawaban_siswa'];
        }

        $data = [
            'title' => 'Hasil Ujian',
            'status' => $status,
            'jadwal' => $jadwal,
            'soal' => $soalList,
            'jawaban' => $mapJawaban,
            'siswa' => $this->db->table('siswa')->where('id', $siswaId)->get()->getRowArray()
        ];

        session()->remove('jadwal_id_aktif');

        return view('siswa/ujian/result', $data);
    }
}
<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\SoalModel;

class BankSoal extends BaseController
{
    protected $db;
    protected $soalModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->soalModel = new SoalModel();
    }

    public function index()
    {
        $guruId = session()->get('id');

        $guru = $this->db->table('guru')->where('id', $guruId)->get()->getRow();
        
        $sekolah = [];
        if ($guru && $guru->sekolah_id) {
            $sekolah = $this->db->table('sekolah')
                ->where('id', $guru->sekolah_id)
                ->get()->getResultArray();
        }

        $data = [
            'title' => 'Bank Soal - Pilih Sekolah',
            'kelas' => $sekolah 
        ];
        return view('guru/soal/index', $data);
    }

    public function mapel($sekolahId)
    {
        $guruId = session()->get('id');
        
        $mapel = $this->db->table('guru_mapel')
            ->select('mapel.*')
            ->join('mapel', 'mapel.id = guru_mapel.mapel_id')
            ->where('guru_mapel.guru_id', $guruId)
            ->get()->getResultArray();

        $sekolah = $this->db->table('sekolah')->where('id', $sekolahId)->get()->getRowArray();

        $data = [
            'title' => 'Bank Soal - Pilih Mata Pelajaran',
            'mapel' => $mapel,
            'kelas' => $sekolah 
        ];
        return view('guru/soal/mapel', $data);
    }

    public function jenis($sekolahId, $mapelId)
    {
        $guruId = session()->get('id');
        $sekolah = $this->db->table('sekolah')->where('id', $sekolahId)->get()->getRowArray();
        $mapel = $this->db->table('mapel')->where('id', $mapelId)->get()->getRowArray();

        $soalPg = $this->soalModel->where(['sekolah_id' => $sekolahId, 'mapel_id' => $mapelId, 'jenis' => 'pg'])->countAllResults();
        $soalEsai = $this->soalModel->where(['sekolah_id' => $sekolahId, 'mapel_id' => $mapelId, 'jenis' => 'esai'])->countAllResults();
        $soalPgKompleks = $this->soalModel->where(['sekolah_id' => $sekolahId, 'mapel_id' => $mapelId, 'jenis' => 'pg_kompleks'])->countAllResults();
        $soalBenarSalah = $this->soalModel->where(['sekolah_id' => $sekolahId, 'mapel_id' => $mapelId, 'jenis' => 'benar_salah'])->countAllResults();

        $targetSalin = []; 

        $data = [
            'title' => 'Bank Soal - Pilih Jenis Soal',
            'kelas' => $sekolah, 
            'mapel' => $mapel,
            'jumlah_pg' => $soalPg,
            'jumlah_esai' => $soalEsai,
            'jumlah_pg_kompleks' => $soalPgKompleks,
            'jumlah_benar_salah' => $soalBenarSalah,
            'target_salin' => $targetSalin
        ];
        return view('guru/soal/jenis', $data);
    }

    public function salin()
    {
        $sourceSekolahId = $this->request->getPost('source_kelas_id');
        $sourceMapelId = $this->request->getPost('source_mapel_id');
        $targetKombinasi = $this->request->getPost('target_kombinasi');

        if (!$targetKombinasi) {
            return redirect()->back()->with('error', 'Silakan pilih tujuan.');
        }

        list($targetSekolahId, $targetMapelId) = explode('-', $targetKombinasi);

        $soalSource = $this->soalModel
            ->where('sekolah_id', $sourceSekolahId)
            ->where('mapel_id', $sourceMapelId)
            ->findAll();

        if (empty($soalSource)) {
            return redirect()->back()->with('error', 'Tidak ada soal sumber untuk disalin.');
        }

        $newQuestions = [];
        $timestamp = date('Y-m-d H:i:s');

        foreach ($soalSource as $s) {
            unset($s['id']); 
            
            $s['sekolah_id'] = $targetSekolahId;
            $s['mapel_id'] = $targetMapelId;
            $s['created_at'] = $timestamp;
            
            $newQuestions[] = $s;
        }

        if (!empty($newQuestions)) {
            $this->soalModel->insertBatch($newQuestions);
            
            return redirect()->back()->with('success', count($newQuestions) . " Soal berhasil disalin.");
        }

        return redirect()->back()->with('error', 'Gagal menyalin soal.');
    }

    public function create($sekolahId, $mapelId, $jenis)
    {
        $sekolah = $this->db->table('sekolah')->where('id', $sekolahId)->get()->getRowArray();
        $mapel = $this->db->table('mapel')->where('id', $mapelId)->get()->getRowArray();

        $data = [
            'title' => 'Buat Soal ' . strtoupper(str_replace('_', ' ', $jenis)),
            'kelas' => $sekolah, 
            'mapel' => $mapel,
            'jenis' => $jenis
        ];

        if ($jenis == 'pg') {
            return view('guru/soal/create_pg', $data);
        } elseif ($jenis == 'pg_kompleks') {
            return view('guru/soal/create_pg_kompleks', $data);
        } elseif ($jenis == 'benar_salah') {
            return view('guru/soal/create_benar_salah', $data);
        } else {
            return view('guru/soal/create_esai', $data);
        }
    }

    public function store()
    {
        $guruId = session()->get('id');
        $sekolahId = $this->request->getPost('sekolah_id');
        $mapelId = $this->request->getPost('mapel_id');
        $jenis = $this->request->getPost('jenis');
        
        $pertanyaan = $this->request->getPost('pertanyaan'); 
        
        $uploadGambar = function($fileInput, $index) {
            $files = $this->request->getFileMultiple($fileInput);
            if (isset($files[$index]) && $files[$index]->isValid() && !$files[$index]->hasMoved()) {
                $newName = $files[$index]->getRandomName();
                $files[$index]->move('uploads/bank_soal', $newName);
                return $newName;
            }
            return null;
        };

        if ($pertanyaan && is_array($pertanyaan)) {
            $dataBatch = [];
            
            if ($jenis == 'pg') {
                $opsiA = $this->request->getPost('opsi_a');
                $opsiB = $this->request->getPost('opsi_b');
                $opsiC = $this->request->getPost('opsi_c');
                $opsiD = $this->request->getPost('opsi_d');
                $opsiE = $this->request->getPost('opsi_e');
                $kunci = $this->request->getPost('kunci_jawaban');

                foreach ($pertanyaan as $key => $q) {
                    if (!empty($q)) {
                        $dataBatch[] = [
                            'guru_id' => $guruId,
                            'sekolah_id' => $sekolahId,
                            'mapel_id' => $mapelId,
                            'jenis' => 'pg',
                            'pertanyaan' => $q,
                            'file_soal' => $uploadGambar('file_soal', $key),
                            'opsi_a' => $opsiA[$key] ?? '',
                            'file_a' => $uploadGambar('file_a', $key),
                            'opsi_b' => $opsiB[$key] ?? '',
                            'file_b' => $uploadGambar('file_b', $key),
                            'opsi_c' => $opsiC[$key] ?? '',
                            'file_c' => $uploadGambar('file_c', $key),
                            'opsi_d' => $opsiD[$key] ?? '',
                            'file_d' => $uploadGambar('file_d', $key),
                            'opsi_e' => $opsiE[$key] ?? '',
                            'file_e' => $uploadGambar('file_e', $key),
                            'kunci_jawaban' => $kunci[$key] ?? '',
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            } elseif ($jenis == 'pg_kompleks') {
                $opsiA = $this->request->getPost('opsi_a');
                $opsiB = $this->request->getPost('opsi_b');
                $opsiC = $this->request->getPost('opsi_c');
                $opsiD = $this->request->getPost('opsi_d');
                $opsiE = $this->request->getPost('opsi_e');
                $kunci = $this->request->getPost('kunci_jawaban'); 

                foreach ($pertanyaan as $key => $q) {
                    if (!empty($q)) {
                        $jawabanBenar = $kunci[$key] ?? [];
                        
                        $dataBatch[] = [
                            'guru_id' => $guruId,
                            'sekolah_id' => $sekolahId,
                            'mapel_id' => $mapelId,
                            'jenis' => 'pg_kompleks',
                            'pertanyaan' => $q,
                            'file_soal' => $uploadGambar('file_soal', $key),
                            'opsi_a' => $opsiA[$key] ?? '',
                            'file_a' => $uploadGambar('file_a', $key),
                            'opsi_b' => $opsiB[$key] ?? '',
                            'file_b' => $uploadGambar('file_b', $key),
                            'opsi_c' => $opsiC[$key] ?? '',
                            'file_c' => $uploadGambar('file_c', $key),
                            'opsi_d' => $opsiD[$key] ?? '',
                            'file_d' => $uploadGambar('file_d', $key),
                            'opsi_e' => $opsiE[$key] ?? '',
                            'file_e' => $uploadGambar('file_e', $key),
                            'kunci_jawaban' => json_encode($jawabanBenar),
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            } elseif ($jenis == 'benar_salah') {
                $pernyataanSub = $this->request->getPost('pernyataan_sub');
                $kunciSub = $this->request->getPost('kunci_sub');

                foreach ($pertanyaan as $key => $q) {
                    if (!empty($q)) {
                        $subItems = $pernyataanSub[$key] ?? [];
                        $subKeys = $kunciSub[$key] ?? [];

                        $dataBatch[] = [
                            'guru_id' => $guruId,
                            'sekolah_id' => $sekolahId,
                            'mapel_id' => $mapelId,
                            'jenis' => 'benar_salah',
                            'pertanyaan' => $q,
                            'file_soal' => $uploadGambar('file_soal', $key),
                            'opsi_a' => json_encode($subItems),
                            'kunci_jawaban' => json_encode($subKeys),
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            } else {
                foreach ($pertanyaan as $key => $q) {
                    if (!empty($q)) {
                        $dataBatch[] = [
                            'guru_id' => $guruId,
                            'sekolah_id' => $sekolahId,
                            'mapel_id' => $mapelId,
                            'jenis' => 'esai',
                            'pertanyaan' => $q,
                            'file_soal' => $uploadGambar('file_soal', $key),
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }

            if (!empty($dataBatch)) {
                $this->soalModel->insertBatch($dataBatch);
            }
        }

        return redirect()->to("guru/soal/jenis/$sekolahId/$mapelId")->with('success', 'Soal berhasil disimpan.');
    }

    private function cekJadwalAda($sekolahId, $mapelId)
    {
        return $this->db->table('jadwal_ujian')
            ->where('sekolah_id', $sekolahId)
            ->where('mapel_id', $mapelId)
            ->countAllResults() > 0;
    }

    public function list($sekolahId, $mapelId, $jenis)
    {
        $soal = $this->soalModel
            ->where('sekolah_id', $sekolahId)
            ->where('mapel_id', $mapelId)
            ->where('jenis', $jenis)
            ->findAll();

        $sekolah = $this->db->table('sekolah')->where('id', $sekolahId)->get()->getRowArray();
        $mapel = $this->db->table('mapel')->where('id', $mapelId)->get()->getRowArray();
        
        $isLocked = $this->cekJadwalAda($sekolahId, $mapelId);

        $data = [
            'title' => 'Daftar Soal ' . strtoupper(str_replace('_', ' ', $jenis)),
            'soal' => $soal,
            'kelas' => $sekolah, 
            'mapel' => $mapel,
            'jenis' => $jenis,
            'is_locked' => $isLocked
        ];

        return view('guru/soal/list', $data);
    }

    public function edit($soalId)
    {
        $soal = $this->soalModel->find($soalId);
        
        if (!$soal) {
            return redirect()->back()->with('error', 'Soal tidak ditemukan.');
        }

        if ($this->cekJadwalAda($soal['sekolah_id'], $soal['mapel_id'])) {
            return redirect()->back()->with('error', 'Soal tidak bisa diedit karena Jadwal Ujian sudah dibuat.');
        }

        $sekolah = $this->db->table('sekolah')->where('id', $soal['sekolah_id'])->get()->getRowArray();
        $mapel = $this->db->table('mapel')->where('id', $soal['mapel_id'])->get()->getRowArray();

        $data = [
            'title' => 'Edit Soal',
            'soal' => $soal,
            'kelas' => $sekolah, 
            'mapel' => $mapel
        ];

        if ($soal['jenis'] == 'pg') {
            return view('guru/soal/edit_pg', $data);
        } elseif ($soal['jenis'] == 'pg_kompleks') {
            return view('guru/soal/edit_pg_kompleks', $data);
        } elseif ($soal['jenis'] == 'benar_salah') {
            return view('guru/soal/edit_benar_salah', $data);
        } else {
            return view('guru/soal/edit_esai', $data);
        }
    }

    public function update($soalId)
    {
        $soalLama = $this->soalModel->find($soalId);
        
        if ($this->cekJadwalAda($soalLama['sekolah_id'], $soalLama['mapel_id'])) {
            return redirect()->back()->with('error', 'Gagal update. Jadwal ujian sudah ada.');
        }

        $jenis = $this->request->getPost('jenis');
        
        $dataUpdate = [
            'pertanyaan' => $this->request->getPost('pertanyaan')
        ];

        $handleFile = function($inputName, $oldFile) {
            $file = $this->request->getFile($inputName);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if ($oldFile && file_exists('uploads/bank_soal/' . $oldFile)) {
                    unlink('uploads/bank_soal/' . $oldFile);
                }
                $newName = $file->getRandomName();
                $file->move('uploads/bank_soal', $newName);
                return $newName;
            }
            return $oldFile;
        };

        $dataUpdate['file_soal'] = $handleFile('file_soal', $soalLama['file_soal']);

        if ($jenis == 'pg') {
            $dataUpdate['opsi_a'] = $this->request->getPost('opsi_a');
            $dataUpdate['file_a'] = $handleFile('file_a', $soalLama['file_a']);
            $dataUpdate['opsi_b'] = $this->request->getPost('opsi_b');
            $dataUpdate['file_b'] = $handleFile('file_b', $soalLama['file_b']);
            $dataUpdate['opsi_c'] = $this->request->getPost('opsi_c');
            $dataUpdate['file_c'] = $handleFile('file_c', $soalLama['file_c']);
            $dataUpdate['opsi_d'] = $this->request->getPost('opsi_d');
            $dataUpdate['file_d'] = $handleFile('file_d', $soalLama['file_d']);
            $dataUpdate['opsi_e'] = $this->request->getPost('opsi_e');
            $dataUpdate['file_e'] = $handleFile('file_e', $soalLama['file_e']);
            $dataUpdate['kunci_jawaban'] = $this->request->getPost('kunci_jawaban');
        } elseif ($jenis == 'pg_kompleks') {
            $dataUpdate['opsi_a'] = $this->request->getPost('opsi_a');
            $dataUpdate['file_a'] = $handleFile('file_a', $soalLama['file_a']);
            $dataUpdate['opsi_b'] = $this->request->getPost('opsi_b');
            $dataUpdate['file_b'] = $handleFile('file_b', $soalLama['file_b']);
            $dataUpdate['opsi_c'] = $this->request->getPost('opsi_c');
            $dataUpdate['file_c'] = $handleFile('file_c', $soalLama['file_c']);
            $dataUpdate['opsi_d'] = $this->request->getPost('opsi_d');
            $dataUpdate['file_d'] = $handleFile('file_d', $soalLama['file_d']);
            $dataUpdate['opsi_e'] = $this->request->getPost('opsi_e');
            $dataUpdate['file_e'] = $handleFile('file_e', $soalLama['file_e']);
            
            $kunci = $this->request->getPost('kunci_jawaban');
            $dataUpdate['kunci_jawaban'] = json_encode($kunci);
        } elseif ($jenis == 'benar_salah') {
            $subItems = $this->request->getPost('pernyataan_sub');
            $subKeys = $this->request->getPost('kunci_sub');
            
            $dataUpdate['opsi_a'] = json_encode($subItems);
            $dataUpdate['kunci_jawaban'] = json_encode($subKeys);
        }

        $this->soalModel->update($soalId, $dataUpdate);

        return redirect()->to("guru/soal/list/{$soalLama['sekolah_id']}/{$soalLama['mapel_id']}/$jenis")->with('success', 'Soal berhasil diperbarui.');
    }

    public function delete($soalId)
    {
        $soal = $this->soalModel->find($soalId);
        
        if ($this->cekJadwalAda($soal['sekolah_id'], $soal['mapel_id'])) {
            return redirect()->back()->with('error', 'Gagal hapus. Jadwal ujian sudah ada.');
        }

        $files = ['file_soal', 'file_a', 'file_b', 'file_c', 'file_d', 'file_e'];
        foreach($files as $f) {
            if (!empty($soal[$f]) && file_exists('uploads/bank_soal/' . $soal[$f])) {
                unlink('uploads/bank_soal/' . $soal[$f]);
            }
        }

        $this->soalModel->delete($soalId);
        return redirect()->back()->with('success', 'Soal berhasil dihapus.');
    }
}
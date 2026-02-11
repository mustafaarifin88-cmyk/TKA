<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Ujian extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $siswaId = session()->get('id');
        $siswa = $this->db->table('siswa')->where('id', $siswaId)->get()->getRow();

        $builder = $this->db->table('jadwal_ujian');
        $builder->select('jadwal_ujian.*, mapel.nama_mapel, guru.nama_lengkap as nama_guru');
        $builder->join('mapel', 'mapel.id = jadwal_ujian.mapel_id');
        $builder->join('guru', 'guru.id = jadwal_ujian.guru_id', 'left');
        $builder->where('jadwal_ujian.sekolah_id', $siswa->sekolah_id);
        $builder->where('jadwal_ujian.tanggal_ujian', date('Y-m-d'));
        $ujian = $builder->get()->getResultArray();

        foreach ($ujian as &$u) {
            $cekStatus = $this->db->table('status_ujian_siswa')
                ->where('jadwal_id', $u['id'])
                ->where('siswa_id', $siswaId)
                ->get()->getRow();
            
            $u['status_siswa'] = $cekStatus ? $cekStatus->status : 'belum_mulai';
        }

        $data = [
            'title' => 'Daftar Ujian',
            'ujian' => $ujian
        ];

        return view('siswa/ujian/daftar_ujian', $data);
    }

    public function token($jadwalId)
    {
        $jadwal = $this->db->table('jadwal_ujian')->where('id', $jadwalId)->get()->getRow();
        
        $waktuSekarang = date('Y-m-d H:i:s');
        $waktuMulai = $jadwal->tanggal_ujian . ' ' . $jadwal->jam_mulai;
        
        if ($waktuSekarang < $waktuMulai) {
            return redirect()->back()->with('error', 'Ujian belum dimulai. Dimulai pada: ' . $waktuMulai);
        }

        $siswaId = session()->get('id');
        $cekSelesai = $this->db->table('status_ujian_siswa')
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->countAllResults();

        if ($cekSelesai > 0) {
            return redirect()->back()->with('error', 'Anda sudah mengerjakan ujian ini.');
        }

        $cekMulai = $this->db->table('status_ujian_siswa')
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->countAllResults();
        
        if ($cekMulai == 0) {
            $this->db->table('status_ujian_siswa')->insert([
                'jadwal_id' => $jadwalId,
                'siswa_id' => $siswaId,
                'waktu_mulai' => date('Y-m-d H:i:s'),
                'status' => 'sedang_mengerjakan'
            ]);
        }

        return redirect()->to(base_url('siswa/ujian/kerjakan/' . $jadwalId));
    }

    public function kerjakan($jadwalId)
    {
        $siswaId = session()->get('id');
        
        $jadwal = $this->db->table('jadwal_ujian')
            ->select('jadwal_ujian.*, mapel.nama_mapel')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->where('jadwal_ujian.id', $jadwalId)
            ->get()->getRow();

        $statusSiswa = $this->db->table('status_ujian_siswa')
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->get()->getRow();

        if (!$statusSiswa || $statusSiswa->status == 'selesai') {
            return redirect()->to(base_url('siswa/ujian'))->with('error', 'Sesi ujian tidak valid atau sudah selesai.');
        }

        $waktuSelesai = date('Y-m-d H:i:s', strtotime($statusSiswa->waktu_mulai . ' + ' . $jadwal->lama_ujian . ' minutes'));
        
        $soalObjektif = $this->db->table('soal')
            ->where('mapel_id', $jadwal->mapel_id)
            ->where('sekolah_id', $jadwal->sekolah_id)
            ->whereIn('jenis', ['pg', 'pg_kompleks', 'benar_salah'])
            ->orderBy('RAND()')
            ->get()->getResultArray();

        $soalEsai = $this->db->table('soal')
            ->where('mapel_id', $jadwal->mapel_id)
            ->where('sekolah_id', $jadwal->sekolah_id)
            ->where('jenis', 'esai')
            ->orderBy('id', 'ASC')
            ->get()->getResultArray();

        $semuaSoal = array_merge($soalObjektif, $soalEsai);

        $jawabanTersimpan = $this->db->table('hasil_ujian')
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->get()->getResultArray();
        
        $jawabanMap = [];
        foreach($jawabanTersimpan as $j) {
            $jawabanMap[$j['soal_id']] = $j['jawaban_siswa'];
        }

        $data = [
            'jadwal' => $jadwal,
            'waktu_selesai' => $waktuSelesai,
            'daftar_soal' => $semuaSoal, 
            'jawaban_map' => $jawabanMap,
            'total_soal' => count($semuaSoal)
        ];

        return view('siswa/ujian/lembar_ujian', $data);
    }

    public function simpan_jawaban()
    {
        if ($this->request->isAJAX()) {
            $siswaId = session()->get('id');
            $jadwalId = $this->request->getPost('jadwal_id');
            $soalId = $this->request->getPost('soal_id');
            $jawaban = $this->request->getPost('jawaban');

            if (is_array($jawaban)) {
                $jawaban = json_encode($jawaban);
            }

            $cek = $this->db->table('hasil_ujian')
                ->where('jadwal_id', $jadwalId)
                ->where('siswa_id', $siswaId)
                ->where('soal_id', $soalId)
                ->countAllResults();

            if ($cek > 0) {
                $this->db->table('hasil_ujian')
                    ->where('jadwal_id', $jadwalId)
                    ->where('siswa_id', $siswaId)
                    ->where('soal_id', $soalId)
                    ->update(['jawaban_siswa' => $jawaban, 'waktu_submit' => date('Y-m-d H:i:s')]);
            } else {
                $this->db->table('hasil_ujian')->insert([
                    'jadwal_id' => $jadwalId,
                    'siswa_id' => $siswaId,
                    'soal_id' => $soalId,
                    'jawaban_siswa' => $jawaban,
                    'waktu_submit' => date('Y-m-d H:i:s')
                ]);
            }
            return json_encode(['status' => 'success']);
        }
    }

    public function selesai_ujian()
    {
        $siswaId = session()->get('id');
        $jadwalId = $this->request->getPost('jadwal_id');

        $this->hitungNilaiOtomatis($jadwalId, $siswaId);

        $this->db->table('status_ujian_siswa')
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->update(['status' => 'selesai']);

        return redirect()->to(base_url('siswa/dashboard'))->with('success', 'Ujian berhasil dikumpulkan.');
    }

    private function hitungNilaiOtomatis($jadwalId, $siswaId)
    {
        $jawabanSiswa = $this->db->table('hasil_ujian')
            ->select('hasil_ujian.jawaban_siswa, soal.jenis, soal.kunci_jawaban')
            ->join('soal', 'soal.id = hasil_ujian.soal_id')
            ->where('hasil_ujian.jadwal_id', $jadwalId)
            ->where('hasil_ujian.siswa_id', $siswaId)
            ->get()->getResultArray();

        $score = ['pg' => 0, 'pg_kompleks' => 0, 'benar_salah' => 0];
        $total = ['pg' => 0, 'pg_kompleks' => 0, 'benar_salah' => 0];

        foreach($jawabanSiswa as $j) {
            $jenis = $j['jenis'];
            if ($jenis == 'esai') continue;

            $total[$jenis]++;
            $kunci = $j['kunci_jawaban'];
            $jawab = $j['jawaban_siswa'];

            if ($jenis == 'pg') {
                if (trim($jawab) == trim($kunci)) {
                    $score['pg']++;
                }
            } 
            elseif ($jenis == 'pg_kompleks') {
                $kunciArr = json_decode($kunci, true);
                $jawabArr = json_decode($jawab, true);

                if (is_array($kunciArr) && is_array($jawabArr)) {
                    sort($kunciArr);
                    sort($jawabArr);
                    if ($kunciArr === $jawabArr) {
                        $score['pg_kompleks']++;
                    }
                }
            }
            elseif ($jenis == 'benar_salah') {
                $kunciArr = json_decode($kunci, true);
                $jawabArr = json_decode($jawab, true);

                if (is_array($kunciArr) && is_array($jawabArr) && count($kunciArr) == count($jawabArr)) {
                    if ($kunciArr === $jawabArr) {
                        $score['benar_salah']++;
                    }
                }
            }
        }

        $nilaiPg = ($total['pg'] > 0) ? ($score['pg'] / $total['pg']) * 100 : 0;
        $nilaiPgKompleks = ($total['pg_kompleks'] > 0) ? ($score['pg_kompleks'] / $total['pg_kompleks']) * 100 : 0;
        $nilaiBenarSalah = ($total['benar_salah'] > 0) ? ($score['benar_salah'] / $total['benar_salah']) * 100 : 0;

        $this->db->table('status_ujian_siswa')
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->update([
                'nilai_pg' => $nilaiPg,
                'nilai_pg_kompleks' => $nilaiPgKompleks,
                'nilai_benar_salah' => $nilaiBenarSalah
            ]);
    }
}
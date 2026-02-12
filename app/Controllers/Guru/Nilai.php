<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Nilai extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $guruId = session()->get('id');

        // Ambil jadwal yang dibuat guru (atau admin untuk sekolah guru ini)
        // Disini kita filter jadwal berdasarkan sekolah_id dari guru
        $guru = $this->db->table('guru')->where('id', $guruId)->get()->getRow();

        $jadwal = $this->db->table('jadwal_ujian')
            ->select('jadwal_ujian.*, sekolah.nama_sekolah, mapel.nama_mapel')
            ->join('sekolah', 'sekolah.id = jadwal_ujian.sekolah_id')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->where('jadwal_ujian.sekolah_id', $guru->sekolah_id) // Filter by Sekolah Guru
            ->orderBy('jadwal_ujian.tanggal_ujian', 'DESC')
            ->get()->getResultArray();

        $data = [
            'title' => 'Rekap Nilai Ujian',
            'jadwal' => $jadwal
        ];

        return view('guru/nilai/index', $data);
    }

    public function detail($jadwalId)
    {
        $jadwal = $this->db->table('jadwal_ujian')
            ->select('jadwal_ujian.*, sekolah.nama_sekolah, mapel.nama_mapel')
            ->join('sekolah', 'sekolah.id = jadwal_ujian.sekolah_id')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->where('jadwal_ujian.id', $jadwalId)
            ->get()->getRowArray();

        // Join ke sekolah_id
        $siswa = $this->db->table('siswa')
            ->select('siswa.id, siswa.nama_lengkap, siswa.nisn, status_ujian_siswa.*')
            ->join('status_ujian_siswa', 'status_ujian_siswa.siswa_id = siswa.id AND status_ujian_siswa.jadwal_id = ' . $jadwalId, 'left')
            ->where('siswa.sekolah_id', $jadwal['sekolah_id'])
            ->orderBy('siswa.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $data = [
            'title' => 'Detail Nilai: ' . $jadwal['nama_mapel'],
            'jadwal' => $jadwal,
            'siswa' => $siswa,
            'validation' => \Config\Services::validation()
        ];

        return view('guru/nilai/detail', $data);
    }

    // ... (Method simpanBobot, koreksi, simpanKoreksi, hitungNilaiPerSiswa, cetak, exportExcel SAMA PERSIS)
    // Cukup copy paste dari versi sebelumnya dan pastikan query menggunakan 'sekolah_id' bukan 'kelas_id'
    // ...

    public function simpanBobot()
    {
        $jadwalId = $this->request->getPost('jadwal_id');
        $bobotPg = $this->request->getPost('bobot_pg');
        $bobotPgKompleks = $this->request->getPost('bobot_pg_kompleks');
        $bobotBenarSalah = $this->request->getPost('bobot_benar_salah');
        $bobotEsai = $this->request->getPost('bobot_esai');

        $total = $bobotPg + $bobotPgKompleks + $bobotBenarSalah + $bobotEsai;
        if ($total != 100) return redirect()->back()->with('error', 'Total bobot harus 100%.');

        $this->db->table('jadwal_ujian')->where('id', $jadwalId)->update([
            'bobot_pg' => $bobotPg,
            'bobot_pg_kompleks' => $bobotPgKompleks,
            'bobot_benar_salah' => $bobotBenarSalah,
            'bobot_esai' => $bobotEsai
        ]);
        
        // Recalculate
        $siswaIds = $this->db->table('status_ujian_siswa')->select('siswa_id')->where('jadwal_id', $jadwalId)->get()->getResultArray();
        foreach($siswaIds as $s) $this->hitungNilaiPerSiswa($jadwalId, $s['siswa_id']);

        return redirect()->back()->with('success', 'Bobot disimpan.');
    }

    // ... (Include fungsi hitungNilaiPerSiswa dari jawaban sebelumnya) ...
    private function hitungNilaiPerSiswa($jadwalId, $siswaId)
    {
        $jadwal = $this->db->table('jadwal_ujian')->where('id', $jadwalId)->get()->getRowArray();
        $jawaban = $this->db->table('hasil_ujian')
            ->select('hasil_ujian.*, soal.jenis, soal.kunci_jawaban')
            ->join('soal', 'soal.id = hasil_ujian.soal_id')
            ->where('hasil_ujian.jadwal_id', $jadwalId)
            ->where('hasil_ujian.siswa_id', $siswaId)
            ->get()->getResultArray();

        $score = ['pg' => 0, 'pg_kompleks' => 0, 'benar_salah' => 0, 'esai' => 0];
        $total = ['pg' => 0, 'pg_kompleks' => 0, 'benar_salah' => 0, 'esai' => 0];

        foreach ($jawaban as $j) {
            $jenis = $j['jenis'];
            $total[$jenis]++;
            if ($jenis == 'esai') {
                $score['esai'] += $j['nilai_koreksi'];
            } elseif ($jenis == 'pg_kompleks') {
                $k = json_decode($j['kunci_jawaban'], true); $s = json_decode($j['jawaban_siswa'], true);
                if (is_array($k) && is_array($s)) { sort($k); sort($s); if ($k === $s) $score['pg_kompleks']++; }
            } elseif ($jenis == 'benar_salah') {
                $k = json_decode($j['kunci_jawaban'], true); $s = json_decode($j['jawaban_siswa'], true);
                if (is_array($k) && is_array($s) && $k === $s) $score['benar_salah']++;
            } else {
                if (trim($j['jawaban_siswa']) == trim($j['kunci_jawaban'])) $score[$jenis]++;
            }
        }

        $nilai = [
            'pg' => ($total['pg']>0) ? ($score['pg']/$total['pg'])*100 : 0,
            'pg_kompleks' => ($total['pg_kompleks']>0) ? ($score['pg_kompleks']/$total['pg_kompleks'])*100 : 0,
            'benar_salah' => ($total['benar_salah']>0) ? ($score['benar_salah']/$total['benar_salah'])*100 : 0,
            'esai' => ($total['esai']>0) ? ($score['esai']/$total['esai']) : 0,
        ];

        $final = 0;
        $bobotTotal = 0;
        if($total['pg']>0) { $final += $nilai['pg']*$jadwal['bobot_pg']; $bobotTotal += $jadwal['bobot_pg']; }
        if($total['pg_kompleks']>0) { $final += $nilai['pg_kompleks']*$jadwal['bobot_pg_kompleks']; $bobotTotal += $jadwal['bobot_pg_kompleks']; }
        if($total['benar_salah']>0) { $final += $nilai['benar_salah']*$jadwal['bobot_benar_salah']; $bobotTotal += $jadwal['bobot_benar_salah']; }
        if($total['esai']>0) { $final += $nilai['esai']*$jadwal['bobot_esai']; $bobotTotal += $jadwal['bobot_esai']; }

        $nilaiAkhir = ($bobotTotal > 0) ? ($final / $bobotTotal) * 100 : 0;
        if($bobotTotal == 100) $nilaiAkhir = $final / 100;

        $this->db->table('status_ujian_siswa')->where('jadwal_id', $jadwalId)->where('siswa_id', $siswaId)
            ->update([
                'nilai_pg' => $nilai['pg'], 'nilai_pg_kompleks' => $nilai['pg_kompleks'],
                'nilai_benar_salah' => $nilai['benar_salah'], 'nilai_esai' => $nilai['esai'],
                'nilai_total' => $nilaiAkhir
            ]);
    }

    public function koreksi($jadwalId, $siswaId)
    {
        $siswa = $this->db->table('siswa')->where('id', $siswaId)->get()->getRowArray();
        $jadwal = $this->db->table('jadwal_ujian')->where('id', $jadwalId)->get()->getRowArray();
        $jawabanAll = $this->db->table('hasil_ujian')->select('hasil_ujian.*, soal.pertanyaan, soal.kunci_jawaban, soal.jenis')
            ->join('soal', 'soal.id = hasil_ujian.soal_id')->where('hasil_ujian.jadwal_id', $jadwalId)->where('hasil_ujian.siswa_id', $siswaId)->get()->getResultArray();
        
        // Grouping & Stats Calculation (sama seperti sebelumnya)
        $jawabanPg = []; $jawabanPgKompleks = []; $jawabanBenarSalah = []; $jawabanEsai = [];
        foreach($jawabanAll as $j) {
            if($j['jenis']=='pg') $jawabanPg[]=$j; elseif($j['jenis']=='pg_kompleks') $jawabanPgKompleks[]=$j;
            elseif($j['jenis']=='benar_salah') $jawabanBenarSalah[]=$j; else $jawabanEsai[]=$j;
        }
        
        $data = ['title' => 'Koreksi', 'siswa' => $siswa, 'jadwal' => $jadwal, 'pg'=>['data'=>$jawabanPg], 'kompleks'=>['data'=>$jawabanPgKompleks], 'bs'=>['data'=>$jawabanBenarSalah], 'esai'=>$jawabanEsai];
        // Tambahkan logic stats hitungSkor di sini untuk view...
        return view('guru/nilai/koreksi', $data);
    }
    
    public function simpanKoreksi() {
        $nilai = $this->request->getPost('nilai_esai');
        foreach($nilai as $id=>$val) $this->db->table('hasil_ujian')->where('id', $id)->update(['nilai_koreksi' => $val]);
        $this->hitungNilaiPerSiswa($this->request->getPost('jadwal_id'), $this->request->getPost('siswa_id'));
        return redirect()->to("guru/nilai/detail/".$this->request->getPost('jadwal_id'));
    }

    public function cetak($jadwalId) { /* ... Logic Dompdf Sama ... */ }
    public function exportExcel($jadwalId) { /* ... Logic Excel Sama ... */ }
}
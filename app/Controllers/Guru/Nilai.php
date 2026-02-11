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

        $jadwal = $this->db->table('jadwal_ujian')
            ->select('jadwal_ujian.*, sekolah.nama_sekolah, mapel.nama_mapel')
            ->join('sekolah', 'sekolah.id = jadwal_ujian.sekolah_id')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->where('jadwal_ujian.guru_id', $guruId)
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

        $siswa = $this->db->table('siswa')
            ->select('siswa.id, siswa.nama_lengkap, siswa.nisn, status_ujian_siswa.nilai_pg, status_ujian_siswa.nilai_pg_kompleks, status_ujian_siswa.nilai_benar_salah, status_ujian_siswa.nilai_esai, status_ujian_siswa.nilai_total, status_ujian_siswa.status')
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

    public function simpanBobot()
    {
        $jadwalId = $this->request->getPost('jadwal_id');
        $bobotPg = $this->request->getPost('bobot_pg');
        $bobotPgKompleks = $this->request->getPost('bobot_pg_kompleks');
        $bobotBenarSalah = $this->request->getPost('bobot_benar_salah');
        $bobotEsai = $this->request->getPost('bobot_esai');

        $totalBobot = $bobotPg + $bobotPgKompleks + $bobotBenarSalah + $bobotEsai;

        if ($totalBobot != 100) {
            return redirect()->back()->with('error', 'Total persentase bobot harus pas 100%. Saat ini: ' . $totalBobot . '%');
        }

        $this->db->table('jadwal_ujian')->where('id', $jadwalId)->update([
            'bobot_pg' => $bobotPg,
            'bobot_pg_kompleks' => $bobotPgKompleks,
            'bobot_benar_salah' => $bobotBenarSalah,
            'bobot_esai' => $bobotEsai
        ]);

        $this->hitungUlangSemua($jadwalId);

        return redirect()->back()->with('success', 'Pengaturan bobot berhasil diperbarui & nilai siswa dihitung ulang.');
    }

    public function koreksi($jadwalId, $siswaId)
    {
        $siswa = $this->db->table('siswa')->where('id', $siswaId)->get()->getRowArray();
        
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $jadwal = $this->db->table('jadwal_ujian')->where('id', $jadwalId)->get()->getRowArray();
        
        if (!$jadwal) {
            return redirect()->back()->with('error', 'Data jadwal ujian tidak ditemukan.');
        }

        $jawabanAll = $this->db->table('hasil_ujian')
            ->select('hasil_ujian.*, soal.pertanyaan, soal.kunci_jawaban, soal.jenis')
            ->join('soal', 'soal.id = hasil_ujian.soal_id')
            ->where('hasil_ujian.jadwal_id', $jadwalId)
            ->where('hasil_ujian.siswa_id', $siswaId)
            ->get()->getResultArray();

        $jawabanPg = [];
        $jawabanPgKompleks = [];
        $jawabanBenarSalah = [];
        $jawabanEsai = [];

        foreach ($jawabanAll as $j) {
            if ($j['jenis'] == 'pg') $jawabanPg[] = $j;
            elseif ($j['jenis'] == 'pg_kompleks') $jawabanPgKompleks[] = $j;
            elseif ($j['jenis'] == 'benar_salah') $jawabanBenarSalah[] = $j;
            elseif ($j['jenis'] == 'esai') $jawabanEsai[] = $j;
        }

        $hitungSkor = function($data, $jenis) {
            $benar = 0;
            $total = count($data);
            foreach ($data as $item) {
                if ($jenis == 'pg_kompleks') {
                    $kunci = json_decode($item['kunci_jawaban'], true);
                    $jawab = json_decode($item['jawaban_siswa'], true);
                    if (is_array($kunci) && is_array($jawab)) {
                        sort($kunci); sort($jawab);
                        if ($kunci === $jawab) $benar++;
                    }
                } elseif ($jenis == 'benar_salah') {
                    $kunciArr = json_decode($item['kunci_jawaban'], true);
                    $jawabArr = json_decode($item['jawaban_siswa'], true);
                    if (is_array($kunciArr) && is_array($jawabArr) && count($kunciArr) == count($jawabArr)) {
                        if ($kunciArr === $jawabArr) $benar++;
                    }
                } else {
                    if (trim($item['jawaban_siswa']) == trim($item['kunci_jawaban'])) $benar++;
                }
            }
            return ['benar' => $benar, 'total' => $total, 'nilai' => ($total > 0 ? ($benar/$total)*100 : 0)];
        };

        $data = [
            'title' => 'Koreksi Jawaban: ' . $siswa['nama_lengkap'],
            'siswa' => $siswa,
            'jadwal' => $jadwal,
            'pg' => ['data' => $jawabanPg, 'stats' => $hitungSkor($jawabanPg, 'pg')],
            'kompleks' => ['data' => $jawabanPgKompleks, 'stats' => $hitungSkor($jawabanPgKompleks, 'pg_kompleks')],
            'bs' => ['data' => $jawabanBenarSalah, 'stats' => $hitungSkor($jawabanBenarSalah, 'benar_salah')],
            'esai' => $jawabanEsai
        ];

        return view('guru/nilai/koreksi', $data);
    }

    public function simpanKoreksi()
    {
        $jadwalId = $this->request->getPost('jadwal_id');
        $siswaId = $this->request->getPost('siswa_id');
        $nilaiKoreksiEsai = $this->request->getPost('nilai_esai'); 

        if ($nilaiKoreksiEsai) {
            foreach ($nilaiKoreksiEsai as $hasilId => $skor) {
                $this->db->table('hasil_ujian')->where('id', $hasilId)->update(['nilai_koreksi' => $skor]);
            }
        }

        $this->hitungNilaiPerSiswa($jadwalId, $siswaId);

        return redirect()->to("guru/nilai/detail/$jadwalId")->with('success', 'Nilai siswa berhasil disimpan.');
    }

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
                $k = json_decode($j['kunci_jawaban'], true);
                $s = json_decode($j['jawaban_siswa'], true);
                if (is_array($k) && is_array($s)) {
                    sort($k); sort($s);
                    if ($k === $s) $score['pg_kompleks']++;
                }
            } elseif ($jenis == 'benar_salah') {
                $k = json_decode($j['kunci_jawaban'], true);
                $s = json_decode($j['jawaban_siswa'], true);
                if (is_array($k) && is_array($s) && count($k) == count($s)) {
                    if ($k === $s) $score['benar_salah']++;
                }
            } else {
                if (trim($j['jawaban_siswa']) == trim($j['kunci_jawaban'])) $score[$jenis]++;
            }
        }

        $nilaiAkhir = [
            'pg' => ($total['pg'] > 0) ? ($score['pg'] / $total['pg']) * 100 : 0,
            'pg_kompleks' => ($total['pg_kompleks'] > 0) ? ($score['pg_kompleks'] / $total['pg_kompleks']) * 100 : 0,
            'benar_salah' => ($total['benar_salah'] > 0) ? ($score['benar_salah'] / $total['benar_salah']) * 100 : 0,
            'esai' => ($total['esai'] > 0) ? ($score['esai'] / $total['esai']) : 0, 
        ];

        $bobotTotal = 0;
        $totalSkorAkhir = 0;

        $types = [
            'pg' => 'bobot_pg', 
            'pg_kompleks' => 'bobot_pg_kompleks', 
            'benar_salah' => 'bobot_benar_salah', 
            'esai' => 'bobot_esai'
        ];

        foreach($types as $key => $column) {
            if ($total[$key] > 0) {
                $bobotTotal += $jadwal[$column];
                $totalSkorAkhir += ($nilaiAkhir[$key] * $jadwal[$column]);
            }
        }

        $finalScore = ($bobotTotal > 0) ? ($totalSkorAkhir / $bobotTotal) * 100 : 0; 
        if ($bobotTotal == 100) $finalScore = $totalSkorAkhir / 100;

        $this->db->table('status_ujian_siswa')
            ->where('jadwal_id', $jadwalId)
            ->where('siswa_id', $siswaId)
            ->update([
                'nilai_pg' => $nilaiAkhir['pg'],
                'nilai_pg_kompleks' => $nilaiAkhir['pg_kompleks'],
                'nilai_benar_salah' => $nilaiAkhir['benar_salah'],
                'nilai_esai' => $nilaiAkhir['esai'],
                'nilai_total' => $finalScore
            ]);
    }

    private function hitungUlangSemua($jadwalId)
    {
        $siswaIds = $this->db->table('status_ujian_siswa')
            ->select('siswa_id')
            ->where('jadwal_id', $jadwalId)
            ->get()->getResultArray();

        foreach ($siswaIds as $s) {
            $this->hitungNilaiPerSiswa($jadwalId, $s['siswa_id']);
        }
    }

    public function cetak($jadwalId)
    {
        $guruId = session()->get('id');
        $guru = $this->db->table('guru')->where('id', $guruId)->get()->getRowArray();
        $instansi = $this->db->table('profil_instansi')->where('id', 1)->get()->getRowArray();

        $jadwal = $this->db->table('jadwal_ujian')
            ->select('jadwal_ujian.*, sekolah.nama_sekolah, mapel.nama_mapel')
            ->join('sekolah', 'sekolah.id = jadwal_ujian.sekolah_id')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->where('jadwal_ujian.id', $jadwalId)
            ->get()->getRowArray();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Data ujian tidak ditemukan.');
        }

        $siswa = $this->db->table('siswa')
            ->select('siswa.nama_lengkap, siswa.nisn, status_ujian_siswa.*')
            ->join('status_ujian_siswa', 'status_ujian_siswa.siswa_id = siswa.id AND status_ujian_siswa.jadwal_id = ' . $jadwalId, 'left')
            ->where('siswa.sekolah_id', $jadwal['sekolah_id'])
            ->orderBy('siswa.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $data = [
            'guru' => $guru,
            'sekolah' => $instansi,
            'jadwal' => $jadwal,
            'siswa' => $siswa
        ];

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        
        $html = view('guru/nilai/cetak', $data);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Nilai_' . str_replace(' ', '_', $jadwal['nama_mapel']) . '_' . str_replace(' ', '_', $jadwal['nama_sekolah']);
        
        $dompdf->stream($filename, ["Attachment" => false]);
        exit();
    }

    public function exportExcel($jadwalId)
    {
        $jadwal = $this->db->table('jadwal_ujian')
            ->select('jadwal_ujian.*, sekolah.nama_sekolah, mapel.nama_mapel')
            ->join('sekolah', 'sekolah.id = jadwal_ujian.sekolah_id')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->where('jadwal_ujian.id', $jadwalId)
            ->get()->getRowArray();

        if (!$jadwal) return redirect()->back();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'LAPORAN NILAI DETAIL');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Mapel: ' . $jadwal['nama_mapel']);
        $sheet->setCellValue('A3', 'Sekolah: ' . $jadwal['nama_sekolah']);
        $sheet->setCellValue('D2', 'Bobot PG: ' . $jadwal['bobot_pg'] . '%');
        $sheet->setCellValue('D3', 'Bobot Komp: ' . $jadwal['bobot_pg_kompleks'] . '%');
        $sheet->setCellValue('E2', 'Bobot B/S: ' . $jadwal['bobot_benar_salah'] . '%');
        $sheet->setCellValue('E3', 'Bobot Esai: ' . $jadwal['bobot_esai'] . '%');

        $row = 5;
        $headers = ['No', 'NISN', 'Nama Siswa', 'Nilai PG', 'Nilai Komp', 'Nilai B/S', 'Nilai Esai', 'Nilai Akhir'];
        $col = 'A';
        foreach($headers as $h) {
            $sheet->setCellValue($col . $row, $h);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF198754']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A5:H5')->applyFromArray($headerStyle);

        $siswa = $this->db->table('siswa')
            ->select('siswa.nama_lengkap, siswa.nisn, status_ujian_siswa.*')
            ->join('status_ujian_siswa', 'status_ujian_siswa.siswa_id = siswa.id AND status_ujian_siswa.jadwal_id = ' . $jadwalId, 'left')
            ->where('siswa.sekolah_id', $jadwal['sekolah_id'])
            ->orderBy('siswa.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $row = 6;
        $no = 1;
        foreach($siswa as $s) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $s['nisn']);
            $sheet->setCellValue('C' . $row, $s['nama_lengkap']);
            $sheet->setCellValue('D' . $row, number_format($s['nilai_pg'] ?? 0, 2));
            $sheet->setCellValue('E' . $row, number_format($s['nilai_pg_kompleks'] ?? 0, 2));
            $sheet->setCellValue('F' . $row, number_format($s['nilai_benar_salah'] ?? 0, 2));
            $sheet->setCellValue('G' . $row, number_format($s['nilai_esai'] ?? 0, 2));
            $sheet->setCellValue('H' . $row, number_format($s['nilai_total'] ?? 0, 2));
            
            $sheet->getStyle('H'.$row)->getFont()->setBold(true);
            
            $row++;
        }

        $lastRow = $row - 1;
        $sheet->getStyle('A5:H' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $filename = 'Nilai_' . str_replace(' ', '_', $jadwal['nama_mapel']) . '_' . $jadwal['nama_sekolah'];
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
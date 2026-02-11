<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class HasilUjian extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $sekolahId = $this->request->getGet('sekolah_id');
        $mapelId = $this->request->getGet('mapel_id');
        
        $siswaData = [];
        $jadwalInfo = null;

        if ($sekolahId && $mapelId) {
            $jadwal = $this->db->table('jadwal_ujian')
                ->where('sekolah_id', $sekolahId)
                ->where('mapel_id', $mapelId)
                ->orderBy('id', 'DESC')
                ->get()->getRowArray();

            if ($jadwal) {
                $jadwalInfo = $jadwal;
                
                $allSiswa = $this->db->table('siswa')
                    ->where('sekolah_id', $sekolahId)
                    ->orderBy('nama_lengkap', 'ASC')
                    ->get()->getResultArray();

                foreach ($allSiswa as $s) {
                    $status = $this->db->table('status_ujian_siswa')
                        ->where('jadwal_id', $jadwal['id'])
                        ->where('siswa_id', $s['id'])
                        ->get()->getRowArray();

                    $siswaData[] = [
                        'nama' => $s['nama_lengkap'],
                        'nisn' => $s['nisn'],
                        'status' => $status ? $status['status'] : 'belum_ujian', 
                        'waktu_mulai' => $status ? $status['waktu_mulai'] : '-',
                        'nilai_akhir' => $status ? $status['nilai_total'] : 0
                    ];
                }
            }
        }

        $sekolah = $this->db->table('sekolah')->get()->getResultArray();
        $mapel = $this->db->table('mapel')->get()->getResultArray();

        $data = [
            'title' => 'Monitoring & Hasil Ujian',
            'sekolah' => $sekolah,
            'mapel' => $mapel,
            'siswa_data' => $siswaData,
            'jadwal_info' => $jadwalInfo,
            'selected_sekolah' => $sekolahId,
            'selected_mapel' => $mapelId
        ];

        return view('admin/hasil_ujian/index', $data);
    }

    public function cetak($sekolahId, $mapelId)
    {
        $jadwal = $this->db->table('jadwal_ujian')
            ->select('jadwal_ujian.*, sekolah.nama_sekolah, mapel.nama_mapel, guru.nama_lengkap as nama_guru')
            ->join('sekolah', 'sekolah.id = jadwal_ujian.sekolah_id')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->join('guru', 'guru.id = jadwal_ujian.guru_id', 'left') 
            ->where('jadwal_ujian.sekolah_id', $sekolahId)
            ->where('jadwal_ujian.mapel_id', $mapelId)
            ->orderBy('id', 'DESC')
            ->get()->getRowArray();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Data ujian tidak ditemukan untuk dicetak.');
        }

        $instansi = $this->db->table('profil_instansi')->where('id', 1)->get()->getRowArray();

        $siswaData = [];
        $allSiswa = $this->db->table('siswa')
            ->where('sekolah_id', $sekolahId)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        foreach ($allSiswa as $s) {
            $status = $this->db->table('status_ujian_siswa')
                ->where('jadwal_id', $jadwal['id'])
                ->where('siswa_id', $s['id'])
                ->get()->getRowArray();

            $siswaData[] = [
                'nama' => $s['nama_lengkap'],
                'nisn' => $s['nisn'],
                'status' => $status ? $status['status'] : 'belum_ujian',
                'nilai_pg' => $status ? $status['nilai_pg'] : 0,
                'nilai_pg_kompleks' => $status ? $status['nilai_pg_kompleks'] : 0,
                'nilai_benar_salah' => $status ? $status['nilai_benar_salah'] : 0,
                'nilai_esai' => $status ? $status['nilai_esai'] : 0,
                'nilai_akhir' => $status ? $status['nilai_total'] : 0
            ];
        }

        $data = [
            'sekolah' => $instansi, 
            'jadwal' => $jadwal,
            'siswa' => $siswaData
        ];

        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        
        $html = view('admin/hasil_ujian/cetak_pdf', $data);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Hasil_Ujian_' . str_replace(' ', '_', $jadwal['nama_mapel']) . '_' . str_replace(' ', '_', $jadwal['nama_sekolah']);
        
        $dompdf->stream($filename, ["Attachment" => false]);
        exit();
    }

    public function exportExcel($sekolahId, $mapelId)
    {
        $jadwal = $this->db->table('jadwal_ujian')
            ->select('jadwal_ujian.*, sekolah.nama_sekolah, mapel.nama_mapel')
            ->join('sekolah', 'sekolah.id = jadwal_ujian.sekolah_id')
            ->join('mapel', 'mapel.id = jadwal_ujian.mapel_id')
            ->where('jadwal_ujian.sekolah_id', $sekolahId)
            ->where('jadwal_ujian.mapel_id', $mapelId)
            ->orderBy('id', 'DESC')
            ->get()->getRowArray();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Data ujian tidak ditemukan.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'REKAP HASIL UJIAN');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Mata Pelajaran: ' . $jadwal['nama_mapel']);
        $sheet->setCellValue('A3', 'Sekolah: ' . $jadwal['nama_sekolah']);
        $sheet->setCellValue('A4', 'Tanggal: ' . date('d M Y', strtotime($jadwal['tanggal_ujian'])));

        $row = 6;
        $headers = ['No', 'NISN', 'Nama Siswa', 'Status Ujian', 'Nilai PG', 'Nilai PG Komp', 'Nilai B/S', 'Nilai Esai', 'Nilai Akhir'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . $row, $h);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF435EBE']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A6:I6')->applyFromArray($headerStyle);

        $siswa = $this->db->table('siswa')
            ->where('sekolah_id', $sekolahId)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $row = 7;
        $no = 1;
        foreach ($siswa as $s) {
            $status = $this->db->table('status_ujian_siswa')
                ->where('jadwal_id', $jadwal['id'])
                ->where('siswa_id', $s['id'])
                ->get()->getRowArray();

            $statusText = $status ? ($status['status'] == 'selesai' ? 'Selesai' : 'Sedang Mengerjakan') : 'Belum Ujian';
            $nilaiPg = $status ? $status['nilai_pg'] : 0;
            $nilaiPgKompleks = $status ? $status['nilai_pg_kompleks'] : 0;
            $nilaiBenarSalah = $status ? $status['nilai_benar_salah'] : 0;
            $nilaiEsai = $status ? $status['nilai_esai'] : 0;
            $nilaiTotal = $status ? $status['nilai_total'] : 0;

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $s['nisn']);
            $sheet->setCellValue('C' . $row, $s['nama_lengkap']);
            $sheet->setCellValue('D' . $row, $statusText);
            $sheet->setCellValue('E' . $row, number_format($nilaiPg, 2));
            $sheet->setCellValue('F' . $row, number_format($nilaiPgKompleks, 2));
            $sheet->setCellValue('G' . $row, number_format($nilaiBenarSalah, 2));
            $sheet->setCellValue('H' . $row, number_format($nilaiEsai, 2));
            $sheet->setCellValue('I' . $row, number_format($nilaiTotal, 2));
            
            $sheet->getStyle('A' . $row . ':B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row . ':I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        $lastRow = $row - 1;
        $sheet->getStyle('A6:I' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $filename = 'Hasil_Ujian_' . str_replace(' ', '_', $jadwal['nama_mapel']) . '_' . $jadwal['nama_sekolah'];
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
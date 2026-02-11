<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class PembuatSoal extends BaseController
{
    protected $guruModel;
    protected $db;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $users = $this->guruModel->select('guru.*, sekolah.nama_sekolah')
            ->join('sekolah', 'sekolah.id = guru.sekolah_id', 'left')
            ->findAll();

        $data = [
            'title' => 'Data Pembuat Soal',
            'users' => $users
        ];
        return view('admin/pembuat_soal/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Pembuat Soal',
            'sekolah' => $this->db->table('sekolah')->get()->getResultArray(),
            'mapel' => $this->db->table('mapel')->get()->getResultArray(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/pembuat_soal/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nama_lengkap' => 'required',
            'username' => 'required|is_unique[guru.username]',
            'password' => 'required|min_length[6]',
            'sekolah_id' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fotoName = 'default.jpg';
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $fotoName = $fileFoto->getRandomName();
            $fileFoto->move('uploads/profil', $fotoName);
        }

        $dataUser = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'sekolah_id' => $this->request->getPost('sekolah_id'),
            'foto' => $fotoName
        ];

        $this->guruModel->insert($dataUser);
        $guruId = $this->guruModel->getInsertID();

        $mapelIds = $this->request->getPost('mapel_id');
        if ($mapelIds) {
            $dataMapel = [];
            foreach ($mapelIds as $mid) {
                $dataMapel[] = ['guru_id' => $guruId, 'mapel_id' => $mid];
            }
            $this->db->table('guru_mapel')->insertBatch($dataMapel);
        }

        return redirect()->to('admin/pembuat_soal')->with('success', 'Pembuat Soal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->guruModel->find($id);
        if (!$user) {
            return redirect()->to('admin/pembuat_soal')->with('error', 'Data tidak ditemukan.');
        }

        $guruMapel = $this->db->table('guru_mapel')->where('guru_id', $id)->get()->getResultArray();

        $data = [
            'title' => 'Edit Pembuat Soal',
            'user' => $user,
            'sekolah' => $this->db->table('sekolah')->get()->getResultArray(),
            'mapel' => $this->db->table('mapel')->get()->getResultArray(),
            'selected_mapel' => array_column($guruMapel, 'mapel_id'),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/pembuat_soal/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_lengkap' => 'required',
            'username' => "required|is_unique[guru.username,id,$id]",
            'sekolah_id' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $this->guruModel->find($id);
        $fotoName = $user['foto'];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $fotoName = $fileFoto->getRandomName();
            $fileFoto->move('uploads/profil', $fotoName);
            if ($user['foto'] != 'default.jpg' && file_exists('uploads/profil/' . $user['foto'])) {
                unlink('uploads/profil/' . $user['foto']);
            }
        }

        $dataUpdate = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'sekolah_id' => $this->request->getPost('sekolah_id'),
            'foto' => $fotoName
        ];

        if ($this->request->getPost('password')) {
            $dataUpdate['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->guruModel->update($id, $dataUpdate);

        $this->db->table('guru_mapel')->where('guru_id', $id)->delete();
        $mapelIds = $this->request->getPost('mapel_id');
        if ($mapelIds) {
            $dataMapel = [];
            foreach ($mapelIds as $mid) {
                $dataMapel[] = ['guru_id' => $id, 'mapel_id' => $mid];
            }
            $this->db->table('guru_mapel')->insertBatch($dataMapel);
        }

        return redirect()->to('admin/pembuat_soal')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $user = $this->guruModel->find($id);
        if ($user['foto'] != 'default.jpg' && file_exists('uploads/profil/' . $user['foto'])) {
            unlink('uploads/profil/' . $user['foto']);
        }
        $this->guruModel->delete($id);
        return redirect()->to('admin/pembuat_soal')->with('success', 'Data berhasil dihapus.');
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = $file->getClientExtension();
            
            if (!in_array($ext, ['xls', 'xlsx'])) {
                return redirect()->back()->with('error', 'Format file harus .xls atau .xlsx');
            }

            if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
                return redirect()->back()->with('error', 'Library PhpSpreadsheet belum terinstall.');
            }

            try {
                $spreadsheet = IOFactory::load($file->getTempName());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                $insertData = [];
                $skippedRows = 0;
                
                $allSekolah = $this->db->table('sekolah')->get()->getResultArray();
                $sekolahMap = [];
                foreach ($allSekolah as $s) {
                    $sekolahMap[strtolower(trim($s['nama_sekolah']))] = $s['id'];
                }

                foreach ($data as $key => $row) {
                    if ($key == 0) continue; 

                    if (empty($row[0]) || empty($row[1])) {
                        $skippedRows++;
                        continue;
                    }

                    $namaSekolahInput = isset($row[3]) ? strtolower(trim($row[3])) : '';
                    $sekolahId = $sekolahMap[$namaSekolahInput] ?? null;

                    if (!$sekolahId) {
                        $skippedRows++;
                        continue;
                    }

                    $insertData[] = [
                        'nama_lengkap' => $row[0],
                        'username'     => $row[1],
                        'password'     => password_hash((string)($row[2] ?? '123456'), PASSWORD_DEFAULT),
                        'sekolah_id'   => $sekolahId,
                        'foto'         => 'default.jpg'
                    ];
                }

                if (!empty($insertData)) {
                    $this->guruModel->ignore(true)->insertBatch($insertData);
                    
                    $msg = count($insertData) . ' Data pembuat soal berhasil diimport.';
                    if ($skippedRows > 0) {
                        $msg .= ' (' . $skippedRows . ' data dilewati karena format salah atau sekolah tidak ditemukan).';
                    }
                    return redirect()->to('admin/pembuat_soal')->with('success', $msg);
                }

                return redirect()->back()->with('error', 'Tidak ada data valid yang dapat dibaca. Pastikan Nama Sekolah sesuai.');

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Gagal mengupload file.');
    }

    public function downloadTemplate()
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            return redirect()->back()->with('error', 'Library PhpSpreadsheet belum terinstall.');
        }

        $spreadsheet = new Spreadsheet();
        
        $dataSekolah = $this->db->table('sekolah')->get()->getResultArray();
        
        if (empty($dataSekolah)) {
            return redirect()->back()->with('error', 'Buat data sekolah terlebih dahulu.');
        }

        $sheetOptions = $spreadsheet->createSheet();
        $sheetOptions->setTitle('DataSekolahHidden');
        $sheetOptions->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

        $rowCount = 1;
        foreach ($dataSekolah as $s) {
            $cleanName = trim($s['nama_sekolah']); 
            $sheetOptions->setCellValue('A' . $rowCount, $cleanName);
            $rowCount++;
        }
        
        $lastRow = $rowCount - 1;
        $formulaList = "DataSekolahHidden!$" . "A$1:$" . "A$" . $lastRow;

        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import');

        $sheet->setCellValue('A1', 'Nama Lengkap (Wajib)');
        $sheet->setCellValue('B1', 'Username (Wajib & Unik)');
        $sheet->setCellValue('C1', 'Password (Default: 123456)');
        $sheet->setCellValue('D1', 'Nama Sekolah (Pilih dari List)');

        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFFF00'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

        for ($i = 2; $i <= 100; $i++) {
            $validation = $sheet->getCell("D$i")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1($formulaList);
        }

        $sheet->setCellValue('A2', 'Budi Santoso');
        $sheet->setCellValue('B2', 'budi01');
        $sheet->setCellValue('C2', '123456');
        $sheet->setCellValue('D2', $dataSekolah[0]['nama_sekolah'] ?? '');

        foreach(range('A','D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_pembuat_soal.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
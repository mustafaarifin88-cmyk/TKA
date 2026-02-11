<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\SekolahModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class Siswa extends BaseController
{
    protected $siswaModel;
    protected $sekolahModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->sekolahModel = new SekolahModel();
    }

    public function index()
    {
        $siswa = $this->siswaModel->select('siswa.*, sekolah.nama_sekolah')
            ->join('sekolah', 'sekolah.id = siswa.sekolah_id')
            ->findAll();

        $data = [
            'title' => 'Data Siswa',
            'siswa' => $siswa
        ];
        return view('admin/siswa/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Siswa',
            'sekolah' => $this->sekolahModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/siswa/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nisn' => 'required|is_unique[siswa.nisn]',
            'nama_lengkap' => 'required',
            'sekolah_id' => 'required',
            'username' => 'required|is_unique[siswa.username]',
            'password' => 'required|min_length[6]',
            'foto' => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fotoName = 'default.jpg';
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $fotoName = $fileFoto->getRandomName();
            $fileFoto->move('uploads/profil', $fotoName);
        }

        $this->siswaModel->save([
            'nisn' => $this->request->getPost('nisn'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'sekolah_id' => $this->request->getPost('sekolah_id'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'foto' => $fotoName
        ]);

        return redirect()->to('admin/siswa')->with('success', 'Data Siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Siswa',
            'siswa' => $this->siswaModel->find($id),
            'sekolah' => $this->sekolahModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/siswa/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nisn' => "required|is_unique[siswa.nisn,id,$id]",
            'nama_lengkap' => 'required',
            'sekolah_id' => 'required',
            'username' => "required|is_unique[siswa.username,id,$id]",
            'foto' => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $siswa = $this->siswaModel->find($id);
        $fotoName = $siswa['foto'];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $fotoName = $fileFoto->getRandomName();
            $fileFoto->move('uploads/profil', $fotoName);
            if ($siswa['foto'] != 'default.jpg' && file_exists('uploads/profil/' . $siswa['foto'])) {
                unlink('uploads/profil/' . $siswa['foto']);
            }
        }

        $dataUpdate = [
            'nisn' => $this->request->getPost('nisn'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'sekolah_id' => $this->request->getPost('sekolah_id'),
            'username' => $this->request->getPost('username'),
            'foto' => $fotoName
        ];

        if ($this->request->getPost('password')) {
            $dataUpdate['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->siswaModel->update($id, $dataUpdate);

        return redirect()->to('admin/siswa')->with('success', 'Data Siswa berhasil diperbarui.');
    }

    public function delete($id)
    {
        $siswa = $this->siswaModel->find($id);
        if ($siswa['foto'] != 'default.jpg' && file_exists('uploads/profil/' . $siswa['foto'])) {
            unlink('uploads/profil/' . $siswa['foto']);
        }
        $this->siswaModel->delete($id);
        return redirect()->to('admin/siswa')->with('success', 'Data Siswa berhasil dihapus.');
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
                
                $allSekolah = $this->sekolahModel->findAll();
                $sekolahMap = [];
                foreach ($allSekolah as $s) {
                    $sekolahMap[strtolower(trim($s['nama_sekolah']))] = $s['id'];
                }

                foreach ($data as $key => $row) {
                    if ($key == 0) continue;

                    if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
                        $skippedRows++;
                        continue;
                    }

                    $namaSekolahInput = strtolower(trim($row[2]));
                    $sekolahId = $sekolahMap[$namaSekolahInput] ?? null;

                    if (!$sekolahId) {
                        $skippedRows++;
                        continue;
                    }

                    $insertData[] = [
                        'nisn'         => $row[0],
                        'nama_lengkap' => $row[1],
                        'sekolah_id'   => $sekolahId,
                        'username'     => $row[3],
                        'password'     => password_hash((string)($row[4] ?? '123456'), PASSWORD_DEFAULT),
                        'foto'         => 'default.jpg',
                    ];
                }

                if (!empty($insertData)) {
                    $this->siswaModel->ignore(true)->insertBatch($insertData);
                    
                    $msg = count($insertData) . ' Data siswa berhasil diimport.';
                    if ($skippedRows > 0) {
                        $msg .= ' (' . $skippedRows . ' data dilewati karena format salah atau sekolah tidak ditemukan).';
                    }
                    return redirect()->to('admin/siswa')->with('success', $msg);
                }

                return redirect()->back()->with('error', 'Tidak ada data valid yang dapat dibaca. Pastikan Nama Sekolah sesuai dengan data di sistem.');

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
        
        $dataSekolah = $this->sekolahModel->findAll();
        
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

        $sheet->setCellValue('A1', 'NISN (Wajib & Unik)');
        $sheet->setCellValue('B1', 'Nama Lengkap (Wajib)');
        $sheet->setCellValue('C1', 'Nama Sekolah (Pilih dari List)');
        $sheet->setCellValue('D1', 'Username (Wajib & Unik)');
        $sheet->setCellValue('E1', 'Password');

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
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        for ($i = 2; $i <= 1000; $i++) {
            $validation = $sheet->getCell("C$i")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1($formulaList);
        }

        $sheet->setCellValue('A2', '0012345678');
        $sheet->setCellValue('B2', 'Ahmad Siswa');
        $sheet->setCellValue('C2', $dataSekolah[0]['nama_sekolah'] ?? ''); 
        $sheet->setCellValue('D2', 'ahmad01');
        $sheet->setCellValue('E2', '123456');

        foreach(range('A','E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_siswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
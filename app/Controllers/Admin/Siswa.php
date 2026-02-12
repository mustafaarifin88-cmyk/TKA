<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\SekolahModel;
use App\Models\InstansiModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class Siswa extends BaseController
{
    protected $siswaModel;
    protected $sekolahModel;
    protected $instansiModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->sekolahModel = new SekolahModel();
        $this->instansiModel = new InstansiModel();
    }

    public function index()
    {
        $sekolahId = $this->request->getGet('sekolah_id');
        
        // Join tabel sekolah untuk menampilkan nama sekolah di tabel
        $builder = $this->siswaModel->select('siswa.*, sekolah.nama_sekolah')
            ->join('sekolah', 'sekolah.id = siswa.sekolah_id', 'left');

        if ($sekolahId) {
            $builder->where('siswa.sekolah_id', $sekolahId);
        }

        $data = [
            'title' => 'Data Siswa',
            'siswa' => $builder->orderBy('siswa.id', 'DESC')->findAll(),
            'sekolah' => $this->sekolahModel->orderBy('nama_sekolah', 'ASC')->findAll(),
            'selected_sekolah' => $sekolahId
        ];
        return view('admin/siswa/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Siswa',
            'sekolah' => $this->sekolahModel->orderBy('nama_sekolah', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/siswa/create', $data);
    }

    public function store()
    {
        // Validasi input
        if (!$this->validate([
            'nisn' => 'required|is_unique[siswa.nisn]',
            'nama_lengkap' => 'required',
            'sekolah_id' => 'required',
            'tanggal_lahir' => 'required|valid_date',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'foto' => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload Foto
        $fileFoto = $this->request->getFile('foto');
        $namaFoto = 'default.jpg';
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/profil', $namaFoto);
        }

        // 1. Simpan data awal dengan Username Temporary
        $data = [
            'nisn' => $this->request->getPost('nisn'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'sekolah_id' => $this->request->getPost('sekolah_id'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'username' => 'TEMP-' . uniqid(), 
            'password' => password_hash('123456', PASSWORD_DEFAULT), // Default Password
            'foto' => $namaFoto
        ];

        $this->siswaModel->save($data);
        $insertId = $this->siswaModel->getInsertID();

        // 2. Update Username sesuai format: TKA + 7 Digit ID (Contoh: TKA0000001)
        $newUsername = 'TKA' . sprintf('%07d', $insertId);
        $this->siswaModel->update($insertId, ['username' => $newUsername]);

        return redirect()->to('admin/siswa')->with('success', 'Data siswa berhasil ditambahkan. Username otomatis: ' . $newUsername);
    }

    public function edit($id)
    {
        $siswa = $this->siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('admin/siswa')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Siswa',
            'siswa' => $siswa,
            'sekolah' => $this->sekolahModel->orderBy('nama_sekolah', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/siswa/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nisn' => "required|is_unique[siswa.nisn,id,$id]",
            'nama_lengkap' => 'required',
            'sekolah_id' => 'required',
            'tanggal_lahir' => 'required|valid_date',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'foto' => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ];

        // Validasi password hanya jika diisi
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $siswa = $this->siswaModel->find($id);
        $data = [
            'nisn' => $this->request->getPost('nisn'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'sekolah_id' => $this->request->getPost('sekolah_id'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
        ];

        // Update password hanya jika diisi admin
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Handle Upload Foto Baru
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/profil', $namaFoto);
            
            // Hapus foto lama jika bukan default
            if ($siswa['foto'] != 'default.jpg' && file_exists('uploads/profil/' . $siswa['foto'])) {
                unlink('uploads/profil/' . $siswa['foto']);
            }
            $data['foto'] = $namaFoto;
        }

        $this->siswaModel->update($id, $data);
        return redirect()->to('admin/siswa')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function delete($id)
    {
        $siswa = $this->siswaModel->find($id);
        if ($siswa) {
            if ($siswa['foto'] != 'default.jpg' && file_exists('uploads/profil/' . $siswa['foto'])) {
                unlink('uploads/profil/' . $siswa['foto']);
            }
            $this->siswaModel->delete($id);
        }
        return redirect()->to('admin/siswa')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function exportPdf()
    {
        $sekolahId = $this->request->getGet('sekolah_id');
        if (!$sekolahId) {
            return redirect()->to('admin/siswa')->with('error', 'Silakan pilih filter sekolah terlebih dahulu untuk mencetak PDF.');
        }

        // Ambil data instansi (profil aplikasi)
        $instansi = $this->instansiModel->first(); 
        if (!$instansi) $instansi = [];

        // Ambil data sekolah yang difilter
        $sekolah = $this->sekolahModel->find($sekolahId);
        
        // Ambil data siswa
        $siswa = $this->siswaModel->where('sekolah_id', $sekolahId)
            ->orderBy('nama_lengkap', 'ASC')
            ->findAll();

        $data = [
            'instansi' => $instansi,
            'sekolah' => $sekolah,
            'siswa' => $siswa
        ];

        $html = view('admin/siswa/cetak_pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Penting untuk load gambar/logo
        $options->set('defaultFont', 'Helvetica');
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'Data_Siswa_' . ($sekolah['nama_sekolah'] ?? 'Sekolah') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => false]);
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = $file->getClientExtension();
            if ($ext == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            $successCount = 0;
            foreach ($data as $key => $row) {
                if ($key == 0) continue; // Skip Header
                
                $nisn = $row[0] ?? null;
                $nama = $row[1] ?? null;
                $sekolahName = $row[2] ?? null;
                
                if ($nisn && $nama && $sekolahName) {
                    $sekolah = $this->sekolahModel->where('nama_sekolah', $sekolahName)->first();
                    if ($sekolah) {
                        if ($this->siswaModel->where('nisn', $nisn)->countAllResults() == 0) {
                            // Insert Data
                            $this->siswaModel->save([
                                'nisn' => $nisn,
                                'nama_lengkap' => $nama,
                                'sekolah_id' => $sekolah['id'],
                                'username' => 'TEMP-' . uniqid(),
                                'password' => password_hash('123456', PASSWORD_DEFAULT),
                                'foto' => 'default.jpg',
                                'jenis_kelamin' => 'L', // Default
                                'tanggal_lahir' => null
                            ]);
                            
                            // Generate Username
                            $insertId = $this->siswaModel->getInsertID();
                            $newUsername = 'TKA' . sprintf('%07d', $insertId);
                            $this->siswaModel->update($insertId, ['username' => $newUsername]);
                            
                            $successCount++;
                        }
                    }
                }
            }
            return redirect()->to('admin/siswa')->with('success', "$successCount data berhasil diimport.");
        }
        return redirect()->back()->with('error', 'File tidak valid.');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Siswa');

        $sheet->setCellValue('A1', 'NISN (Wajib & Unik)');
        $sheet->setCellValue('B1', 'Nama Lengkap (Wajib)');
        $sheet->setCellValue('C1', 'Nama Sekolah (Wajib Sesuai Sistem)');

        $sekolah = $this->sekolahModel->findAll();
        $sekolahNames = array_column($sekolah, 'nama_sekolah');
        $formulaList = '"' . implode(',', $sekolahNames) . '"';

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

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_siswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}
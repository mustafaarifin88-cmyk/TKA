<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\SekolahModel;
use App\Models\JadwalUjianModel;

class Dashboard extends BaseController
{
    protected $siswaModel;
    protected $sekolahModel;
    protected $jadwalModel;
    protected $db;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->sekolahModel = new SekolahModel();
        $this->jadwalModel = new JadwalUjianModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $siswaId = session()->get('id');
        $siswa = $this->siswaModel->find($siswaId);

        if (!$siswa) {
            return redirect()->to('login/siswa');
        }

        // Ambil data sekolah
        $sekolah = $this->sekolahModel->find($siswa['sekolah_id']);

        // Ambil daftar mapel yang terdaftar di sekolah ini
        // (Bisa dari tabel sekolah_mapel atau mapel yang ada jadwalnya)
        // Kita ambil dari sekolah_mapel agar dropdown lengkap
        $mapel = $this->db->table('sekolah_mapel')
            ->select('mapel.id, mapel.nama_mapel')
            ->join('mapel', 'mapel.id = sekolah_mapel.mapel_id')
            ->where('sekolah_mapel.sekolah_id', $siswa['sekolah_id'])
            ->orderBy('mapel.nama_mapel', 'ASC')
            ->get()->getResultArray();

        $data = [
            'title' => 'Konfirmasi Data Peserta',
            'siswa' => $siswa,
            'sekolah' => $sekolah,
            'mapel' => $mapel
        ];

        return view('siswa/dashboard', $data);
    }

    // Dipanggil via AJAX saat siswa memilih mapel atau menekan tombol cek
    public function cekKonfirmasi()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $siswaId = session()->get('id');
        $inputTglLahir = $this->request->getPost('tanggal_lahir');
        $mapelId = $this->request->getPost('mapel_id');

        $siswa = $this->siswaModel->find($siswaId);

        // 1. Validasi Tanggal Lahir
        if ($inputTglLahir != $siswa['tanggal_lahir']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tanggal lahir tidak sesuai dengan data sistem.'
            ]);
        }

        // 2. Cek Jadwal Ujian Hari Ini
        $today = date('Y-m-d');
        $jamSekarang = date('H:i:s');

        $jadwal = $this->jadwalModel
            ->where('sekolah_id', $siswa['sekolah_id'])
            ->where('mapel_id', $mapelId)
            ->where('tanggal_ujian', $today)
            ->where('status', 'aktif') // Pastikan status jadwal aktif
            ->first();

        if (!$jadwal) {
            return $this->response->setJSON([
                'status' => 'no_schedule',
                'message' => 'Jadwal Belum Disetting Admin'
            ]);
        }

        // Cek apakah siswa sudah selesai ujian ini sebelumnya
        $statusUjian = $this->db->table('status_ujian_siswa')
            ->where('jadwal_id', $jadwal['id'])
            ->where('siswa_id', $siswaId)
            ->get()->getRowArray();

        if ($statusUjian && $statusUjian['status'] == 'selesai') {
            return $this->response->setJSON([
                'status' => 'finished',
                'message' => 'Anda sudah menyelesaikan ujian ini.'
            ]);
        }

        // 3. Logika Waktu
        $jamMulai = $jadwal['jam_mulai'];
        
        // Hitung jam selesai berdasarkan durasi (opsional jika ingin membatasi telat masuk)
        // $jamSelesai = date('H:i:s', strtotime($jamMulai) + ($jadwal['lama_ujian'] * 60));

        if ($jamSekarang < $jamMulai) {
            // Belum mulai (Hitung Mundur)
            return $this->response->setJSON([
                'status' => 'countdown',
                'waktu_mulai' => $today . ' ' . $jamMulai, // Format YYYY-MM-DD HH:mm:ss untuk JS Date
                'message' => 'Ujian belum dimulai.'
            ]);
        } else {
            // Sudah mulai, Boleh Masuk
            
            // Set session jadwal_id agar aman di controller Ujian
            session()->set('jadwal_id_aktif', $jadwal['id']);

            return $this->response->setJSON([
                'status' => 'ready',
                'message' => 'Silakan mulai ujian.',
                'redirect_url' => base_url('siswa/ujian') // Redirect ke controller ujian
            ]);
        }
    }
}
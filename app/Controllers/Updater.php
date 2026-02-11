<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use ZipArchive;

class Updater extends BaseController
{
    protected $updateConfig;
    protected $db;

    public function __construct()
    {
        $this->updateConfig = new \Config\Updater();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // 1. Ambil versi lokal dari database
        $currentVersion = $this->db->table('app_version')->orderBy('id', 'DESC')->get()->getRow()->version;

        // 2. Ambil data dari Server (JSON)
        // Gunakan cURL atau file_get_contents
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get($this->updateConfig->updateUrl);
            $serverData = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $serverData = null; // Server mati/tidak ketemu
        }

        $data = [
            'title' => 'Cek Pembaruan Sistem',
            'current_version' => $currentVersion,
            'server_data' => $serverData,
            'has_update' => false
        ];

        // 3. Bandingkan Versi
        if ($serverData && version_compare($serverData['latest_version'], $currentVersion, '>')) {
            $data['has_update'] = true;
        }

        return view('admin/updater/index', $data);
    }

    public function process()
    {
        // Validasi input
        $downloadUrl = $this->request->getPost('download_url');
        $newVersion = $this->request->getPost('new_version');

        if (!$downloadUrl || !$newVersion) {
            return redirect()->back()->with('error', 'Data update tidak valid.');
        }

        // 1. Download File ZIP ke folder writable/uploads
        $zipFile = WRITEPATH . 'uploads/update.zip';
        $fileContent = file_get_contents($downloadUrl);
        
        if ($fileContent === false) {
            return redirect()->back()->with('error', 'Gagal mendownload file update dari server.');
        }
        file_put_contents($zipFile, $fileContent);

        // 2. Ekstrak ZIP (Menimpa file asli di ROOTPATH)
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            // ROOTPATH adalah root folder project CI4 Anda
            $zip->extractTo(ROOTPATH);
            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal mengekstrak file update.');
        }

        // 3. Jalankan Migrasi Database (Jika ada perubahan struktur DB di update baru)
        try {
            $migrate = \Config\Services::migrations();
            $migrate->latest(); // Jalankan 'spark migrate' secara programmatically
        } catch (\Throwable $e) {
            // Log error tapi jangan hentikan proses jika hanya error migrasi kecil
            log_message('error', 'Migration failed: ' . $e->getMessage());
        }

        // 4. Update Versi di Database
        $this->db->table('app_version')->update(['version' => $newVersion]);

        // 5. Hapus file ZIP sementara
        @unlink($zipFile);

        return redirect()->to('/admin/updater')->with('success', 'Sistem berhasil diperbarui ke versi ' . $newVersion);
    }
}
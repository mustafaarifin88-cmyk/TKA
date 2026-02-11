<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use ZipArchive;

class Updater extends BaseController
{
    protected $updateConfig;
    protected $db;
    protected $statusFile;

    public function __construct()
    {
        $this->updateConfig = new \Config\Updater();
        $this->db = \Config\Database::connect();
        $this->statusFile = WRITEPATH . 'uploads/update_status.json';
    }

    public function index()
    {
        if (file_exists($this->statusFile)) @unlink($this->statusFile);

        $currentVersion = '1.0.0';
        $checkTabel = $this->db->table('information_schema.tables')->where('table_name', 'app_version')->countAllResults();
        
        if ($checkTabel > 0) {
            $query = $this->db->table('app_version')->orderBy('id', 'DESC')->get()->getRow();
            if ($query) $currentVersion = $query->version;
        }

        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get($this->updateConfig->updateUrl, ['timeout' => 5]);
            $serverData = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $serverData = null;
        }

        $data = [
            'title' => 'Cek Pembaruan Sistem',
            'current_version' => $currentVersion,
            'server_data' => $serverData,
            'has_update' => false
        ];

        if ($serverData && version_compare($serverData['latest_version'], $currentVersion, '>')) {
            $data['has_update'] = true;
        }

        return view('admin/updater/index', $data);
    }

    public function init()
    {
        $url = $this->request->getGet('url');
        if (!$url) return $this->response->setJSON(['status' => 'error', 'message' => 'URL Invalid']);

        $this->writeStatus('processing', 10, 'Menghubungi server update...');

        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '1024M');

        $zipPath = WRITEPATH . 'uploads/';
        if (!is_dir($zipPath)) mkdir($zipPath, 0777, true);
        $zipFile = $zipPath . 'update.zip';

        $fp = fopen($zipFile, 'w+');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_FILE, $fp); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        session_write_close();

        $this->writeStatus('processing', 30, 'Sedang mendownload file (Mohon tunggu)...');
        
        $success = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        fclose($fp);

        if (!$success) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal download: ' . $error]);
        }

        $this->writeStatus('downloaded', 70, 'Download selesai. Menyiapkan ekstraksi...');
        return $this->response->setJSON(['status' => 'success']);
    }

    public function extract()
    {
        $newVersion = $this->request->getGet('version');
        $zipFile = WRITEPATH . 'uploads/update.zip';

        $this->writeStatus('processing', 80, 'Mengekstrak file sistem...');

        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            if ($zip->extractTo(ROOTPATH)) {
                $zip->close();
                
                try {
                    $this->writeStatus('processing', 90, 'Memperbarui database...');
                    $migrate = \Config\Services::migrations();
                    $migrate->latest();
                } catch (\Throwable $e) {
                }

                $exists = $this->db->table('app_version')->countAllResults();
                if($exists > 0) {
                    $this->db->table('app_version')->update(['version' => $newVersion, 'updated_at' => date('Y-m-d H:i:s')]);
                } else {
                    $this->db->table('app_version')->insert(['version' => $newVersion, 'updated_at' => date('Y-m-d H:i:s')]);
                }

                @unlink($zipFile);
                
                helper('filesystem');
                delete_files(WRITEPATH . 'cache', false, true);

                $this->writeStatus('completed', 100, 'Update Berhasil!');
                return $this->response->setJSON(['status' => 'completed']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengekstrak file (Permission Denied).']);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'File update korup/rusak.']);
    }

    public function status()
    {
        if (file_exists($this->statusFile)) {
            $data = json_decode(file_get_contents($this->statusFile), true);
            return $this->response->setJSON($data);
        }
        return $this->response->setJSON(['status' => 'waiting', 'percent' => 0, 'message' => 'Menunggu proses...']);
    }

    private function writeStatus($status, $percent, $msg)
    {
        file_put_contents($this->statusFile, json_encode([
            'status' => $status,
            'percent' => $percent,
            'message' => $msg
        ]));
    }
}
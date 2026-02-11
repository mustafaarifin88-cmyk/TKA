<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['form', 'url', 'session'];
    protected $userData = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();
        $db = \Config\Database::connect();

        // 1. GLOBAL DATA SEKOLAH (Untuk Favicon & Judul)
        // Ambil data sekolah (asumsi ID 1 adalah data utama)
        $sekolah = $db->table('sekolah')->where('id', 1)->get()->getRowArray();
        
        // Share data sekolah ke semua view
        // Jika belum ada data, set array kosong agar tidak error
        $dataGlobal = [
            'sekolah_data' => $sekolah ?? []
        ];

        // 2. GLOBAL DATA USER (Sidebar & Auth)
        if ($this->session->has('is_login')) {
            $role = $this->session->get('role');
            $id = $this->session->get('id');

            $query = null;

            if ($role == 'admin') {
                $query = $db->table('admin')->where('id', $id)->get()->getRowArray();
            } elseif ($role == 'guru') {
                $query = $db->table('guru')->where('id', $id)->get()->getRowArray();
            } elseif ($role == 'siswa') {
                $query = $db->table('siswa')->where('id', $id)->get()->getRowArray();
            }

            if ($query) {
                $this->userData = $query;
                $this->userData['role'] = $role;

                session()->set('foto', $query['foto']);
                session()->set('nama_lengkap', $query['nama_lengkap']);

                // Gabungkan data user ke data global
                $dataGlobal['active_user'] = $this->userData;
            }
        }

        // Render Data ke View
        \Config\Services::renderer()->setData($dataGlobal, 'raw');
    }
}
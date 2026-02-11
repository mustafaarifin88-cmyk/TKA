<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->get('/', 'Auth::login');
$routes->get('setup', 'Setup::index');

$routes->get('login', 'Auth::login');
$routes->post('auth/proses', 'Auth::prosesLogin');
$routes->get('login/siswa', 'Auth::loginSiswa');
$routes->post('auth/proses_siswa', 'Auth::prosesLoginSiswa');
$routes->get('logout', 'Auth::logout');

$routes->get('tutorial', 'Tutorial::index', ['filter' => 'auth']);

$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    $routes->get('profile', 'Admin\Profile::index');
    $routes->post('profile/update', 'Admin\Profile::update');
    
    $routes->get('instansi', 'Admin\Instansi::index');
    $routes->post('instansi/update', 'Admin\Instansi::update');

    $routes->get('updater', 'Admin\Updater::index');
    $routes->get('updater/init', 'Admin\Updater::init');
    $routes->get('updater/extract', 'Admin\Updater::extract');
    $routes->get('updater/status', 'Admin\Updater::status');

    $routes->get('mapel', 'Admin\Mapel::index');
    $routes->get('mapel/create', 'Admin\Mapel::create');
    $routes->post('mapel/store', 'Admin\Mapel::store');
    $routes->get('mapel/edit/(:num)', 'Admin\Mapel::edit/$1');
    $routes->put('mapel/update/(:num)', 'Admin\Mapel::update/$1');
    $routes->get('mapel/delete/(:num)', 'Admin\Mapel::delete/$1');

    $routes->get('sekolah', 'Admin\Sekolah::index');
    $routes->get('sekolah/create', 'Admin\Sekolah::create');
    $routes->post('sekolah/store', 'Admin\Sekolah::store');
    $routes->get('sekolah/edit/(:num)', 'Admin\Sekolah::edit/$1');
    $routes->post('sekolah/update/(:num)', 'Admin\Sekolah::update/$1');
    $routes->get('sekolah/delete/(:num)', 'Admin\Sekolah::delete/$1');
    
    $routes->get('pengaturan_sekolah', 'Admin\PengaturanSekolah::index');
    $routes->get('pengaturan_sekolah/manage/(:num)', 'Admin\PengaturanSekolah::manage/$1');
    $routes->post('pengaturan_sekolah/save', 'Admin\PengaturanSekolah::save');

    $routes->get('pembuat_soal', 'Admin\PembuatSoal::index');
    $routes->get('pembuat_soal/create', 'Admin\PembuatSoal::create');
    $routes->post('pembuat_soal/store', 'Admin\PembuatSoal::store');
    $routes->post('pembuat_soal/import', 'Admin\PembuatSoal::import'); 
    $routes->get('pembuat_soal/download_template', 'Admin\PembuatSoal::downloadTemplate'); 
    $routes->get('pembuat_soal/edit/(:num)', 'Admin\PembuatSoal::edit/$1');
    $routes->post('pembuat_soal/update/(:num)', 'Admin\PembuatSoal::update/$1'); 
    $routes->get('pembuat_soal/delete/(:num)', 'Admin\PembuatSoal::delete/$1');

    $routes->get('siswa', 'Admin\Siswa::index');
    $routes->get('siswa/create', 'Admin\Siswa::create');
    $routes->post('siswa/store', 'Admin\Siswa::store');
    $routes->post('siswa/import', 'Admin\Siswa::import'); 
    $routes->get('siswa/download_template', 'Admin\Siswa::downloadTemplate');
    $routes->get('siswa/edit/(:num)', 'Admin\Siswa::edit/$1');
    $routes->post('siswa/update/(:num)', 'Admin\Siswa::update/$1');
    $routes->get('siswa/delete/(:num)', 'Admin\Siswa::delete/$1');

    $routes->get('jadwal', 'Admin\JadwalUjian::index');
    $routes->get('jadwal/create', 'Admin\JadwalUjian::create');
    $routes->post('jadwal/store', 'Admin\JadwalUjian::store');
    $routes->get('jadwal/delete/(:num)', 'Admin\JadwalUjian::delete/$1');
    $routes->get('jadwal/getMapelBySekolah/(:num)', 'Admin\JadwalUjian::getMapelBySekolah/$1');

    $routes->get('bank_soal', 'Admin\BankSoal::index');
    $routes->get('bank_soal/mapel/(:num)', 'Admin\BankSoal::mapel/$1');
    $routes->get('bank_soal/list/(:num)/(:num)', 'Admin\BankSoal::list/$1/$2');
    $routes->get('bank_soal/edit/(:num)', 'Admin\BankSoal::edit/$1');
    $routes->post('bank_soal/update/(:num)', 'Admin\BankSoal::update/$1');
    $routes->get('bank_soal/delete/(:num)', 'Admin\BankSoal::delete/$1');

    $routes->get('hasil', 'Admin\HasilUjian::index');
    $routes->get('hasil/cetak/(:num)/(:num)', 'Admin\HasilUjian::cetak/$1/$2');
    $routes->get('hasil/export_excel/(:num)/(:num)', 'Admin\HasilUjian::exportExcel/$1/$2');
});

$routes->group('guru', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Guru\Dashboard::index');
    $routes->get('profile', 'Guru\Profile::index');
    $routes->post('profile/update', 'Guru\Profile::update');
    
    $routes->get('soal', 'Guru\BankSoal::index');
    $routes->get('soal/mapel/(:num)', 'Guru\BankSoal::mapel/$1'); 
    $routes->get('soal/jenis/(:num)/(:num)', 'Guru\BankSoal::jenis/$1/$2'); 
    $routes->get('soal/create/(:num)/(:num)/(:segment)', 'Guru\BankSoal::create/$1/$2/$3'); 
    $routes->post('soal/store', 'Guru\BankSoal::store'); 
    $routes->post('soal/salin', 'Guru\BankSoal::salin');
    $routes->get('soal/list/(:num)/(:num)/(:segment)', 'Guru\BankSoal::list/$1/$2/$3'); 
    $routes->get('soal/edit/(:num)', 'Guru\BankSoal::edit/$1'); 
    $routes->post('soal/update/(:num)', 'Guru\BankSoal::update/$1'); 
    $routes->get('soal/delete/(:num)', 'Guru\BankSoal::delete/$1');
    
    $routes->get('nilai', 'Guru\Nilai::index');
    $routes->get('nilai/detail/(:num)', 'Guru\Nilai::detail/$1');
    $routes->post('nilai/simpan_bobot', 'Guru\Nilai::simpanBobot');
    $routes->get('nilai/koreksi/(:num)/(:num)', 'Guru\Nilai::koreksi/$1/$2');
    $routes->post('nilai/simpan_koreksi', 'Guru\Nilai::simpanKoreksi');
    $routes->get('nilai/cetak/(:num)', 'Guru\Nilai::cetak/$1');
    $routes->get('nilai/export_excel/(:num)', 'Guru\Nilai::exportExcel/$1');
});

$routes->group('siswa', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Siswa\Dashboard::index');
    $routes->get('profile', 'Siswa\Profile::index');
    $routes->post('profile/update', 'Siswa\Profile::update');
    
    $routes->get('ujian', 'Siswa\Ujian::index');
    $routes->get('ujian/token/(:num)', 'Siswa\Ujian::token/$1');
    $routes->get('ujian/kerjakan/(:num)', 'Siswa\Ujian::kerjakan/$1');
    $routes->post('ujian/simpan_jawaban', 'Siswa\Ujian::simpan_jawaban');
    $routes->post('ujian/selesai_ujian', 'Siswa\Ujian::selesai_ujian');
});

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
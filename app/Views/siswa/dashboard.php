<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <h3>Dashboard Siswa</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="alert alert-primary">
                <h4 class="alert-heading">Selamat Datang, <?= session()->get('nama_lengkap'); ?>!</h4>
                <p>Selamat datang di sistem ujian online. Silakan cek menu Daftar Ujian untuk melihat jadwal ujian yang tersedia.</p>
            </div>
            
            <div class="row">
                <div class="col-12 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon blue">
                                        <i class="bi bi-calendar-event-fill"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Ujian Tersedia Hari Ini</h6>
                                    <h6 class="font-extrabold mb-0"><?= $ujian_aktif; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Ujian Telah Diselesaikan</h6>
                                    <h6 class="font-extrabold mb-0"><?= $ujian_selesai; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Aksi Cepat</h4>
                        </div>
                        <div class="card-body">
                            <a href="<?= base_url('siswa/ujian') ?>" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-pen-fill"></i> Lihat Daftar Ujian
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
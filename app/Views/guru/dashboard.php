<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <h3>Dashboard Pembuat Soal</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="alert alert-light-success color-success">
                <h4 class="alert-heading">Selamat Datang, <?= session()->get('nama_lengkap'); ?>!</h4>
                <p>Anda login sebagai Pembuat Soal. Tugas Anda adalah mengelola bank soal untuk mata pelajaran yang diampu.</p>
            </div>
            
            <div class="row">
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon purple">
                                        <i class="bi bi-book-half"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Mapel Diampu</h6>
                                    <h6 class="font-extrabold mb-0"><?= $total_mapel ?? 0 ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon blue">
                                        <i class="bi bi-collection-fill"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Total Soal</h6>
                                    <h6 class="font-extrabold mb-0"><?= $total_soal ?? 0 ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green">
                                        <i class="bi bi-building"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Unit Sekolah</h6>
                                    <h6 class="font-extrabold mb-0" style="font-size: 1rem;">
                                        <?= $sekolah_saya['nama_sekolah'] ?? '-' ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h5 class="card-title m-0">Aksi Cepat</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <a href="<?= base_url('guru/soal') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i> Buat Soal Baru
                                </a>
                                <a href="<?= base_url('guru/nilai') ?>" class="btn btn-success">
                                    <i class="bi bi-calculator me-2"></i> Rekap Nilai
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
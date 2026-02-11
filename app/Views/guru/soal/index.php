<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Bank Soal</h3>
                <p class="text-subtitle text-muted">Pilih kelas untuk mulai mengelola soal ujian.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bank Soal</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <?php if (empty($kelas)) : ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    <h4 class="alert-heading">Data Kosong</h4>
                    <p>Anda belum ditugaskan di kelas manapun. Silakan hubungi Administrator.</p>
                </div>
            </div>
        <?php else : ?>
            <?php foreach ($kelas as $k) : ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="<?= base_url('guru/soal/mapel/' . $k['id']) ?>" class="card-link text-decoration-none">
                        <div class="card text-center mb-3 shadow-sm" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="stats-icon blue mx-auto">
                                        <i class="bi bi-door-open-fill text-white"></i>
                                    </div>
                                </div>
                                <h5 class="card-title text-dark"><?= $k['nama_kelas'] ?></h5>
                                <p class="card-text text-muted small">Klik untuk memilih Mata Pelajaran</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
<?= $this->endSection(); ?>
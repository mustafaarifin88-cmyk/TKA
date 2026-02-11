<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pilih Mata Pelajaran</h3>
                <p class="text-subtitle text-muted">Kelas: <strong><?= $kelas['nama_kelas'] ?></strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/soal') ?>">Bank Soal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pilih Mapel</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <?php if (empty($mapel)) : ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    <h4 class="alert-heading">Data Kosong</h4>
                    <p>Tidak ada mata pelajaran yang ditugaskan kepada Anda di kelas ini.</p>
                </div>
            </div>
        <?php else : ?>
            <?php foreach ($mapel as $m) : ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="<?= base_url('guru/soal/jenis/' . $kelas['id'] . '/' . $m['id']) ?>" class="card-link text-decoration-none">
                        <div class="card text-center mb-3 shadow-sm" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="stats-icon purple mx-auto">
                                        <i class="bi bi-book-fill text-white"></i>
                                    </div>
                                </div>
                                <h5 class="card-title text-dark"><?= $m['nama_mapel'] ?></h5>
                                <p class="card-text text-muted small">Kelola Soal PG & Esai</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <div class="col-12 mt-3">
            <a href="<?= base_url('guru/soal') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Pilih Kelas
            </a>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
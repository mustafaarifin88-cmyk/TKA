<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pilih Mata Pelajaran</h3>
                <p class="text-subtitle text-muted">Pembuat Soal: <strong><?= $guru['nama_lengkap'] ?></strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/bank_soal') ?>">Bank Soal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pilih Mapel</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        
        <?php if (empty($mapel)) : ?>
            <div class="card shadow-sm border-0 text-center py-5">
                <div class="card-body">
                    <img src="<?= base_url('assets/static/images/samples/error-404.svg') ?>" alt="No Data" style="height: 150px; opacity: 0.5;">
                    <h5 class="mt-4 text-muted">Tidak Ada Mata Pelajaran</h5>
                    <p class="text-muted mb-3">User ini belum ditugaskan untuk mengampu mata pelajaran apapun.</p>
                    <a href="<?= base_url('admin/pembuat_soal') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="bi bi-gear-fill me-1"></i> Atur Mapel
                    </a>
                </div>
            </div>
        <?php else : ?>
            <div class="row">
                <?php foreach ($mapel as $m) : ?>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <a href="<?= base_url('admin/bank_soal/list/' . $guru['id'] . '/' . $m['id']) ?>" class="text-decoration-none">
                            <div class="card border-0 shadow-sm card-hover h-100">
                                <div class="card-body text-center p-4">
                                    <div class="avatar avatar-xl bg-light-primary text-primary mb-3 shadow-sm mx-auto">
                                        <i class="bi bi-book-half fs-2"></i>
                                    </div>
                                    <h5 class="card-title text-dark mb-1 text-truncate" title="<?= $m['nama_mapel'] ?>">
                                        <?= $m['nama_mapel'] ?>
                                    </h5>
                                    <p class="text-muted small mb-0">Klik untuk melihat soal</p>
                                </div>
                                <div class="card-footer bg-white border-0 pt-0 pb-4 text-center">
                                    <button class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                        Buka Bank Soal
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-12">
                <a href="<?= base_url('admin/bank_soal') ?>" class="btn btn-light-secondary shadow-sm px-4">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Pembuat Soal
                </a>
            </div>
        </div>

    </section>
</div>

<style>
    .card-hover {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        transform: translateY(0);
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .card-hover:hover .avatar {
        background-color: #435ebe !important;
        color: #fff !important;
        transform: scale(1.1);
        transition: all 0.3s ease;
    }
    .card-hover:hover .btn-outline-primary {
        background-color: #435ebe;
        color: #fff;
        border-color: #435ebe;
    }
</style>
<?= $this->endSection(); ?>
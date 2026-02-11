<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Bank Soal (Admin)</h3>
                <p class="text-subtitle text-muted">Pilih pembuat soal untuk melihat dan mengoreksi soal yang telah dibuat.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bank Soal</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        
        <?php if (empty($pembuat_soal)) : ?>
            <div class="card shadow-sm border-0 text-center py-5">
                <div class="card-body">
                    <img src="<?= base_url('assets/static/images/samples/error-404.svg') ?>" alt="No Data" style="height: 150px; opacity: 0.5;">
                    <h5 class="mt-4 text-muted">Data Pembuat Soal Kosong</h5>
                    <p class="text-muted mb-3">Belum ada user pembuat soal yang terdaftar.</p>
                    <a href="<?= base_url('admin/pembuat_soal/create') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pembuat Soal
                    </a>
                </div>
            </div>
        <?php else : ?>
            <div class="row">
                <?php foreach ($pembuat_soal as $p) : ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <a href="<?= base_url('admin/bank_soal/mapel/' . $p['id']) ?>" class="text-decoration-none">
                            <div class="card border-0 shadow-sm card-hover h-100">
                                <div class="card-body p-4 d-flex align-items-center">
                                    <div class="avatar avatar-xl me-3 shadow-sm">
                                        <img src="<?= base_url('uploads/profil/' . ($p['foto'] ? $p['foto'] : 'default.jpg')) ?>" 
                                             alt="Foto" 
                                             style="object-fit: cover; width: 100%; height: 100%;">
                                    </div>
                                    <div style="overflow: hidden;">
                                        <h6 class="text-muted font-semibold mb-1 text-xs">Pembuat Soal</h6>
                                        <h5 class="font-bold mb-0 text-dark text-truncate"><?= $p['nama_lengkap'] ?></h5>
                                        <small class="text-primary"><?= $p['username'] ?></small>
                                    </div>
                                    <div class="ms-auto text-primary opacity-50 icon-arrow">
                                        <i class="bi bi-chevron-right fs-4"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-light-primary border-0 py-2 px-4 text-center">
                                    <small class="text-primary fw-bold">Lihat Mapel <i class="bi bi-arrow-right-short"></i></small>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </section>
</div>

<style>
    .card-hover {
        transition: all 0.3s ease;
        transform: translateY(0);
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .card-hover:hover .icon-arrow {
        opacity: 1;
        transform: translateX(5px);
        transition: all 0.3s ease;
    }
    .card-hover:hover .card-footer {
        background-color: #435ebe !important;
    }
    .card-hover:hover .card-footer small {
        color: #fff !important;
    }
</style>
<?= $this->endSection(); ?>
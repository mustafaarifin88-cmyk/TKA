<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Kelola Bank Soal</h3>
                <p class="text-subtitle text-muted">
                    Kelas: <strong><?= $kelas['nama_kelas'] ?></strong> | 
                    Mapel: <strong><?= $mapel['nama_mapel'] ?></strong>
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <button type="button" class="btn btn-warning shadow-sm" data-bs-toggle="modal" data-bs-target="#modalSalin">
                    <i class="bi bi-copy me-2"></i> Salin Soal ke Kelas Lain
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <section class="row">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body py-4 d-flex flex-column">
                    <div class="mb-3">
                        <div class="stats-icon purple mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="bi bi-list-check text-white fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Pilihan Ganda</h5>
                    <p class="card-text text-muted mb-3 flex-grow-1">
                        Total Soal: <span class="badge bg-primary"><?= $jumlah_pg ?></span>
                    </p>
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('guru/soal/create/' . $kelas['id'] . '/' . $mapel['id'] . '/pg') ?>" class="btn btn-primary btn-sm rounded-pill">
                            <i class="bi bi-plus-lg"></i> Buat Baru
                        </a>
                        <a href="<?= base_url('guru/soal/list/' . $kelas['id'] . '/' . $mapel['id'] . '/pg') ?>" class="btn btn-outline-primary btn-sm rounded-pill">
                            <i class="bi bi-eye-fill"></i> Lihat Soal
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body py-4 d-flex flex-column">
                    <div class="mb-3">
                        <div class="stats-icon blue mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="bi bi-list-task text-white fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title">PG Kompleks</h5>
                    <p class="card-text text-muted mb-3 flex-grow-1">
                        Total Soal: <span class="badge bg-info"><?= $jumlah_pg_kompleks ?></span>
                    </p>
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('guru/soal/create/' . $kelas['id'] . '/' . $mapel['id'] . '/pg_kompleks') ?>" class="btn btn-info btn-sm rounded-pill text-white">
                            <i class="bi bi-plus-lg"></i> Buat Baru
                        </a>
                        <a href="<?= base_url('guru/soal/list/' . $kelas['id'] . '/' . $mapel['id'] . '/pg_kompleks') ?>" class="btn btn-outline-info btn-sm rounded-pill">
                            <i class="bi bi-eye-fill"></i> Lihat Soal
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body py-4 d-flex flex-column">
                    <div class="mb-3">
                        <div class="stats-icon green mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="bi bi-check-circle text-white fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Benar Salah</h5>
                    <p class="card-text text-muted mb-3 flex-grow-1">
                        Total Soal: <span class="badge bg-success"><?= $jumlah_benar_salah ?></span>
                    </p>
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('guru/soal/create/' . $kelas['id'] . '/' . $mapel['id'] . '/benar_salah') ?>" class="btn btn-success btn-sm rounded-pill text-white">
                            <i class="bi bi-plus-lg"></i> Buat Baru
                        </a>
                        <a href="<?= base_url('guru/soal/list/' . $kelas['id'] . '/' . $mapel['id'] . '/benar_salah') ?>" class="btn btn-outline-success btn-sm rounded-pill">
                            <i class="bi bi-eye-fill"></i> Lihat Soal
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body py-4 d-flex flex-column">
                    <div class="mb-3">
                        <div class="stats-icon red mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="bi bi-justify-left text-white fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title">Esai / Uraian</h5>
                    <p class="card-text text-muted mb-3 flex-grow-1">
                        Total Soal: <span class="badge bg-danger"><?= $jumlah_esai ?></span>
                    </p>
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('guru/soal/create/' . $kelas['id'] . '/' . $mapel['id'] . '/esai') ?>" class="btn btn-danger btn-sm rounded-pill text-white">
                            <i class="bi bi-plus-lg"></i> Buat Baru
                        </a>
                        <a href="<?= base_url('guru/soal/list/' . $kelas['id'] . '/' . $mapel['id'] . '/esai') ?>" class="btn btn-outline-danger btn-sm rounded-pill">
                            <i class="bi bi-eye-fill"></i> Lihat Soal
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 mt-4">
            <a href="<?= base_url('guru/soal/mapel/' . $kelas['id']) ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Pilih Mapel
            </a>
        </div>
    </section>
</div>

<div class="modal fade" id="modalSalin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="bi bi-copy me-2"></i> Salin Soal ke Kelas Lain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('guru/soal/salin') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="source_kelas_id" value="<?= $kelas['id'] ?>">
                <input type="hidden" name="source_mapel_id" value="<?= $mapel['id'] ?>">

                <div class="modal-body">
                    <p>
                        Anda akan menyalin semua soal (PG, PG Kompleks, Benar Salah, Esai) dari: <br>
                        <strong><?= $kelas['nama_kelas'] ?> - <?= $mapel['nama_mapel'] ?></strong>
                    </p>
                    
                    <div class="form-group mt-3">
                        <label class="form-label fw-bold">Pilih Kelas & Mapel Tujuan:</label>
                        <select name="target_kombinasi" class="form-select" required>
                            <option value="">-- Pilih Tujuan Salin --</option>
                            <?php if (!empty($target_salin)): ?>
                                <?php foreach ($target_salin as $t): ?>
                                    <option value="<?= $t['kelas_id'] . '-' . $t['mapel_id'] ?>">
                                        <?= $t['nama_kelas'] ?> - <?= $t['nama_mapel'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Tidak ada kelas lain yang tersedia.</option>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> Soal akan ditambahkan ke bank soal tujuan (tidak menimpa soal lama).
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning shadow">Mulai Salin</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
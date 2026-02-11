<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Sekolah</h3>
                <p class="text-subtitle text-muted">Input data sekolah baru ke dalam sistem.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/sekolah') ?>">Data Sekolah</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title m-0">Formulir Sekolah</h5>
            </div>
            <div class="card-body pt-4">
                
                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger alert-dismissible show fade">
                        <ul class="mb-0 ps-3">
                            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/sekolah/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Sekolah</label>
                            <input type="text" name="nama_sekolah" class="form-control" placeholder="Contoh: SDN 01 Pagi" value="<?= old('nama_sekolah') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NPSN</label>
                            <input type="text" name="npsn" class="form-control" placeholder="Nomor Pokok Sekolah Nasional" value="<?= old('npsn') ?>" required>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-bold">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" placeholder="Nama Kecamatan" value="<?= old('kecamatan') ?>" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('admin/sekolah') ?>" class="btn btn-light-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
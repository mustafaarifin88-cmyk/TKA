<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Atur Mapel Kelas</h3>
                <p class="text-subtitle text-muted">Centang mata pelajaran yang diajarkan di kelas <strong><?= $kelas['nama_kelas'] ?></strong>.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/pengaturan_kelas') ?>">Pengaturan Kelas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/pengaturan_kelas/save') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="kelas_id" value="<?= $kelas['id'] ?>">

                    <div class="row">
                        <?php foreach ($mapel as $m) : ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="mapel_id[]" value="<?= $m['id'] ?>" id="mapel_<?= $m['id'] ?>" 
                                    <?= in_array($m['id'], $assigned_mapel) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="mapel_<?= $m['id'] ?>">
                                        <?= $m['nama_mapel'] ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group text-end mt-4">
                        <a href="<?= base_url('admin/pengaturan_kelas') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
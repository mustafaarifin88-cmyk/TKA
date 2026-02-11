<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Profil Sekolah</h3>
                <p class="text-subtitle text-muted">Kelola identitas sekolah dan logo.</p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Logo Sekolah</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="avatar avatar-2xl mb-3" style="width: 150px; height: 150px;">
                            <?php if (!empty($sekolah['logo'])) : ?>
                                <img src="<?= base_url('uploads/sekolah/' . $sekolah['logo']) ?>" alt="Logo Sekolah" style="object-fit: contain; width: 100%; height: 100%;">
                            <?php else : ?>
                                <img src="<?= base_url('assets/compiled/jpg/building.jpg') ?>" alt="Default Logo">
                            <?php endif; ?>
                        </div>
                        <p class="text-center">Logo ini juga digunakan sebagai favicon.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Form Data Sekolah</h4>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= $error ?></li>
                                <?php endforeach ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/sekolah/update') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="form-group mb-3">
                            <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                            <input type="text" name="nama_sekolah" id="nama_sekolah" class="form-control" value="<?= old('nama_sekolah', $sekolah['nama_sekolah'] ?? '') ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="npsn" class="form-label">NPSN</label>
                            <input type="text" name="npsn" id="npsn" class="form-control" value="<?= old('npsn', $sekolah['npsn'] ?? '') ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap (Nama Jalan)</label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="3" required><?= old('alamat', $sekolah['alamat'] ?? '') ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kota" class="form-label">Kota/Kabupaten</label>
                                    <input type="text" name="kota" id="kota" class="form-control" value="<?= old('kota', $sekolah['kota'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kode_pos" class="form-label">Kode Pos</label>
                                    <input type="text" name="kode_pos" id="kode_pos" class="form-control" value="<?= old('kode_pos', $sekolah['kode_pos'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="logo" class="form-label">Upload Logo Baru</label>
                            <input type="file" name="logo" id="logo" class="form-control">
                            <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                        </div>

                        <div class="form-group text-end mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
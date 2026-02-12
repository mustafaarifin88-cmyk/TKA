<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Profile Saya</h3>
                <p class="text-subtitle text-muted">Kelola informasi akun Anda.</p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="avatar avatar-2xl">
                            <img src="<?= base_url('uploads/profil/' . ($active_user['foto'] ?? 'default.jpg')) ?>" alt="Avatar">
                        </div>

                        <h3 class="mt-3"><?= $active_user['nama_lengkap'] ?></h3>
                        <p class="text-small">Pembuat Soal</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card">
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

                    <form action="<?= base_url('guru/profile/update') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="form-group mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= old('nama_lengkap', $active_user['nama_lengkap']) ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" value="<?= $active_user['username'] ?>" disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password Baru (Opsional)</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Biarkan kosong jika tidak diganti">
                        </div>
                        <div class="form-group mb-3">
                            <label for="conf_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="conf_password" id="conf_password" class="form-control" placeholder="Ulangi password baru">
                        </div>
                        <div class="form-group mb-3">
                            <label for="foto" class="form-label">Foto Profil</label>
                            <input type="file" name="foto" id="foto" class="form-control">
                        </div>
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
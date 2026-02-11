<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Data Guru</h3>
                <p class="text-subtitle text-muted">Perbarui data guru, kelas ajar, dan mata pelajaran.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/guru') ?>">Data Guru</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
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

                <form action="<?= base_url('admin/guru/update/' . $guru['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" name="nip" id="nip" class="form-control" value="<?= old('nip', $guru['nip']) ?>" placeholder="Isi '-' jika tidak ada">
                            </div>
                            <div class="form-group mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= old('nama_lengkap', $guru['nama_lengkap']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" value="<?= old('username', $guru['username']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password Baru (Opsional)</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Biarkan kosong jika tidak diganti">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">Kelas yang Diajar</h6>
                            <div class="row">
                                <?php foreach ($kelas as $k) : ?>
                                    <div class="col-md-3 col-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="kelas_id[]" value="<?= $k['id'] ?>" id="kelas_<?= $k['id'] ?>"
                                            <?= in_array($k['id'], $selected_kelas) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="kelas_<?= $k['id'] ?>">
                                                <?= $k['nama_kelas'] ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">Mata Pelajaran yang Diampu</h6>
                            <div class="row">
                                <?php foreach ($mapel as $m) : ?>
                                    <div class="col-md-3 col-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="mapel_id[]" value="<?= $m['id'] ?>" id="mapel_<?= $m['id'] ?>"
                                            <?= in_array($m['id'], $selected_mapel) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="mapel_<?= $m['id'] ?>">
                                                <?= $m['nama_mapel'] ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-2">
                            <div class="avatar avatar-xl mb-2">
                                <img src="<?= base_url('uploads/profil/' . ($guru['foto'] ? $guru['foto'] : 'default.jpg')) ?>" alt="Foto Lama">
                            </div>
                            <small class="d-block text-muted">Foto Saat Ini</small>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group mb-3">
                                <label for="foto" class="form-label">Ganti Foto Profil (Opsional)</label>
                                <input type="file" name="foto" id="foto" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-end mt-3">
                        <a href="<?= base_url('admin/guru') ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
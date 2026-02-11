<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Data Siswa</h3>
                <p class="text-subtitle text-muted">Perbarui data siswa dan informasi login.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/siswa') ?>">Data Siswa</a></li>
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

                <form action="<?= base_url('admin/siswa/update/' . $siswa['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nisn" class="form-label">NISN</label>
                                <input type="text" name="nisn" id="nisn" class="form-control" value="<?= old('nisn', $siswa['nisn']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= old('nama_lengkap', $siswa['nama_lengkap']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="sekolah_id" class="form-label">Sekolah Asal</label>
                                <select name="sekolah_id" id="sekolah_id" class="form-select" required>
                                    <option value="">-- Pilih Sekolah --</option>
                                    <?php foreach ($sekolah as $s) : ?>
                                        <option value="<?= $s['id'] ?>" <?= (old('sekolah_id', $siswa['sekolah_id']) == $s['id']) ? 'selected' : '' ?>>
                                            <?= $s['nama_sekolah'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" value="<?= old('username', $siswa['username']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password Baru (Opsional)</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Biarkan kosong jika tidak diganti">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-2">
                            <div class="avatar avatar-xl mb-2">
                                <img src="<?= base_url('uploads/profil/' . ($siswa['foto'] ? $siswa['foto'] : 'default.jpg')) ?>" alt="Foto Lama">
                            </div>
                            <small class="d-block text-muted">Foto Saat Ini</small>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group mb-3">
                                <label for="foto" class="form-label">Ganti Foto Profil (Opsional)</label>
                                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-end mt-3">
                        <a href="<?= base_url('admin/siswa') ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
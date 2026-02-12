<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Data Siswa</h3>
                <p class="text-subtitle text-muted">Perbarui data diri atau reset password siswa.</p>
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
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/siswa/update/' . $siswa['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nisn" class="form-label fw-bold">NISN</label>
                                <input type="text" name="nisn" id="nisn" class="form-control" value="<?= old('nisn', $siswa['nisn']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= old('nama_lengkap', $siswa['nama_lengkap']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="sekolah_id" class="form-label fw-bold">Asal Sekolah</label>
                                <select name="sekolah_id" id="sekolah_id" class="form-select" required>
                                    <option value="">-- Pilih Sekolah --</option>
                                    <?php foreach ($sekolah as $s) : ?>
                                        <option value="<?= $s['id'] ?>" <?= old('sekolah_id', $siswa['sekolah_id']) == $s['id'] ? 'selected' : '' ?>>
                                            <?= $s['nama_sekolah'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Username Readonly -->
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Username (Otomatis)</label>
                                <input type="text" class="form-control bg-light" value="<?= $siswa['username'] ?>" readonly>
                                <small class="text-muted">Username digenerate oleh sistem dan tidak dapat diubah.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tanggal_lahir" class="form-label fw-bold">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="<?= old('tanggal_lahir', $siswa['tanggal_lahir']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                <div class="d-flex gap-4 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="L" <?= old('jenis_kelamin', $siswa['jenis_kelamin']) == 'L' ? 'checked' : '' ?> required>
                                        <label class="form-check-label" for="jk_l">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="P" <?= old('jenis_kelamin', $siswa['jenis_kelamin']) == 'P' ? 'checked' : '' ?> required>
                                        <label class="form-check-label" for="jk_p">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Edit Password Field -->
                            <div class="form-group mb-3 p-3 bg-light rounded border">
                                <label for="password" class="form-label fw-bold"><i class="bi bi-key-fill me-1"></i> Ganti Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Isi jika ingin mengubah password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengganti. Default: 123456</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="avatar avatar-xl mb-2">
                                <img src="<?= base_url('uploads/profil/' . ($siswa['foto'] ? $siswa['foto'] : 'default.jpg')) ?>" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;" alt="Foto Lama">
                            </div>
                            <small class="d-block text-muted">Foto Saat Ini</small>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group mb-3">
                                <label for="foto" class="form-label fw-bold">Update Foto Profil (Opsional)</label>
                                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-end mt-4">
                        <a href="<?= base_url('admin/siswa') ?>" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
</script>
<?= $this->endSection(); ?>
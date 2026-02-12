<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Siswa Baru</h3>
                <p class="text-subtitle text-muted">Input data siswa. Username & Password akan dibuat otomatis.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/siswa') ?>">Data Siswa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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

                <form action="<?= base_url('admin/siswa/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nisn" class="form-label fw-bold">NISN <span class="text-danger">*</span></label>
                                <input type="text" name="nisn" id="nisn" class="form-control" value="<?= old('nisn') ?>" placeholder="Nomor Induk Siswa Nasional" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= old('nama_lengkap') ?>" placeholder="Nama lengkap siswa" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="sekolah_id" class="form-label fw-bold">Asal Sekolah <span class="text-danger">*</span></label>
                                <select name="sekolah_id" id="sekolah_id" class="form-select" required>
                                    <option value="">-- Pilih Sekolah --</option>
                                    <?php foreach ($sekolah as $s) : ?>
                                        <option value="<?= $s['id'] ?>" <?= old('sekolah_id') == $s['id'] ? 'selected' : '' ?>>
                                            <?= $s['nama_sekolah'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tanggal_lahir" class="form-label fw-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="<?= old('tanggal_lahir') ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="d-flex gap-4 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="L" <?= old('jenis_kelamin') == 'L' ? 'checked' : '' ?> required>
                                        <label class="form-check-label" for="jk_l">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="P" <?= old('jenis_kelamin') == 'P' ? 'checked' : '' ?> required>
                                        <label class="form-check-label" for="jk_p">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="foto" class="form-label fw-bold">Foto Profil (Opsional)</label>
                                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-light-secondary mt-3">
                        <i class="bi bi-info-circle me-2"></i> Username akan dibuat otomatis (contoh: <b>TKA0000001</b>) dan Password default: <b>123456</b>.
                    </div>

                    <div class="form-group text-end mt-4">
                        <a href="<?= base_url('admin/siswa') ?>" class="btn btn-light-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Profil Instansi</h3>
                <p class="text-subtitle text-muted">Pengaturan identitas induk aplikasi (Kop Surat & Logo Utama).</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profil Instansi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title m-0"><i class="bi bi-building-gear me-2"></i> Edit Data Instansi</h5>
        </div>
        <div class="card-body pt-4">
            
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible show fade">
                    <i class="bi bi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/instansi/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-4 text-center mb-4 mb-md-0 border-end">
                        <label class="form-label fw-bold d-block mb-3">Logo Instansi</label>
                        <div class="avatar avatar-xl bg-light shadow-sm mb-3 position-relative" style="width: 180px; height: 180px; margin: 0 auto;">
                            <img src="<?= base_url('uploads/sekolah/' . ($instansi['logo'] ?? 'default_logo.png')) ?>" 
                                 alt="Logo" style="object-fit: contain; padding: 10px; width: 100%; height: 100%;" id="logoPreview">
                        </div>
                        <div class="px-4">
                            <input type="file" name="logo" class="form-control form-control-sm mt-2" accept="image/*" onchange="previewImage(this)">
                            <small class="text-muted d-block mt-2" style="font-size: 0.8rem;">
                                <i class="bi bi-info-circle"></i> Format: JPG/PNG, Max 2MB. Logo ini akan tampil di halaman login dan kop laporan.
                            </small>
                        </div>
                    </div>

                    <div class="col-md-8 ps-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Nama Instansi / Dinas</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-bank"></i></span>
                                <input type="text" name="nama_instansi" class="form-control" 
                                       value="<?= old('nama_instansi', $instansi['nama_instansi'] ?? '') ?>" 
                                       placeholder="Contoh: Dinas Pendidikan Kota Pekanbaru" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kota / Kabupaten</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" name="kota" class="form-control" 
                                           value="<?= old('kota', $instansi['kota'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kode Pos</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-postcard"></i></span>
                                    <input type="text" name="kode_pos" class="form-control" 
                                           value="<?= old('kode_pos', $instansi['kode_pos'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="4" required placeholder="Jl. ..."><?= old('alamat', $instansi['alamat'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="bi bi-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?= $this->endSection(); ?>
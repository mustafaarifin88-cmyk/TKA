<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Pembuat Soal</h3>
                <p class="text-subtitle text-muted">Perbarui data akun guru dan penugasan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/pembuat_soal') ?>">Pembuat Soal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                
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

                <form action="<?= base_url('admin/pembuat_soal/update/' . $user['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h5 class="text-primary mb-3"><i class="bi bi-person-lines-fill"></i> Informasi Akun</h5>
                            
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="<?= old('nama_lengkap', $user['nama_lengkap']) ?>" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Username</label>
                                <input type="text" name="username" class="form-control" value="<?= old('username', $user['username']) ?>" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Password Baru (Opsional)</label>
                                <input type="password" name="password" class="form-control" placeholder="Isi jika ingin mengganti password">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Foto Profil</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-lg bg-light">
                                        <img src="<?= base_url('uploads/profil/' . ($user['foto'] ? $user['foto'] : 'default.jpg')) ?>" alt="Foto" style="object-fit: cover;">
                                    </div>
                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <h5 class="text-info mb-3"><i class="bi bi-briefcase-fill"></i> Penugasan Mata Pelajaran</h5>
                            
                            <!-- Dropdown Sekolah DIHAPUS -->

                            <div class="form-group">
                                <label class="form-label fw-bold mb-2">Mata Pelajaran yang Diampu</label>
                                <div class="row g-2" style="max-height: 250px; overflow-y: auto;">
                                    <?php foreach ($mapel as $m) : ?>
                                        <div class="col-md-6">
                                            <div class="form-check card p-2 border">
                                                <input class="form-check-input" type="checkbox" name="mapel_id[]" value="<?= $m['id'] ?>" id="mapel_<?= $m['id'] ?>"
                                                <?= in_array($m['id'], $selected_mapel) ? 'checked' : '' ?>>
                                                <label class="form-check-label w-100 cursor-pointer" for="mapel_<?= $m['id'] ?>">
                                                    <?= $m['nama_mapel'] ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="<?= base_url('admin/pembuat_soal') ?>" class="btn btn-light-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
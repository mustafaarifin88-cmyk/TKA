<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Pembuat Soal</h3>
                <p class="text-subtitle text-muted">Kelola akun guru atau pembuat soal yang terdaftar.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pembuat Soal</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title m-0">Tabel User</h5>
                <div>
                    <button type="button" class="btn btn-success me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-file-earmark-excel-fill"></i> Import
                    </button>
                    <a href="<?= base_url('admin/pembuat_soal/create') ?>" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-lg"></i> Tambah Baru
                    </a>
                </div>
            </div>
            <div class="card-body pt-4">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible show fade">
                        <i class="bi bi-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="table1">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="10%">Foto</th>
                                <th>Nama & Username</th>
                                <th>Sekolah / Unit</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $index => $u) : ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="avatar avatar-lg bg-light shadow-sm">
                                            <img src="<?= base_url('uploads/profil/' . ($u['foto'] ? $u['foto'] : 'default.jpg')) ?>" 
                                                 alt="Foto" style="object-fit: cover;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= $u['nama_lengkap'] ?></div>
                                        <small class="text-muted"><i class="bi bi-person-fill"></i> <?= $u['username'] ?></small>
                                    </td>
                                    <td>
                                        <?php if(!empty($u['nama_sekolah'])): ?>
                                            <span class="badge bg-light-info text-info">
                                                <i class="bi bi-building me-1"></i> <?= $u['nama_sekolah'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light-secondary text-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm">
                                            <a href="<?= base_url('admin/pembuat_soal/edit/' . $u['id']) ?>" class="btn btn-sm btn-warning text-white" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="<?= base_url('admin/pembuat_soal/delete/' . $u['id']) ?>" onclick="return confirm('Yakin hapus user ini?')" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-file-earmark-excel-fill me-2"></i> Import User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/pembuat_soal/import') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="d-grid mb-3">
                        <a href="<?= base_url('admin/pembuat_soal/download_template') ?>" class="btn btn-outline-success">
                            <i class="bi bi-download me-2"></i> Download Template Excel
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload File Excel</label>
                        <input type="file" name="file_excel" class="form-control" accept=".xlsx, .xls" required>
                    </div>
                    <div class="alert alert-light-warning color-warning mb-0 text-sm">
                        <i class="bi bi-info-circle"></i> Pastikan nama sekolah di file Excel sama persis dengan data sekolah di sistem.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/extensions/simple-datatables/umd/simple-datatables.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/simple-datatables.js') ?>"></script>
<?= $this->endSection(); ?>
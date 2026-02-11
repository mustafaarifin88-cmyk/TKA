<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Siswa</h3>
                <p class="text-subtitle text-muted">Daftar semua siswa yang terdaftar dalam sistem.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Siswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h4 class="card-title m-0"><i class="bi bi-people-fill me-2 text-primary"></i> Tabel Siswa</h4>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-success me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-file-earmark-excel-fill"></i> Import Excel
                    </button>
                    <a href="<?= base_url('admin/siswa/create') ?>" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Siswa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                            <span><?= session()->getFlashdata('success') ?></span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                            <span><?= session()->getFlashdata('error') ?></span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover table-lg" id="table1">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="10%">Foto</th>
                                <th>Informasi Siswa</th>
                                <th>Kelas</th>
                                <th>Username</th>
                                <th class="text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($siswa as $key => $s) : ?>
                                <tr class="align-middle">
                                    <td class="text-center"><?= $key + 1 ?></td>
                                    <td>
                                        <div class="avatar avatar-xl bg-light shadow-sm">
                                            <img src="<?= base_url('uploads/profil/' . ($s['foto'] ? $s['foto'] : 'default.jpg')) ?>" 
                                                 alt="Foto Siswa"
                                                 style="object-fit: cover; width: 100%; height: 100%;">
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="font-bold mb-0 text-primary"><?= $s['nama_lengkap'] ?></h6>
                                        <small class="text-muted d-block mt-1">NISN: <span class="fw-bold"><?= $s['nisn'] ?></span></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-info text-info fw-bold px-3 py-2">
                                            <?= $s['nama_kelas'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-secondary text-secondary px-3 py-2">
                                            <i class="bi bi-person-fill me-1"></i> <?= $s['username'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm">
                                            <a href="<?= base_url('admin/siswa/edit/' . $s['id']) ?>" class="btn btn-sm btn-warning text-white" title="Edit Data">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="<?= base_url('admin/siswa/delete/' . $s['id']) ?>" onclick="return confirm('Yakin ingin menghapus data siswa ini?')" class="btn btn-sm btn-danger" title="Hapus Data">
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

<!-- Modal Import Excel -->
<div class="modal fade text-left" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content shadow">
            <div class="modal-header bg-success">
                <h5 class="modal-title white" id="importModalLabel">
                    <i class="bi bi-file-earmark-excel-fill me-2"></i> Import Data Siswa
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="<?= base_url('admin/siswa/import') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    
                    <!-- AREA DOWNLOAD TEMPLATE -->
                    <div class="d-grid mb-4">
                        <a href="<?= base_url('admin/siswa/download_template') ?>" class="btn btn-info text-white shadow-sm">
                            <i class="bi bi-download me-2"></i> Download Template Excel
                        </a>
                        <small class="text-muted text-center mt-2">Unduh format template terlebih dahulu sebelum upload.</small>
                    </div>

                    <div class="alert alert-light-primary color-primary mb-4 text-sm">
                        <i class="bi bi-info-circle-fill me-2"></i> <b>Penting:</b> Kolom "Nama Kelas" di Excel harus sama persis dengan nama kelas yang ada di menu Data Kelas.
                    </div>
                    
                    <div class="mb-3">
                        <label for="file_excel" class="form-label fw-bold">Pilih File Excel</label>
                        <input class="form-control" type="file" id="file_excel" name="file_excel" accept=".xlsx, .xls" required>
                        <div class="form-text">Maksimal ukuran file 5MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-success ml-1">
                        <span class="d-none d-sm-block">Upload & Import</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/extensions/simple-datatables/umd/simple-datatables.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/simple-datatables.js') ?>"></script>
<?= $this->endSection(); ?>
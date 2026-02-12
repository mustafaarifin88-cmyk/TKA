<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class=\"page-heading\">
    <div class=\"page-title\">
        <div class=\"row\">
            <div class=\"col-12 col-md-6 order-md-1 order-last\">
                <h3>Data Siswa</h3>
                <p class=\"text-subtitle text-muted\">Daftar semua siswa yang terdaftar dalam sistem.</p>
            </div>
            <div class=\"col-12 col-md-6 order-md-2 order-first\">
                <nav aria-label=\"breadcrumb\" class=\"breadcrumb-header float-start float-lg-end\">
                    <ol class=\"breadcrumb\">
                        <li class=\"breadcrumb-item\"><a href=\"<?= base_url('admin/dashboard') ?>\">Dashboard</a></li>
                        <li class=\"breadcrumb-item active\" aria-current=\"page\">Data Siswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class=\"page-content\">
    <section class=\"section\">
        <div class=\"card shadow-sm border-0 mb-4\">
            <div class=\"card-body bg-light-primary rounded\">
                <form action=\"\" method=\"get\" class=\"row g-3 align-items-end\">
                    <div class=\"col-md-4\">
                        <label class=\"form-label fw-bold\">Filter Sekolah</label>
                        <select name=\"sekolah_id\" class=\"form-select\">
                            <option value=\"\">-- Tampilkan Semua Sekolah --</option>
                            <?php foreach ($sekolah as $s) : ?>
                                <option value=\"<?= $s['id'] ?>\" <?= (isset($selected_sekolah) && $selected_sekolah == $s['id']) ? 'selected' : '' ?>>
                                    <?= $s['nama_sekolah'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class=\"col-md-4 d-flex gap-2\">
                        <button type=\"submit\" class=\"btn btn-primary\">
                            <i class=\"bi bi-funnel-fill me-1\"></i> Filter
                        </button>
                        <?php if(!empty($selected_sekolah)): ?>
                            <a href=\"<?= base_url('admin/siswa/cetak_pdf?sekolah_id=' . $selected_sekolah) ?>\" target=\"_blank\" class=\"btn btn-danger\">
                                <i class=\"bi bi-file-pdf-fill me-1\"></i> Cetak PDF
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class=\"card shadow-sm\">
            <div class=\"card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center\">
                <h4 class=\"card-title m-0\"><i class=\"bi bi-people-fill me-2 text-primary\"></i> Tabel Siswa</h4>
                <div class=\"mt-3 mt-md-0\">
                    <button type=\"button\" class=\"btn btn-success me-2 shadow-sm\" data-bs-toggle=\"modal\" data-bs-target=\"#importModal\">
                        <i class=\"bi bi-file-earmark-spreadsheet-fill\"></i> Import Excel
                    </button>
                    <a href=\"<?= base_url('admin/siswa/create') ?>\" class=\"btn btn-primary shadow-sm\">
                        <i class=\"bi bi-plus-lg\"></i> Tambah Siswa
                    </a>
                </div>
            </div>
            <div class=\"card-body\">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
                        <?= session()->getFlashdata('success') ?>
                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                        <?= session()->getFlashdata('error') ?>
                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                    </div>
                <?php endif; ?>

                <div class=\"table-responsive\">
                    <table class=\"table table-hover\" id=\"table1\">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>NISN</th>
                                <th>Nama Lengkap</th>
                                <th>L/P</th>
                                <th>Sekolah</th>
                                <th>Username</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($siswa as $index => $row) : ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <div class=\"avatar avatar-lg\">
                                            <img src=\"<?= base_url('uploads/profil/' . ($row['foto'] ? $row['foto'] : 'default.jpg')) ?>\" alt=\"Foto\">
                                        </div>
                                    </td>
                                    <td><?= $row['nisn'] ?></td>
                                    <td class=\"fw-bold\"><?= $row['nama_lengkap'] ?></td>
                                    <td><?= $row['jenis_kelamin'] ?></td>
                                    <td><?= $row['nama_sekolah'] ?></td>
                                    <td><span class=\"badge bg-light-primary text-primary\"><?= $row['username'] ?></span></td>
                                    <td>
                                        <div class=\"btn-group\">
                                            <a href=\"<?= base_url('admin/siswa/edit/' . $row['id']) ?>\" class=\"btn btn-sm btn-warning text-white\" title=\"Edit\">
                                                <i class=\"bi bi-pencil-square\"></i>
                                            </a>
                                            <a href=\"<?= base_url('admin/siswa/delete/' . $row['id']) ?>\" onclick=\"return confirm('Yakin hapus data siswa ini?')\" class=\"btn btn-sm btn-danger\" title=\"Hapus\">
                                                <i class=\"bi bi-trash-fill\"></i>
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

<div class=\"modal fade text-left\" id=\"importModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel1\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-dialog-scrollable\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header bg-success\">
                <h5 class=\"modal-title white\" id=\"myModalLabel1\">Import Data Siswa</h5>
                <button type=\"button\" class=\"close rounded-pill\" data-bs-dismiss=\"modal\" aria-label=\"Close\">
                    <i data-feather=\"x\"></i>
                </button>
            </div>
            <form action=\"<?= base_url('admin/siswa/import') ?>\" method=\"post\" enctype=\"multipart/form-data\">
                <?= csrf_field() ?>
                <div class=\"modal-body\">
                    <div class=\"d-grid mb-3\">
                        <a href=\"<?= base_url('admin/siswa/download_template') ?>\" class=\"btn btn-outline-success\">
                            <i class=\"bi bi-download me-2\"></i> Download Template Excel
                        </a>
                        <small class=\"text-muted text-center mt-2\">Unduh format template terlebih dahulu sebelum upload.</small>
                    </div>

                    <div class=\"alert alert-light-primary color-primary mb-4 text-sm\">
                        <i class=\"bi bi-info-circle-fill me-2\"></i> <b>Penting:</b> Pilih nama sekolah melalui dropdown di dalam file Excel.
                    </div>
                    
                    <div class=\"mb-3\">
                        <label for=\"file_excel\" class=\"form-label fw-bold\">Pilih File Excel</label>
                        <input class=\"form-control\" type=\"file\" id=\"file_excel\" name=\"file_excel\" accept=\".xlsx, .xls\" required>
                        <div class=\"form-text\">Maksimal ukuran file 5MB.</div>
                    </div>
                </div>
                <div class=\"modal-footer\">
                    <button type=\"button\" class=\"btn btn-light-secondary\" data-bs-dismiss=\"modal\">
                        <span class=\"d-none d-sm-block\">Batal</span>
                    </button>
                    <button type=\"submit\" class=\"btn btn-success ml-1\">
                        <span class=\"d-none d-sm-block\">Upload & Import</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src=\"<?= base_url('assets/extensions/simple-datatables/umd/simple-datatables.js') ?>\"></script>
<script src=\"<?= base_url('assets/static/js/pages/simple-datatables.js') ?>\"></script>
<?= $this->endSection(); ?>
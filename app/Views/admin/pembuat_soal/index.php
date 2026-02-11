<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Bank Soal</h3>
                <p class="text-subtitle text-muted">Kelola semua butir soal ujian di sini.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bank Soal</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Daftar Soal</h4>
                <a href="<?= base_url('admin/pembuat_soal/create') ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Soal Baru
                </a>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible show fade">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover table-lg" id="table-soal">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Mata Pelajaran</th>
                                <th>Pertanyaan</th>
                                <th width="10%">Kesulitan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($soal) && is_array($soal)) : ?>
                                <?php $no = 1; foreach ($soal as $s) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <span class="badge bg-light-primary text-primary"><?= esc($s['nama_mapel']) ?></span>
                                        </td>
                                        <td class="text-truncate" style="max-width: 300px;">
                                            <?= strip_tags($s['pertanyaan']) ?>
                                        </td>
                                        <td>
                                            <?php if ($s['kesulitan'] == 'mudah') : ?>
                                                <span class="badge bg-success">Mudah</span>
                                            <?php elseif ($s['kesulitan'] == 'sedang') : ?>
                                                <span class="badge bg-warning text-dark">Sedang</span>
                                            <?php else : ?>
                                                <span class="badge bg-danger">Sulit</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('admin/pembuat_soal/edit/' . $s['id']) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="<?= base_url('admin/pembuat_soal/delete/' . $s['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small">Belum ada data soal.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
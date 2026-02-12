<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="text-primary">Daftar Soal <?= strtoupper(str_replace('_', ' ', $jenis)) ?></h3>
                <p class="text-subtitle text-muted">
                    Mata Pelajaran: <span class="fw-bold text-dark"><?= $mapel['nama_mapel'] ?></span> | 
                    Sekolah: <span class="fw-bold text-dark"><?= $kelas['nama_sekolah'] ?></span>
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <a href="<?= base_url("guru/soal/jenis/{$kelas['id']}/{$mapel['id']}") ?>" class="btn btn-light-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($is_locked) : ?>
        <div class="alert alert-light-warning color-warning border-warning shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-lock-fill fs-4 me-3"></i>
                <div>
                    <strong>Mode Baca Saja</strong><br>
                    Soal tidak dapat diedit karena Jadwal Ujian sudah dibuat.
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0">List Pertanyaan</h5>
            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill">Total: <?= count($soal) ?></span>
        </div>
        <div class="card-body pt-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="table1">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="50%">Pertanyaan</th>
                            <th width="10%" class="text-center">Kunci</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($soal as $key => $s) : ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= $key + 1 ?></td>
                                <td>
                                    <div class="text-wrap" style="font-size: 0.95rem;">
                                        <?= substr(strip_tags($s['pertanyaan']), 0, 150) ?>...
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if ($jenis == 'pg') : ?>
                                        <span class="badge bg-success rounded-circle" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;"><?= $s['kunci_jawaban'] ?></span>
                                    <?php elseif ($jenis == 'pg_kompleks') : ?>
                                        <?php 
                                            $keys = json_decode($s['kunci_jawaban'], true);
                                            if(is_array($keys)) {
                                                foreach($keys as $k) echo '<span class="badge bg-success me-1">'.$k.'</span>';
                                            } else echo '-';
                                        ?>
                                    <?php elseif ($jenis == 'benar_salah') : ?>
                                        <span class="badge bg-info">Tabel</span>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!$is_locked) : ?>
                                        <div class="btn-group shadow-sm">
                                            <a href="<?= base_url('guru/soal/edit/' . $s['id']) ?>" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="<?= base_url('guru/soal/delete/' . $s['id']) ?>" onclick="return confirm('Hapus soal?')" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></a>
                                        </div>
                                    <?php else : ?>
                                        <i class="bi bi-lock text-muted"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($soal)): ?>
                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada soal.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/extensions/simple-datatables/umd/simple-datatables.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/simple-datatables.js') ?>"></script>
<?= $this->endSection(); ?>
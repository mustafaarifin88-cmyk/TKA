<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Koreksi Bank Soal</h3>
                <p class="text-subtitle text-muted">
                    Pembuat Soal: <span class="fw-bold text-dark"><?= $guru['nama_lengkap'] ?></span> | 
                    Mata Pelajaran: <span class="fw-bold text-dark"><?= $mapel['nama_mapel'] ?></span>
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <a href="<?= base_url('admin/bank_soal/mapel/' . $guru['id']) ?>" class="btn btn-light-secondary shadow-sm rounded-pill">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Mapel
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

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 text-primary">
                <i class="bi bi-list-check me-2"></i> Daftar Pertanyaan
            </h5>
            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill">
                Total: <?= count($soal) ?> Soal
            </span>
        </div>
        <div class="card-body pt-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="table1">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="35%">Pertanyaan</th>
                            <th class="text-center" width="15%">Jenis</th>
                            <th class="text-center" width="10%">Media</th>
                            <th class="text-center" width="20%">Kunci</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($soal as $key => $s) : ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= $key + 1 ?></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark" style="font-size: 0.95rem;">
                                            <?= substr(strip_tags($s['pertanyaan']), 0, 80) ?>...
                                        </span>
                                        <small class="text-muted mt-1">ID: #<?= $s['id'] ?></small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if ($s['jenis'] == 'pg') : ?>
                                        <span class="badge bg-light-info text-info border border-info rounded-pill">Pilihan Ganda</span>
                                    <?php elseif ($s['jenis'] == 'pg_kompleks') : ?>
                                        <span class="badge bg-light-primary text-primary border border-primary rounded-pill">PG Kompleks</span>
                                    <?php elseif ($s['jenis'] == 'benar_salah') : ?>
                                        <span class="badge bg-light-success text-success border border-success rounded-pill">Benar/Salah</span>
                                    <?php else : ?>
                                        <span class="badge bg-light-warning text-warning border border-warning rounded-pill">Esai</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($s['file_soal']) : ?>
                                        <div class="avatar avatar-lg shadow-sm border position-relative">
                                            <img src="<?= base_url('uploads/bank_soal/' . $s['file_soal']) ?>" 
                                                 alt="Soal" 
                                                 style="object-fit: cover; width: 100%; height: 100%; cursor: pointer;"
                                                 onclick="window.open(this.src, '_blank')"
                                                 data-bs-toggle="tooltip" title="Lihat Gambar">
                                        </div>
                                    <?php else : ?>
                                        <span class="text-muted text-xs"><i class="bi bi-dash-lg"></i></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($s['jenis'] == 'pg') : ?>
                                        <span class="avatar avatar-sm bg-success text-white fw-bold shadow-sm">
                                            <?= $s['kunci_jawaban'] ?>
                                        </span>
                                    <?php elseif ($s['jenis'] == 'pg_kompleks') : ?>
                                        <?php 
                                            $keys = json_decode($s['kunci_jawaban'], true);
                                            if(is_array($keys)) {
                                                foreach($keys as $k) {
                                                    echo '<span class="badge bg-success me-1 mb-1">'.$k.'</span>';
                                                }
                                            } else {
                                                echo '<span class="text-muted">-</span>';
                                            }
                                        ?>
                                    <?php elseif ($s['jenis'] == 'benar_salah') : ?>
                                        <?php 
                                            $keys = json_decode($s['kunci_jawaban'], true);
                                            if(is_array($keys)) {
                                                echo '<span class="badge bg-info">'.count($keys).' Item</span>';
                                            } else {
                                                 echo '<span class="badge '. (($s['kunci_jawaban'] == 'Benar') ? 'bg-success' : 'bg-danger') .'">'.$s['kunci_jawaban'].'</span>';
                                            }
                                        ?>
                                    <?php else : ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm" role="group">
                                        <a href="<?= base_url('admin/bank_soal/edit/' . $s['id']) ?>" class="btn btn-sm btn-warning text-white" data-bs-toggle="tooltip" title="Koreksi / Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="<?= base_url('admin/bank_soal/delete/' . $s['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini secara permanen?')" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus Soal">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($soal)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <img src="<?= base_url('assets/static/images/samples/error-404.svg') ?>" alt="No Data" style="height: 150px; opacity: 0.5;">
                                    <p class="text-muted mt-3 mb-0">Belum ada soal yang dibuat oleh pembuat soal ini untuk mata pelajaran tersebut.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/extensions/simple-datatables/umd/simple-datatables.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/simple-datatables.js') ?>"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?= $this->endSection(); ?>
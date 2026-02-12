<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Rekap Nilai Ujian</h3>
                <p class="text-subtitle text-muted">Pilih ujian untuk melihat hasil dan melakukan koreksi esai.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nilai</li>
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
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="table1">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Mata Pelajaran</th>
                                <th>Sekolah / Unit</th>
                                <th>Tanggal Ujian</th>
                                <th class="text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jadwal as $key => $j) : ?>
                                <tr>
                                    <td class="text-center"><?= $key + 1 ?></td>
                                    <td>
                                        <span class="fw-bold text-dark"><?= $j['nama_mapel'] ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-primary text-primary">
                                            <i class="bi bi-building me-1"></i> <?= $j['nama_sekolah'] ?>
                                        </span>
                                    </td>
                                    <td><?= date('d M Y', strtotime($j['tanggal_ujian'])) ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('guru/nilai/detail/' . $j['id']) ?>" class="btn btn-primary btn-sm shadow-sm rounded-pill px-3">
                                            <i class="bi bi-pencil-square me-1"></i> Detail & Koreksi
                                        </a>
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

<script src="<?= base_url('assets/extensions/simple-datatables/umd/simple-datatables.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/simple-datatables.js') ?>"></script>
<?= $this->endSection(); ?>
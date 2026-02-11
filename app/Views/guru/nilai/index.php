<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Rekap Nilai Ujian</h3>
                <p class="text-subtitle text-muted">Pilih ujian untuk melakukan koreksi nilai.</p>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Tanggal Ujian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jadwal as $key => $j) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $j['nama_mapel'] ?></td>
                                    <td><?= $j['nama_kelas'] ?></td>
                                    <td><?= date('d M Y', strtotime($j['tanggal_ujian'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('guru/nilai/detail/' . $j['id']) ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye-fill"></i> Detail & Koreksi
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
<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Penilaian</h3>
                <p class="text-subtitle text-muted">
                    <span class="badge bg-primary"><?= $jadwal['nama_mapel'] ?></span> 
                    <span class="badge bg-info"><?= $jadwal['nama_kelas'] ?></span>
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <a href="<?= base_url('guru/nilai/cetak/' . $jadwal['id']) ?>" target="_blank" class="btn btn-danger shadow-sm me-1">
                    <i class="bi bi-printer-fill me-2"></i> PDF
                </a>
                
                <a href="<?= base_url('guru/nilai/export_excel/' . $jadwal['id']) ?>" target="_blank" class="btn btn-success shadow-sm me-1">
                    <i class="bi bi-file-earmark-excel-fill me-2"></i> Excel
                </a>

                <a href="<?= base_url('guru/nilai') ?>" class="btn btn-secondary shadow-sm">Kembali</a>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light-primary border-bottom">
            <h5 class="card-title m-0 text-primary"><i class="bi bi-sliders me-2"></i> Pengaturan Bobot Nilai (%)</h5>
        </div>
        <div class="card-body mt-3">
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('guru/nilai/simpan_bobot') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="jadwal_id" value="<?= $jadwal['id'] ?>">
                
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label fw-bold">PG</label>
                        <div class="input-group">
                            <input type="number" name="bobot_pg" class="form-control" value="<?= $jadwal['bobot_pg'] ?>" min="0" max="100" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">PG Kompleks</label>
                        <div class="input-group">
                            <input type="number" name="bobot_pg_kompleks" class="form-control" value="<?= $jadwal['bobot_pg_kompleks'] ?>" min="0" max="100" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Benar/Salah</label>
                        <div class="input-group">
                            <input type="number" name="bobot_benar_salah" class="form-control" value="<?= $jadwal['bobot_benar_salah'] ?>" min="0" max="100" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Esai</label>
                        <div class="input-group">
                            <input type="number" name="bobot_esai" class="form-control" value="<?= $jadwal['bobot_esai'] ?>" min="0" max="100" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-save me-1"></i> Simpan & Hitung Ulang
                        </button>
                    </div>
                </div>
            </form>
            <div class="alert alert-light-secondary mt-3 mb-0 py-2">
                <small><i class="bi bi-info-circle me-1"></i> Total bobot harus 100%. Nilai Akhir dihitung otomatis berdasarkan persentase masing-masing jenis soal.</small>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title m-0"><i class="bi bi-table me-2"></i> Daftar Nilai Siswa</h5>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="table1">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Siswa</th>
                            <th class="text-center">PG</th>
                            <th class="text-center">Kompleks</th>
                            <th class="text-center">B/S</th>
                            <th class="text-center">Esai</th>
                            <th class="text-center bg-light-success text-success">Akhir</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($siswa as $key => $s) : ?>
                            <tr>
                                <td class="text-center"><?= $key + 1 ?></td>
                                <td>
                                    <div class="fw-bold"><?= $s['nama_lengkap'] ?></div>
                                    <small class="text-muted"><?= $s['nisn'] ?></small>
                                </td>
                                <td class="text-center"><?= number_format($s['nilai_pg'] ?? 0, 2) ?></td>
                                <td class="text-center"><?= number_format($s['nilai_pg_kompleks'] ?? 0, 2) ?></td>
                                <td class="text-center"><?= number_format($s['nilai_benar_salah'] ?? 0, 2) ?></td>
                                <td class="text-center"><?= number_format($s['nilai_esai'] ?? 0, 2) ?></td>
                                <td class="text-center fw-bold fs-5 text-success"><?= number_format($s['nilai_total'] ?? 0, 2) ?></td>
                                <td class="text-center">
                                    <?php if ($s['status'] == 'selesai') : ?>
                                        <a href="<?= base_url('guru/nilai/koreksi/' . $jadwal['id'] . '/' . $s['id']) ?>" class="btn btn-warning btn-sm shadow-sm" data-bs-toggle="tooltip" title="Koreksi Jawaban">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    <?php else : ?>
                                        <span class="badge bg-light-secondary text-secondary">Belum Selesai</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/extensions/simple-datatables/umd/simple-datatables.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/simple-datatables.js') ?>"></script>
<?= $this->endSection(); ?>
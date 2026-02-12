<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Koreksi Jawaban</h3>
                <p class="text-subtitle text-muted">Siswa: <strong><?= $siswa['nama_lengkap'] ?></strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <a href="<?= base_url('guru/nilai/detail/' . $jadwal['id']) ?>" class="btn btn-secondary shadow-sm">Kembali</a>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">Skor Penilaian Otomatis</h5>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light-primary rounded">
                                <h6 class="text-muted">Pilihan Ganda</h6>
                                <h3 class="fw-bold text-primary mb-0"><?= number_format($pg['stats']['nilai'], 2) ?></h3>
                                <small>Benar: <?= $pg['stats']['benar'] ?> / <?= $pg['stats']['total'] ?></small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light-info rounded">
                                <h6 class="text-muted">PG Kompleks</h6>
                                <h3 class="fw-bold text-info mb-0"><?= number_format($kompleks['stats']['nilai'], 2) ?></h3>
                                <small>Benar: <?= $kompleks['stats']['benar'] ?> / <?= $kompleks['stats']['total'] ?></small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light-success rounded">
                                <h6 class="text-muted">Benar / Salah</h6>
                                <h3 class="fw-bold text-success mb-0"><?= number_format($bs['stats']['nilai'], 2) ?></h3>
                                <small>Benar: <?= $bs['stats']['benar'] ?> / <?= $bs['stats']['total'] ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="<?= base_url('guru/nilai/simpan_koreksi') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="jadwal_id" value="<?= $jadwal['id'] ?>">
        <input type="hidden" name="siswa_id" value="<?= $siswa['id'] ?>">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0"><i class="bi bi-pencil-square me-2"></i> Koreksi Manual Esai</h5>
                <button type="submit" class="btn btn-dark btn-sm fw-bold shadow-sm">
                    <i class="bi bi-save-fill me-1"></i> Simpan Nilai
                </button>
            </div>
            <div class="card-body pt-4">
                <?php if (empty($esai)) : ?>
                    <div class="alert alert-light-secondary text-center">Tidak ada soal esai pada ujian ini.</div>
                <?php else : ?>
                    <div class="alert alert-light-warning mb-4">
                        <i class="bi bi-info-circle me-1"></i> Berikan nilai antara <b>0 - 100</b> untuk setiap jawaban esai siswa.
                    </div>
                    
                    <?php foreach ($esai as $key => $e) : ?>
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="card-title text-muted">Soal No. <?= $key + 1 ?></h6>
                                <div class="mb-3 text-dark"><?= $e['pertanyaan'] ?></div>
                                
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Jawaban Siswa:</label>
                                    <div class="p-3 bg-light rounded border">
                                        <?= nl2br($e['jawaban_siswa']) ?>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Nilai (0-100):</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="nilai_esai[<?= $e['id'] ?>]" class="form-control border-warning fw-bold text-center" 
                                               value="<?= $e['nilai_koreksi'] ?>" min="0" max="100" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg shadow">Simpan Hasil Koreksi</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <div class="accordion mt-4 shadow-sm" id="accordionDetail">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                    Lihat Detail Jawaban Objektif (PG, Kompleks, B/S)
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionDetail">
                <div class="accordion-body bg-light">
                    <h6 class="text-primary mt-2">Pilihan Ganda</h6>
                    <?php foreach($pg['data'] as $p): ?>
                        <div class="mb-2 p-2 border rounded bg-white d-flex justify-content-between">
                            <div class="w-75 text-truncate"><?= strip_tags($p['pertanyaan']) ?></div>
                            <span>
                                Jwb: <b><?= $p['jawaban_siswa'] ?></b> 
                                <span class="badge <?= ($p['jawaban_siswa']==$p['kunci_jawaban']) ? 'bg-success' : 'bg-danger' ?>">
                                    <?= ($p['jawaban_siswa']==$p['kunci_jawaban']) ? 'Benar' : 'Salah' ?>
                                </span>
                            </span>
                        </div>
                    <?php endforeach; ?>

                    <h6 class="text-info mt-3">PG Kompleks</h6>
                    <?php foreach($kompleks['data'] as $k): 
                        $kunciArr = json_decode($k['kunci_jawaban'], true);
                        $jawabArr = json_decode($k['jawaban_siswa'], true);
                        if(is_array($kunciArr)) sort($kunciArr);
                        if(is_array($jawabArr)) sort($jawabArr);
                        $isBenar = ($kunciArr === $jawabArr);
                    ?>
                        <div class="mb-2 p-2 border rounded bg-white d-flex justify-content-between">
                            <div class="w-75 text-truncate"><?= strip_tags($k['pertanyaan']) ?></div>
                            <span>
                                <span class="badge <?= $isBenar ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $isBenar ? 'Benar' : 'Salah' ?>
                                </span>
                            </span>
                        </div>
                    <?php endforeach; ?>

                    <h6 class="text-success mt-3">Benar / Salah</h6>
                    <?php foreach($bs['data'] as $b): 
                         $kunciArr = json_decode($b['kunci_jawaban'], true);
                         $jawabArr = json_decode($b['jawaban_siswa'], true);
                         $isBenar = (is_array($kunciArr) && is_array($jawabArr) && $kunciArr === $jawabArr);
                    ?>
                        <div class="mb-2 p-2 border rounded bg-white d-flex justify-content-between">
                            <div class="w-75 text-truncate"><?= strip_tags($b['pertanyaan']) ?></div>
                            <span>
                                <span class="badge <?= $isBenar ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $isBenar ? 'Benar' : 'Salah' ?>
                                </span>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
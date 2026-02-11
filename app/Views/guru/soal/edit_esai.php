<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Soal Esai</h3>
                <p class="text-subtitle text-muted">
                    <span class="badge bg-light-primary text-primary me-2"><i class="bi bi-book me-1"></i> <?= $mapel['nama_mapel'] ?></span>
                    <span class="badge bg-light-info text-info"><i class="bi bi-people me-1"></i> <?= $kelas['nama_kelas'] ?></span>
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <a href="<?= base_url("guru/soal/list/{$soal['kelas_id']}/{$soal['mapel_id']}/esai") ?>" class="btn btn-light-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/update/' . $soal['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="jenis" value="esai">

        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom pb-3">
                        <h5 class="card-title m-0 text-primary"><i class="bi bi-pencil-square me-2"></i> Konten Soal Esai</h5>
                    </div>
                    <div class="card-body pt-4">
                        
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold text-dark mb-2">Pertanyaan</label>
                            <textarea name="pertanyaan" class="form-control" rows="8" required placeholder="Tuliskan pertanyaan esai anda disini..."><?= $soal['pertanyaan'] ?></textarea>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-image me-1"></i> Gambar Pendukung (Opsional)</label>
                            <div class="d-flex flex-column flex-md-row gap-3 align-items-start">
                                <div class="w-100">
                                    <input type="file" name="file_soal" class="form-control" accept="image/*">
                                    <div class="form-text text-muted">Upload gambar baru jika ingin mengganti gambar lama.</div>
                                </div>
                                <?php if ($soal['file_soal']) : ?>
                                    <div class="text-center p-2 border rounded bg-light" style="min-width: 150px;">
                                        <small class="d-block mb-1 text-muted fw-bold">Gambar Saat Ini:</small>
                                        <img src="<?= base_url('uploads/bank_soal/' . $soal['file_soal']) ?>" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-height: 150px; cursor: pointer;" 
                                             onclick="window.open(this.src, '_blank')"
                                             title="Klik untuk memperbesar"
                                             alt="Preview Soal">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= base_url("guru/soal/list/{$soal['kelas_id']}/{$soal['mapel_id']}/esai") ?>" class="btn btn-light-secondary px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow">
                                <i class="bi bi-save-fill me-2"></i> Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>
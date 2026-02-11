<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Soal Pilihan Ganda</h3>
                <p class="text-subtitle text-muted">
                    <span class="badge bg-light-primary text-primary me-2"><i class="bi bi-book me-1"></i> <?= $mapel['nama_mapel'] ?></span>
                    <span class="badge bg-light-info text-info"><i class="bi bi-people me-1"></i> <?= $kelas['nama_kelas'] ?></span>
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <a href="<?= base_url("guru/soal/list/{$soal['kelas_id']}/{$soal['mapel_id']}/pg") ?>" class="btn btn-light-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/update/' . $soal['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="jenis" value="pg">

        <div class="row">
            <!-- Kolom Kiri: Editor Soal & Opsi -->
            <div class="col-12 col-lg-8">
                <!-- Kartu Pertanyaan -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-3">
                        <h5 class="card-title m-0 text-primary"><i class="bi bi-question-circle-fill me-2"></i> Konten Soal</h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold text-dark mb-2">Teks Pertanyaan</label>
                            <textarea name="pertanyaan" class="form-control" rows="6" required placeholder="Tuliskan pertanyaan anda disini..."><?= $soal['pertanyaan'] ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-image me-1"></i> Gambar Soal (Opsional)</label>
                            <div class="d-flex flex-column flex-md-row gap-3 align-items-start">
                                <div class="w-100">
                                    <input type="file" name="file_soal" class="form-control" accept="image/*">
                                    <div class="form-text text-muted">Upload gambar baru jika ingin mengganti gambar lama.</div>
                                </div>
                                <?php if ($soal['file_soal']) : ?>
                                    <div class="text-center p-2 border rounded bg-light" style="min-width: 120px;">
                                        <small class="d-block mb-1 text-muted fw-bold">Gambar Saat Ini:</small>
                                        <img src="<?= base_url('uploads/bank_soal/' . $soal['file_soal']) ?>" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-height: 100px; cursor: pointer;" 
                                             onclick="window.open(this.src, '_blank')"
                                             title="Klik untuk memperbesar"
                                             alt="Preview Soal">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kartu Opsi Jawaban -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom pb-3">
                        <h5 class="card-title m-0 text-info"><i class="bi bi-list-ul me-2"></i> Pilihan Jawaban</h5>
                    </div>
                    <div class="card-body pt-4">
                        <?php 
                        $opsi = ['a', 'b', 'c', 'd', 'e'];
                        foreach($opsi as $o) : 
                            $label = strtoupper($o);
                            $required = ($o == 'a' || $o == 'b' || $o == 'c') ? 'required' : '';
                        ?>
                            <div class="row mb-4 align-items-start border-bottom pb-3">
                                <div class="col-auto">
                                    <span class="avatar avatar-md bg-light-primary text-primary fw-bold shadow-sm"><?= $label ?></span>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-2">
                                        <input type="text" name="opsi_<?= $o ?>" class="form-control fw-bold" 
                                               value="<?= $soal["opsi_$o"] ?>" 
                                               placeholder="Teks Jawaban <?= $label ?>" <?= $required ?>>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light"><i class="bi bi-image"></i></span>
                                            <input type="file" name="file_<?= $o ?>" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                        <?php if ($soal["file_$o"]) : ?>
                                            <a href="<?= base_url('uploads/bank_soal/' . $soal["file_$o"]) ?>" target="_blank" class="btn btn-sm btn-light-secondary border" data-bs-toggle="tooltip" title="Lihat Gambar">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Sidebar Kunci & Aksi -->
            <div class="col-12 col-lg-4">
                <div class="card shadow border-0 mb-4 sticky-top" style="top: 20px; z-index: 1;">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title m-0 text-white"><i class="bi bi-key-fill me-2"></i> Kunci Jawaban</h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="alert alert-light-success color-success mb-3 border-0">
                            <i class="bi bi-info-circle me-1"></i> Tentukan jawaban yang benar.
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label fw-bold">Pilih Kunci:</label>
                            <select name="kunci_jawaban" class="form-select form-select-lg border-success fw-bold text-success text-center shadow-sm" required>
                                <?php foreach($opsi as $o) : $label = strtoupper($o); ?>
                                    <option value="<?= $label ?>" <?= ($soal['kunci_jawaban'] == $label) ? 'selected' : '' ?>>
                                        Opsi <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow">
                                <i class="bi bi-save-fill me-2"></i> Simpan Update
                            </button>
                            <a href="<?= base_url("guru/soal/list/{$soal['kelas_id']}/{$soal['mapel_id']}/pg") ?>" class="btn btn-light-secondary text-muted">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Inisialisasi Tooltip Bootstrap jika tersedia
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?= $this->endSection(); ?>
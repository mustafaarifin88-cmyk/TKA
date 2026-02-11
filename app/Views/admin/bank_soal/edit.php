<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Soal (Admin)</h3>
                <p class="text-subtitle text-muted">Koreksi konten soal yang dibuat oleh pembuat soal.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <a href="<?= base_url("admin/bank_soal/list/{$soal['guru_id']}/{$soal['mapel_id']}") ?>" class="btn btn-light-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> Batal & Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <form action="<?= base_url('admin/bank_soal/update/' . $soal['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="jenis" value="<?= $soal['jenis'] ?>">

        <div class="row">
            <div class="col-12 <?= ($soal['jenis'] == 'pg') ? 'col-lg-8' : 'col-lg-12' ?>">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-3">
                        <h5 class="card-title m-0 text-primary">
                            <i class="bi bi-pencil-square me-2"></i> 
                            Pertanyaan (<?= strtoupper($soal['jenis']) ?>)
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold text-dark mb-2">Teks Pertanyaan</label>
                            <textarea name="pertanyaan" class="form-control" rows="5" required><?= $soal['pertanyaan'] ?></textarea>
                        </div>

                        <?php if ($soal['file_soal']) : ?>
                            <div class="form-group mb-4">
                                <label class="form-label text-muted d-block">Gambar Terlampir:</label>
                                <img src="<?= base_url('uploads/bank_soal/' . $soal['file_soal']) ?>" 
                                     class="img-fluid rounded border shadow-sm" 
                                     style="max-height: 200px;" 
                                     onclick="window.open(this.src, '_blank')">
                            </div>
                        <?php endif; ?>

                        <?php if ($soal['jenis'] == 'pg') : ?>
                            <hr class="my-4">
                            <h6 class="mb-3 text-info"><i class="bi bi-list-ul me-2"></i> Pilihan Jawaban</h6>
                            
                            <?php 
                            $opsi = ['a', 'b', 'c', 'd', 'e'];
                            foreach($opsi as $o) : 
                                $label = strtoupper($o);
                                $required = ($o == 'a' || $o == 'b' || $o == 'c') ? 'required' : '';
                            ?>
                                <div class="row mb-3 align-items-center">
                                    <div class="col-auto">
                                        <span class="avatar avatar-sm bg-light-secondary text-dark fw-bold"><?= $label ?></span>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="opsi_<?= $o ?>" class="form-control" 
                                               value="<?= $soal["opsi_$o"] ?>" <?= $required ?>>
                                        
                                        <?php if ($soal["file_$o"]) : ?>
                                            <div class="mt-1">
                                                <small class="text-muted"><i class="bi bi-image"></i> Gambar Opsi:</small> 
                                                <a href="<?= base_url('uploads/bank_soal/' . $soal["file_$o"]) ?>" target="_blank" class="text-decoration-none">Lihat</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($soal['jenis'] == 'pg') : ?>
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title m-0 text-white"><i class="bi bi-key-fill me-2"></i> Kunci Jawaban</h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="form-group">
                            <label class="form-label fw-bold">Jawaban Benar:</label>
                            <select name="kunci_jawaban" class="form-select border-success fw-bold text-success text-center" required>
                                <?php foreach(['A', 'B', 'C', 'D', 'E'] as $kunci) : ?>
                                    <option value="<?= $kunci ?>" <?= ($soal['kunci_jawaban'] == $kunci) ? 'selected' : '' ?>>
                                        Opsi <?= $kunci ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="col-12 mt-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-end gap-2">
                        <a href="<?= base_url("admin/bank_soal/list/{$soal['guru_id']}/{$soal['mapel_id']}") ?>" class="btn btn-light-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4 shadow">
                            <i class="bi bi-save-fill me-2"></i> Simpan Perubahan (Admin)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>
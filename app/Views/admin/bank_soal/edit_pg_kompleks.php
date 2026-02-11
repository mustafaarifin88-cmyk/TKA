<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Soal PG Kompleks (Admin)</h3>
                <p class="text-subtitle text-muted">Koreksi soal pilihan ganda dengan jawaban lebih dari satu.</p>
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
        <input type="hidden" name="jenis" value="pg_kompleks">

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-3">
                        <h5 class="card-title m-0 text-primary">
                            <i class="bi bi-pencil-square me-2"></i> 
                            Pertanyaan
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
                                     onclick="window.open(this.src, '_blank')"
                                     style="cursor: pointer;">
                            </div>
                        <?php endif; ?>

                        <hr class="my-4">
                        <h6 class="mb-3 text-info"><i class="bi bi-list-check me-2"></i> Pilihan Jawaban</h6>
                        
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
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title m-0 text-white"><i class="bi bi-check-all me-2"></i> Kunci Jawaban</h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="alert alert-light-primary color-primary mb-3">
                            <i class="bi bi-info-circle me-1"></i> Pilih satu atau lebih jawaban yang benar.
                        </div>

                        <?php 
                            $kunciArr = json_decode($soal['kunci_jawaban'], true) ?? [];
                        ?>

                        <div class="d-flex flex-column gap-2">
                            <?php foreach(['A', 'B', 'C', 'D', 'E'] as $k) : ?>
                                <div class="form-check card-hover p-2 border rounded">
                                    <input class="form-check-input ms-1" type="checkbox" name="kunci_jawaban[]" 
                                           value="<?= $k ?>" id="kunci_<?= $k ?>"
                                           <?= in_array($k, $kunciArr) ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-bold ms-2 cursor-pointer w-100" for="kunci_<?= $k ?>">
                                        Pilihan <?= $k ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
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
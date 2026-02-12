<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <h3>Edit Soal Pilihan Ganda</h3>
    <p class="text-subtitle text-muted">Sekolah: <?= $kelas['nama_sekolah'] ?></p>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/update/' . $soal['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="jenis" value="pg">

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-3">
                        <h5 class="card-title m-0 text-primary">Konten Soal</h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">Pertanyaan</label>
                            <textarea name="pertanyaan" class="summernote-editor" required><?= $soal['pertanyaan'] ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom pb-3">
                        <h5 class="card-title m-0 text-info">Pilihan Jawaban</h5>
                    </div>
                    <div class="card-body pt-4">
                        <?php foreach(['a', 'b', 'c', 'd', 'e'] as $o) : ?>
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Opsi <?= strtoupper($o) ?></label>
                                <textarea name="opsi_<?= $o ?>" class="summernote-simple"><?= $soal["opsi_$o"] ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card shadow border-0 mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title m-0 text-white">Kunci Jawaban</h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="form-group">
                            <label class="form-label fw-bold">Pilih Kunci:</label>
                            <select name="kunci_jawaban" class="form-select" required>
                                <?php foreach(['A', 'B', 'C', 'D', 'E'] as $kunci) : ?>
                                    <option value="<?= $kunci ?>" <?= ($soal['kunci_jawaban'] == $kunci) ? 'selected' : '' ?>><?= $kunci ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <hr class="my-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Simpan Update</button>
                            <a href="<?= base_url("guru/soal/list/{$soal['sekolah_id']}/{$soal['mapel_id']}/pg") ?>" class="btn btn-light-secondary">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.summernote-editor').summernote({
            placeholder: 'Edit pertanyaan...', tabsize: 2, height: 250,
            toolbar: [['style', ['style']], ['font', ['bold', 'underline']], ['para', ['ul', 'ol', 'paragraph']], ['insert', ['link', 'picture']]]
        });
        $('.summernote-simple').summernote({
            placeholder: 'Edit jawaban...', tabsize: 2, height: 100,
            toolbar: [['font', ['bold', 'italic']], ['insert', ['picture']]]
        });
    });
</script>
<?= $this->endSection(); ?>
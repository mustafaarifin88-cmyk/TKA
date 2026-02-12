<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <h3>Edit Soal PG Kompleks</h3>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/update/' . $soal['id']) ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="jenis" value="pg_kompleks">

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body pt-4">
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">Pertanyaan</label>
                            <textarea name="pertanyaan" class="summernote-editor" required><?= $soal['pertanyaan'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0">
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
                    <div class="card-header bg-primary text-white"><h5 class="card-title m-0 text-white">Kunci Jawaban</h5></div>
                    <div class="card-body pt-4">
                        <?php $kunciArr = json_decode($soal['kunci_jawaban'], true) ?? []; ?>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach(['A', 'B', 'C', 'D', 'E'] as $k) : ?>
                                <div class="form-check border p-2 rounded">
                                    <input class="form-check-input ms-1" type="checkbox" name="kunci_jawaban[]" value="<?= $k ?>" <?= in_array($k, $kunciArr) ? 'checked' : '' ?>>
                                    <label class="form-check-label ms-2 fw-bold"><?= $k ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <hr class="my-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Simpan Update</button>
                            <a href="<?= base_url("guru/soal/list/{$soal['sekolah_id']}/{$soal['mapel_id']}/pg_kompleks") ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('.summernote-editor').summernote({ height: 250 });
        $('.summernote-simple').summernote({ height: 100, toolbar: [['font', ['bold', 'italic']], ['insert', ['picture']]] });
    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <h3>Edit Soal Esai</h3>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/update/' . $soal['id']) ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="jenis" value="esai">

        <div class="card shadow-sm border-0">
            <div class="card-body pt-4">
                <div class="form-group mb-4">
                    <label class="form-label fw-bold">Pertanyaan</label>
                    <textarea name="pertanyaan" class="summernote-editor" required><?= $soal['pertanyaan'] ?></textarea>
                </div>
                <div class="form-group text-end mt-4">
                    <a href="<?= base_url("guru/soal/list/{$soal['sekolah_id']}/{$soal['mapel_id']}/esai") ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.summernote-editor').summernote({
            placeholder: 'Edit pertanyaan...', tabsize: 2, height: 300,
            toolbar: [['style', ['style']], ['font', ['bold', 'underline']], ['para', ['ul', 'ol', 'paragraph']], ['insert', ['link', 'picture', 'video']]]
        });
    });
</script>
<?= $this->endSection(); ?>
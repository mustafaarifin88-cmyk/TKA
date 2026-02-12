<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <h3>Buat Soal Esai</h3>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="sekolah_id" value="<?= $kelas['id'] ?>">
        <input type="hidden" name="mapel_id" value="<?= $mapel['id'] ?>">
        <input type="hidden" name="jenis" value="esai">

        <div id="questions-container"></div>

        <div class="row mt-4 mb-5">
            <div class="col-12 d-flex justify-content-between">
                <button type="button" class="btn btn-success btn-lg" id="btn-add-question"><i class="bi bi-plus-circle"></i> Tambah Soal</button>
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Simpan Semua</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('questions-container');
        const btnAdd = document.getElementById('btn-add-question');
        let questionCount = 0;

        function addQuestion() {
            questionCount++;
            let qIndex = questionCount - 1; 
            
            const card = document.createElement('div');
            card.className = 'card mb-5 border border-primary shadow-sm';
            card.innerHTML = `
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-white m-0">Soal Esai Nomor ${questionCount}</h5>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(this)"><i class="bi bi-trash"></i> Hapus</button>
                </div>
                <div class="card-body mt-3">
                    <div class="form-group">
                        <label class="form-label fw-bold">Pertanyaan</label>
                        <textarea name="pertanyaan[${qIndex}]" class="summernote-editor" required></textarea>
                    </div>
                </div>
            `;
            container.appendChild(card);
            $(card).find('.summernote-editor').summernote({
                placeholder: 'Tulis pertanyaan esai...', tabsize: 2, height: 250,
                toolbar: [['style', ['style']], ['font', ['bold', 'underline']], ['para', ['ul', 'ol', 'paragraph']], ['insert', ['link', 'picture', 'video']]]
            });
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        window.removeQuestion = function(btn) { if(confirm('Hapus?')) btn.closest('.card').remove(); }
        btnAdd.addEventListener('click', addQuestion);
        addQuestion();
    });
</script>
<?= $this->endSection(); ?>
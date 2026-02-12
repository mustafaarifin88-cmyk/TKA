<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <h3>Buat Soal Pilihan Ganda</h3>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="sekolah_id" value="<?= $kelas['id'] ?>">
        <input type="hidden" name="mapel_id" value="<?= $mapel['id'] ?>">
        <input type="hidden" name="jenis" value="pg">

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
                    <h5 class="card-title text-white m-0">Soal Nomor ${questionCount}</h5>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(this)"><i class="bi bi-trash"></i> Hapus</button>
                </div>
                <div class="card-body mt-3">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Konten Soal</label>
                        <textarea name="pertanyaan[${qIndex}]" class="summernote-editor" required></textarea>
                    </div>
                    <hr>
                    <label class="form-label fw-bold text-primary mb-3">Pilihan Jawaban</label>
                    <div class="row">
                        ${['a','b','c','d','e'].map(opsi => `
                            <div class="col-md-6 mb-3">
                                <div class="card p-2 border bg-light">
                                    <label class="fw-bold mb-1">Opsi ${opsi.toUpperCase()}</label>
                                    <textarea name="opsi_${opsi}[${qIndex}]" class="summernote-simple"></textarea>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label fw-bold text-success">Kunci Jawaban</label>
                        <select name="kunci_jawaban[${qIndex}]" class="form-select border-success" required>
                            <option value="">-- Pilih --</option>
                            <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option><option value="E">E</option>
                        </select>
                    </div>
                </div>
            `;
            container.appendChild(card);
            
            $(card).find('.summernote-editor').summernote({
                placeholder: 'Tulis soal...', tabsize: 2, height: 200,
                toolbar: [['style', ['style']], ['font', ['bold', 'underline', 'clear']], ['para', ['ul', 'ol', 'paragraph']], ['insert', ['link', 'picture']], ['view', ['fullscreen', 'codeview']]]
            });
            $(card).find('.summernote-simple').summernote({
                placeholder: 'Jawaban...', tabsize: 2, height: 100,
                toolbar: [['font', ['bold', 'italic']], ['insert', ['picture']]]
            });
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        window.removeQuestion = function(btn) { if(confirm('Hapus soal ini?')) btn.closest('.card').remove(); }
        btnAdd.addEventListener('click', addQuestion);
        addQuestion();
    });
</script>
<?= $this->endSection(); ?>
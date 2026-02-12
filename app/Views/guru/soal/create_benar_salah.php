<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <h3>Buat Soal Benar / Salah (Majemuk)</h3>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="sekolah_id" value="<?= $kelas['id'] ?>">
        <input type="hidden" name="mapel_id" value="<?= $mapel['id'] ?>">
        <input type="hidden" name="jenis" value="benar_salah">

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
            
            let tableRow = `
                <tr id="row_${qIndex}_0">
                    <td><textarea name="pernyataan_sub[${qIndex}][]" class="form-control" rows="2" placeholder="Isi pernyataan..." required></textarea></td>
                    <td class="text-center align-middle"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="kunci_sub[${qIndex}][0]" value="Benar" required><label class="form-check-label text-success fw-bold">Benar</label></div></td>
                    <td class="text-center align-middle"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="kunci_sub[${qIndex}][0]" value="Salah" required><label class="form-check-label text-danger fw-bold">Salah</label></div></td>
                    <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-light-danger" onclick="removeRow(this)"><i class="bi bi-x-lg"></i></button></td>
                </tr>
            `;

            card.innerHTML = `
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-white m-0">Soal Nomor ${questionCount}</h5>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(this)"><i class="bi bi-trash"></i> Hapus</button>
                </div>
                <div class="card-body mt-3">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Stimulus</label>
                        <textarea name="pertanyaan[${qIndex}]" class="summernote-editor" required></textarea>
                    </div>
                    <h6 class="mb-2 text-primary">Tabel Pernyataan</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="table_bs_${qIndex}">
                            <thead class="bg-light-primary text-center">
                                <tr><th width="60%">Pernyataan</th><th width="15%">Benar</th><th width="15%">Salah</th><th width="10%"><i class="bi bi-trash"></i></th></tr>
                            </thead>
                            <tbody>${tableRow}</tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-info text-white mt-2" onclick="addTableRow(${qIndex})"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
                    </div>
                </div>
            `;
            container.appendChild(card);
            $(card).find('.summernote-editor').summernote({ placeholder: 'Tulis stimulus...', tabsize: 2, height: 150, toolbar: [['style', ['style']], ['font', ['bold', 'underline']], ['insert', ['picture']]] });
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        window.removeQuestion = function(btn) { if(confirm('Hapus soal?')) btn.closest('.card').remove(); }
        window.removeRow = function(btn) { 
            let tbody = btn.closest('tbody');
            if (tbody.children.length > 1) btn.closest('tr').remove();
            else alert('Minimal satu pernyataan.');
        }
        window.addTableRow = function(qIndex) {
            const table = document.getElementById(`table_bs_${qIndex}`).getElementsByTagName('tbody')[0];
            const rowIndex = table.rows.length; 
            const newRow = table.insertRow();
            newRow.innerHTML = `
                <td><textarea name="pernyataan_sub[${qIndex}][]" class="form-control" rows="2" placeholder="Isi pernyataan..." required></textarea></td>
                <td class="text-center align-middle"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="kunci_sub[${qIndex}][${rowIndex}]" value="Benar" required><label class="form-check-label text-success fw-bold">Benar</label></div></td>
                <td class="text-center align-middle"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="kunci_sub[${qIndex}][${rowIndex}]" value="Salah" required><label class="form-check-label text-danger fw-bold">Salah</label></div></td>
                <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-light-danger" onclick="removeRow(this)"><i class="bi bi-x-lg"></i></button></td>
            `;
        }

        btnAdd.addEventListener('click', addQuestion);
        addQuestion();
    });
</script>
<?= $this->endSection(); ?>
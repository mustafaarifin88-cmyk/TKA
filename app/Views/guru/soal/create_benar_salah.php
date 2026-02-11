<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Soal Benar / Salah (Majemuk)</h3>
                <p class="text-subtitle text-muted">Mata Pelajaran: <strong><?= $mapel['nama_mapel'] ?></strong> | Kelas: <strong><?= $kelas['nama_kelas'] ?></strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/soal') ?>">Bank Soal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buat Soal B/S</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="kelas_id" value="<?= $kelas['id'] ?>">
        <input type="hidden" name="mapel_id" value="<?= $mapel['id'] ?>">
        <input type="hidden" name="jenis" value="benar_salah">

        <div id="questions-container"></div>

        <div class="row mt-4 mb-5">
            <div class="col-12 d-flex justify-content-between">
                <button type="button" class="btn btn-success btn-lg" id="btn-add-question">
                    <i class="bi bi-plus-circle"></i> Tambah Soal Berikutnya
                </button>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> Simpan Semua Soal
                </button>
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
            card.className = 'card mb-4 border border-secondary shadow-sm';
            
            let tableRow = `
                <tr id="row_${qIndex}_0">
                    <td>
                        <input type="text" name="pernyataan_sub[${qIndex}][]" class="form-control" placeholder="Isi pernyataan..." required>
                    </td>
                    <td class="text-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="kunci_sub[${qIndex}][0]" value="Benar" required>
                            <label class="form-check-label text-success fw-bold">Benar</label>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="kunci_sub[${qIndex}][0]" value="Salah" required>
                            <label class="form-check-label text-danger fw-bold">Salah</label>
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-light-danger" onclick="removeRow(this)">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </td>
                </tr>
            `;

            card.innerHTML = `
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Soal Nomor ${questionCount} (Benar/Salah Majemuk)</h5>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(this)">
                        <i class="bi bi-trash"></i> Hapus Soal
                    </button>
                </div>
                <div class="card-body mt-3">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Pertanyaan Utama (Stimulus)</label>
                        <textarea name="pertanyaan[${qIndex}]" class="form-control mb-2" rows="3" required placeholder="Contoh: Tentukan kebenaran dari pernyataan-pernyataan berikut..."></textarea>
                        <label class="text-sm text-muted"><i class="bi bi-image"></i> Gambar Pendukung (Opsional):</label>
                        <input type="file" name="file_soal[${qIndex}]" class="form-control form-control-sm w-50" accept="image/*">
                    </div>
                    
                    <h6 class="mb-2 text-primary">Tabel Pernyataan & Kunci Jawaban</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="table_bs_${qIndex}">
                            <thead class="bg-light-primary text-center">
                                <tr>
                                    <th width="60%">Pernyataan</th>
                                    <th width="15%">Benar</th>
                                    <th width="15%">Salah</th>
                                    <th width="10%"><i class="bi bi-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRow}
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-info text-white mt-2" onclick="addTableRow(${qIndex})">
                            <i class="bi bi-plus-lg"></i> Tambah Baris Pernyataan
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(card);
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        window.removeQuestion = function(btn) {
            if(confirm('Hapus soal ini?')) btn.closest('.card').remove();
        }

        window.removeRow = function(btn) {
            let tbody = btn.closest('tbody');
            if (tbody.children.length > 1) {
                btn.closest('tr').remove();
            } else {
                alert('Minimal harus ada satu pernyataan.');
            }
        }

        window.addTableRow = function(qIndex) {
            const table = document.getElementById(`table_bs_${qIndex}`).getElementsByTagName('tbody')[0];
            const rowIndex = table.rows.length; 
            
            const newRow = table.insertRow();
            newRow.innerHTML = `
                <td>
                    <input type="text" name="pernyataan_sub[${qIndex}][]" class="form-control" placeholder="Isi pernyataan..." required>
                </td>
                <td class="text-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kunci_sub[${qIndex}][${rowIndex}]" value="Benar" required>
                        <label class="form-check-label text-success fw-bold">Benar</label>
                    </div>
                </td>
                <td class="text-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kunci_sub[${qIndex}][${rowIndex}]" value="Salah" required>
                        <label class="form-check-label text-danger fw-bold">Salah</label>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-light-danger" onclick="removeRow(this)">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </td>
            `;
        }

        btnAdd.addEventListener('click', addQuestion);
        addQuestion();
    });
</script>
<?= $this->endSection(); ?>
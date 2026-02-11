<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Soal Pilihan Ganda Kompleks</h3>
                <p class="text-subtitle text-muted">Mata Pelajaran: <strong><?= $mapel['nama_mapel'] ?></strong> | Kelas: <strong><?= $kelas['nama_kelas'] ?></strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/soal') ?>">Bank Soal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buat Soal PG Kompleks</li>
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
        <input type="hidden" name="jenis" value="pg_kompleks">

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
            
            card.innerHTML = `
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Soal Nomor ${questionCount} (PG Kompleks)</h5>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(this)">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
                <div class="card-body mt-3">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Pertanyaan</label>
                        <textarea name="pertanyaan[${qIndex}]" class="form-control mb-2" rows="3" required placeholder="Tulis pertanyaan disini..."></textarea>
                        <label class="text-sm text-muted"><i class="bi bi-image"></i> Gambar Soal (Opsional):</label>
                        <input type="file" name="file_soal[${qIndex}]" class="form-control form-control-sm w-50" accept="image/*">
                    </div>
                    
                    <div class="alert alert-light-primary color-primary"><i class="bi bi-info-circle"></i> Centang kotak di sebelah kanan untuk menandai jawaban benar (Bisa lebih dari satu).</div>

                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 border mb-3 bg-light-primary">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold m-0">Pilihan A</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kunci_jawaban[${qIndex}][]" value="A" id="check_A_${qIndex}">
                                        <label class="form-check-label text-success fw-bold" for="check_A_${qIndex}">Benar</label>
                                    </div>
                                </div>
                                <div class="input-group mb-1">
                                    <span class="input-group-text">A</span>
                                    <input type="text" name="opsi_a[${qIndex}]" class="form-control" required placeholder="Teks Jawaban A">
                                </div>
                                <input type="file" name="file_a[${qIndex}]" class="form-control form-control-sm" accept="image/*">
                            </div>

                            <div class="card p-2 border mb-3 bg-light-primary">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold m-0">Pilihan B</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kunci_jawaban[${qIndex}][]" value="B" id="check_B_${qIndex}">
                                        <label class="form-check-label text-success fw-bold" for="check_B_${qIndex}">Benar</label>
                                    </div>
                                </div>
                                <div class="input-group mb-1">
                                    <span class="input-group-text">B</span>
                                    <input type="text" name="opsi_b[${qIndex}]" class="form-control" required placeholder="Teks Jawaban B">
                                </div>
                                <input type="file" name="file_b[${qIndex}]" class="form-control form-control-sm" accept="image/*">
                            </div>

                            <div class="card p-2 border mb-3 bg-light-primary">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold m-0">Pilihan C</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kunci_jawaban[${qIndex}][]" value="C" id="check_C_${qIndex}">
                                        <label class="form-check-label text-success fw-bold" for="check_C_${qIndex}">Benar</label>
                                    </div>
                                </div>
                                <div class="input-group mb-1">
                                    <span class="input-group-text">C</span>
                                    <input type="text" name="opsi_c[${qIndex}]" class="form-control" required placeholder="Teks Jawaban C">
                                </div>
                                <input type="file" name="file_c[${qIndex}]" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card p-2 border mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold m-0">Pilihan D</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kunci_jawaban[${qIndex}][]" value="D" id="check_D_${qIndex}">
                                        <label class="form-check-label text-success fw-bold" for="check_D_${qIndex}">Benar</label>
                                    </div>
                                </div>
                                <div class="input-group mb-1">
                                    <span class="input-group-text">D</span>
                                    <input type="text" name="opsi_d[${qIndex}]" class="form-control" placeholder="Teks Jawaban D">
                                </div>
                                <input type="file" name="file_d[${qIndex}]" class="form-control form-control-sm" accept="image/*">
                            </div>

                            <div class="card p-2 border mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold m-0">Pilihan E</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kunci_jawaban[${qIndex}][]" value="E" id="check_E_${qIndex}">
                                        <label class="form-check-label text-success fw-bold" for="check_E_${qIndex}">Benar</label>
                                    </div>
                                </div>
                                <div class="input-group mb-1">
                                    <span class="input-group-text">E</span>
                                    <input type="text" name="opsi_e[${qIndex}]" class="form-control" placeholder="Teks Jawaban E">
                                </div>
                                <input type="file" name="file_e[${qIndex}]" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(card);
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        window.removeQuestion = function(btn) {
            if(confirm('Hapus soal ini?')) {
                btn.closest('.card').remove();
            }
        }

        btnAdd.addEventListener('click', addQuestion);
        addQuestion();
    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Soal Esai</h3>
                <p class="text-subtitle text-muted">Mata Pelajaran: <strong><?= $mapel['nama_mapel'] ?></strong> | Kelas: <strong><?= $kelas['nama_kelas'] ?></strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/soal') ?>">Bank Soal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buat Soal Esai</li>
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
        <input type="hidden" name="jenis" value="esai">

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
            
            const card = document.createElement('div');
            card.className = 'card mb-4 border border-secondary shadow-sm';
            card.innerHTML = `
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Soal Esai Nomor ${questionCount}</h5>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(this)">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
                <div class="card-body mt-3">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Pertanyaan</label>
                        <textarea name="pertanyaan[]" class="form-control mb-3" rows="5" required placeholder="Tulis pertanyaan esai disini..."></textarea>
                        
                        <label class="text-sm text-muted"><i class="bi bi-image"></i> Gambar Pendukung (Opsional):</label>
                        <input type="file" name="file_soal[]" class="form-control w-50" accept="image/*">
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
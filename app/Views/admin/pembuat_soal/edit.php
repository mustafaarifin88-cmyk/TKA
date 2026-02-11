<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Soal</h3>
                <p class="text-subtitle text-muted">Perbarui data butir soal yang sudah ada.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/pembuat_soal') ?>">Bank Soal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Formulir Perubahan Soal</h4>
                        <a href="<?= base_url('admin/pembuat_soal') ?>" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('errors')) : ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('admin/pembuat_soal/update/' . $soal['id']) ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="PUT">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Mata Pelajaran</label>
                                    <select name="mapel_id" class="form-select" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        <?php foreach ($mapel as $m) : ?>
                                            <option value="<?= $m['id'] ?>" <?= (old('mapel_id') ?? $soal['mapel_id']) == $m['id'] ? 'selected' : '' ?>><?= $m['nama_mapel'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tingkat Kesulitan</label>
                                    <select name="kesulitan" class="form-select" required>
                                        <option value="mudah" <?= (old('kesulitan') ?? $soal['kesulitan']) == 'mudah' ? 'selected' : '' ?>>Mudah</option>
                                        <option value="sedang" <?= (old('kesulitan') ?? $soal['kesulitan']) == 'sedang' ? 'selected' : '' ?>>Sedang</option>
                                        <option value="sulit" <?= (old('kesulitan') ?? $soal['kesulitan']) == 'sulit' ? 'selected' : '' ?>>Sulit</option>
                                    </select>
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="form-label fw-bold">Pertanyaan</label>
                                    <textarea name="pertanyaan" id="editor" class="form-control" rows="4" required><?= old('pertanyaan') ?? $soal['pertanyaan'] ?></textarea>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Gambar Pendukung</label>
                                    <?php if ($soal['gambar']): ?>
                                        <div class="mb-2">
                                            <img src="<?= base_url('uploads/soal/' . $soal['gambar']) ?>" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                                            <p class="text-muted small">Gambar saat ini. Biarkan kosong jika tidak ingin mengubah.</p>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="gambar" class="form-control">
                                    <small class="text-muted">Format: jpg, jpeg, png. Maks: 2MB</small>
                                </div>

                                <hr>
                                <h5 class="mb-3">Pilihan Jawaban</h5>

                                <?php $options = ['a', 'b', 'c', 'd', 'e']; ?>
                                <?php foreach ($options as $opt) : ?>
                                    <div class="col-12 mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold text-uppercase"><?= $opt ?></span>
                                            <input type="text" name="opsi_<?= $opt ?>" class="form-control" placeholder="Teks jawaban <?= $opt ?>" value="<?= old('opsi_'.$opt) ?? $soal['opsi_'.$opt] ?>" required>
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="radio" name="jawaban_benar" value="<?= $opt ?>" <?= (old('jawaban_benar') ?? $soal['jawaban_benar']) == $opt ? 'checked' : '' ?> required>
                                                <small class="ms-2">Benar</small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="col-12 mt-4 d-flex justify-content-end">
                                    <a href="<?= base_url('admin/pembuat_soal') ?>" class="btn btn-light-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-warning px-5 text-white">Update Soal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
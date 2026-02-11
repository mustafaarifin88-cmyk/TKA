<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Soal Baru</h3>
                <p class="text-subtitle text-muted">Tambahkan butir soal baru ke dalam bank soal.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/pembuat_soal') ?>">Bank Soal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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
                        <h4 class="card-title">Formulir Input Soal</h4>
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

                        <form action="<?= base_url('admin/pembuat_soal/store') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Mata Pelajaran</label>
                                    <select name="mapel_id" class="form-select" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        <?php foreach ($mapel as $m) : ?>
                                            <option value="<?= $m['id'] ?>" <?= old('mapel_id') == $m['id'] ? 'selected' : '' ?>><?= $m['nama_mapel'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tingkat Kesulitan</label>
                                    <select name="kesulitan" class="form-select" required>
                                        <option value="mudah" <?= old('kesulitan') == 'mudah' ? 'selected' : '' ?>>Mudah</option>
                                        <option value="sedang" <?= old('kesulitan') == 'sedang' ? 'selected' : '' ?>>Sedang</option>
                                        <option value="sulit" <?= old('kesulitan') == 'sulit' ? 'selected' : '' ?>>Sulit</option>
                                    </select>
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="form-label fw-bold">Pertanyaan</label>
                                    <textarea name="pertanyaan" id="editor" class="form-control" rows="4" required><?= old('pertanyaan') ?></textarea>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Gambar Pendukung (Opsional)</label>
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
                                            <input type="text" name="opsi_<?= $opt ?>" class="form-control" placeholder="Teks jawaban <?= $opt ?>" value="<?= old('opsi_'.$opt) ?>" required>
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="radio" name="jawaban_benar" value="<?= $opt ?>" <?= old('jawaban_benar') == $opt ? 'checked' : '' ?> required>
                                                <small class="ms-2">Benar</small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="col-12 mt-4 d-flex justify-content-end">
                                    <button type="reset" class="btn btn-light-secondary me-2">Reset</button>
                                    <button type="submit" class="btn btn-primary px-5">Simpan Soal</button>
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
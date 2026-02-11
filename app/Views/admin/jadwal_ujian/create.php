<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Jadwal Ujian</h3>
                <p class="text-subtitle text-muted">Buat jadwal pelaksanaan ujian baru untuk siswa.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/jadwal_ujian') ?>">Jadwal Ujian</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title">Formulir Jadwal Ujian</h4>
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

                        <form action="<?= base_url('admin/jadwal_ujian/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mapel_id" class="form-label fw-bold">Mata Pelajaran</label>
                                    <select name="mapel_id" id="mapel_id" class="form-select <?= (isset($validation) && $validation->hasError('mapel_id')) ? 'is-invalid' : '' ?>" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        <?php foreach ($mapel as $m) : ?>
                                            <option value="<?= $m['id'] ?>" <?= old('mapel_id') == $m['id'] ? 'selected' : '' ?>><?= $m['nama_mapel'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="kelas_id" class="form-label fw-bold">Kelas</label>
                                    <select name="kelas_id" id="kelas_id" class="form-select <?= (isset($validation) && $validation->hasError('kelas_id')) ? 'is-invalid' : '' ?>" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php foreach ($kelas as $k) : ?>
                                            <option value="<?= $k['id'] ?>" <?= old('kelas_id') == $k['id'] ? 'selected' : '' ?>><?= $k['nama_kelas'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="tanggal" class="form-label fw-bold">Tanggal Ujian</label>
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= old('tanggal') ?>" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="jam_mulai" class="form-label fw-bold">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?= old('jam_mulai') ?>" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="durasi" class="form-label fw-bold">Durasi (Menit)</label>
                                    <div class="input-group">
                                        <input type="number" name="durasi" id="durasi" class="form-control" placeholder="Contoh: 90" value="<?= old('durasi') ?>" required>
                                        <span class="input-group-text">Menit</span>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label for="token" class="form-label fw-bold">Token (Opsional)</label>
                                    <input type="text" name="token" id="token" class="form-control" maxlength="6" placeholder="Biarkan kosong untuk generate otomatis" value="<?= old('token') ?>">
                                    <small class="text-muted italic">*Token digunakan siswa untuk mengakses ujian.</small>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <a href="<?= base_url('admin/jadwal_ujian') ?>" class="btn btn-light-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary px-4">Simpan Jadwal</button>
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
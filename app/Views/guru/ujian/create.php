<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Jadwal Ujian</h3>
                <p class="text-subtitle text-muted">Atur jadwal pelaksanaan ujian baru.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('guru/ujian') ?>">Jadwal Ujian</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buat Baru</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('guru/ujian/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="kelas_id" class="form-label">Pilih Kelas</label>
                                <select name="kelas_id" id="kelas_id" class="form-select" required onchange="getMapelByKelas(this.value)">
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($kelas as $k) : ?>
                                        <option value="<?= $k['id'] ?>" <?= old('kelas_id') == $k['id'] ? 'selected' : '' ?>><?= $k['nama_kelas'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="mapel_id" class="form-label">Pilih Mata Pelajaran</label>
                                <select name="mapel_id" id="mapel_id" class="form-select" required disabled>
                                    <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tanggal_ujian" class="form-label">Tanggal Ujian</label>
                                <input type="date" name="tanggal_ujian" id="tanggal_ujian" class="form-control" value="<?= old('tanggal_ujian') ?>" required min="<?= date('Y-m-d') ?>">
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?= old('jam_mulai') ?>" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="lama_ujian" class="form-label">Durasi (Menit)</label>
                                        <input type="number" name="lama_ujian" id="lama_ujian" class="form-control" value="<?= old('lama_ujian') ?>" placeholder="Contoh: 90" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-end mt-4">
                        <a href="<?= base_url('guru/ujian') ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    function getMapelByKelas(kelasId) {
        const mapelSelect = document.getElementById('mapel_id');
        
        if (!kelasId) {
            mapelSelect.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
            mapelSelect.disabled = true;
            return;
        }

        mapelSelect.innerHTML = '<option value="">Memuat data...</option>';
        mapelSelect.disabled = true;

        fetch(`<?= base_url('guru/ujian/getMapelByKelas') ?>/${kelasId}`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Pilih Mata Pelajaran --</option>';
                if (data.length > 0) {
                    data.forEach(item => {
                        options += `<option value="${item.id}">${item.nama_mapel}</option>`;
                    });
                    mapelSelect.disabled = false;
                } else {
                    options = '<option value="">Tidak ada mapel untuk kelas ini</option>';
                }
                mapelSelect.innerHTML = options;
            })
            .catch(error => {
                console.error('Error:', error);
                mapelSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    }
</script>
<?= $this->endSection(); ?>
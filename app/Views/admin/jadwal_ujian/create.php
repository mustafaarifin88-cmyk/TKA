<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Jadwal Ujian</h3>
                <p class="text-subtitle text-muted">Atur pelaksanaan ujian untuk sekolah dan mata pelajaran tertentu.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/jadwal') ?>">Jadwal Ujian</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buat Baru</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom pb-3">
                <h5 class="card-title m-0 text-primary">
                    <i class="bi bi-calendar-plus-fill me-2"></i> Form Pengaturan Jadwal
                </h5>
            </div>
            <div class="card-body pt-4">
                
                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Terjadi Kesalahan Input</h6>
                                <ul class="mb-0 ps-3">
                                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/jadwal/store') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 border-end-md">
                            <h6 class="text-muted mb-3"><i class="bi bi-bookmarks-fill me-1"></i> Data Akademik</h6>
                            
                            <div class="form-group mb-4">
                                <label for="sekolah_id" class="form-label fw-bold">Pilih Sekolah</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                    <select name="sekolah_id" id="sekolah_id" class="form-select" required onchange="getMapelBySekolah(this.value)">
                                        <option value="">-- Pilih Sekolah --</option>
                                        <?php foreach ($sekolah as $s) : ?>
                                            <option value="<?= $s['id'] ?>" <?= old('sekolah_id') == $s['id'] ? 'selected' : '' ?>>
                                                <?= $s['nama_sekolah'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <small class="text-muted">Pilih sekolah terlebih dahulu untuk memunculkan mapel.</small>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="mapel_id" class="form-label fw-bold">Pilih Mata Pelajaran</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-book"></i></span>
                                    <select name="mapel_id" id="mapel_id" class="form-select" required disabled>
                                        <option value="">-- Pilih Sekolah Terlebih Dahulu --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 ps-md-4">
                            <h6 class="text-muted mb-3"><i class="bi bi-clock-history me-1"></i> Waktu Pelaksanaan</h6>
                            
                            <div class="form-group mb-4">
                                <label for="tanggal_ujian" class="form-label fw-bold">Tanggal Ujian</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" name="tanggal_ujian" id="tanggal_ujian" class="form-control" 
                                           value="<?= old('tanggal_ujian') ?>" required min="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-4">
                                        <label for="jam_mulai" class="form-label fw-bold">Jam Mulai</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-alarm"></i></span>
                                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" 
                                                   value="<?= old('jam_mulai') ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-4">
                                        <label for="lama_ujian" class="form-label fw-bold">Durasi (Menit)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-stopwatch"></i></span>
                                            <input type="number" name="lama_ujian" id="lama_ujian" class="form-control" 
                                                   value="<?= old('lama_ujian') ?>" placeholder="90" required min="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('admin/jadwal') ?>" class="btn btn-light-secondary px-4">
                            <i class="bi bi-x me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4 shadow">
                            <i class="bi bi-save me-1"></i> Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    function getMapelBySekolah(sekolahId) {
        const mapelSelect = document.getElementById('mapel_id');
        
        mapelSelect.innerHTML = '<option value="">Memuat data...</option>';
        mapelSelect.disabled = true;

        if (!sekolahId) {
            mapelSelect.innerHTML = '<option value="">-- Pilih Sekolah Terlebih Dahulu --</option>';
            return;
        }

        fetch(`<?= base_url('admin/jadwal/getMapelBySekolah') ?>/${sekolahId}`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Pilih Mata Pelajaran --</option>';
                
                if (data.length > 0) {
                    data.forEach(item => {
                        options += `<option value="${item.id}">${item.nama_mapel}</option>`;
                    });
                    mapelSelect.disabled = false;
                } else {
                    options = '<option value="">Tidak ada mapel untuk sekolah ini</option>';
                }
                
                mapelSelect.innerHTML = options;
            })
            .catch(error => {
                console.error('Error:', error);
                mapelSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    }
</script>

<style>
    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #dee2e6;
        }
    }
</style>
<?= $this->endSection(); ?>
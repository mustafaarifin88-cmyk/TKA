<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Jadwal Ujian</h3>
                <p class="text-subtitle text-muted">Atur waktu dan durasi pelaksanaan ujian untuk setiap sekolah.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jadwal Ujian</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 text-primary">
                    <i class="bi bi-calendar-week-fill me-2"></i> Daftar Jadwal Aktif
                </h5>
                <a href="<?= base_url('admin/jadwal/create') ?>" class="btn btn-primary shadow-sm rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> Buat Jadwal Baru
                </a>
            </div>
            <div class="card-body pt-4">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                            <span><?= session()->getFlashdata('success') ?></span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="table1">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="25%">Detail Akademik</th>
                                <th width="25%">Waktu Pelaksanaan</th>
                                <th class="text-center">Durasi</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jadwal as $key => $j) : ?>
                                <tr>
                                    <td class="text-center fw-bold text-muted"><?= $key + 1 ?></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark mb-1"><?= $j['nama_mapel'] ?></span>
                                            <span class="badge bg-light-info text-info w-auto align-self-start">
                                                <i class="bi bi-building-fill me-1"></i> <?= $j['nama_sekolah'] ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark mb-1">
                                                <i class="bi bi-calendar-event me-2 text-primary"></i>
                                                <?= date('d M Y', strtotime($j['tanggal_ujian'])) ?>
                                            </span>
                                            <span class="text-muted small">
                                                <i class="bi bi-clock me-2 text-warning"></i>
                                                Mulai: <?= date('H:i', strtotime($j['jam_mulai'])) ?> WIB
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light-secondary text-secondary border border-secondary rounded-pill px-3">
                                            <i class="bi bi-stopwatch me-1"></i> <?= $j['lama_ujian'] ?> Menit
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                            $waktuUjian = strtotime($j['tanggal_ujian'] . ' ' . $j['jam_mulai']);
                                            $waktuSelesai = $waktuUjian + ($j['lama_ujian'] * 60);
                                            $sekarang = time();
                                        ?>
                                        
                                        <?php if ($sekarang > $waktuSelesai) : ?>
                                            <span class="badge bg-secondary">Selesai</span>
                                        <?php elseif ($sekarang >= $waktuUjian && $sekarang <= $waktuSelesai) : ?>
                                            <span class="badge bg-success spinner-grow-sm">
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                                Berlangsung
                                            </span>
                                        <?php else : ?>
                                            <span class="badge bg-info">Terjadwal</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/jadwal/delete/' . $j['id']) ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ujian ini? Data nilai siswa terkait mungkin akan hilang.')" 
                                           class="btn btn-sm btn-danger shadow-sm" 
                                           data-bs-toggle="tooltip" 
                                           title="Hapus Jadwal">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?= base_url('assets/extensions/simple-datatables/umd/simple-datatables.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/simple-datatables.js') ?>"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?= $this->endSection(); ?>
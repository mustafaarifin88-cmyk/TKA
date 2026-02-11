<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Daftar Ujian</h3>
                <p class="text-subtitle text-muted">Berikut adalah daftar ujian yang tersedia untuk Anda.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('siswa/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar Ujian</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php if (empty($ujian)) : ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <h4 class="alert-heading">Tidak Ada Ujian</h4>
                        <p>Saat ini tidak ada jadwal ujian yang aktif untuk kelas Anda.</p>
                    </div>
                </div>
            <?php else : ?>
                <?php foreach ($ujian as $u) : ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-1 border-light">
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title mb-1"><?= $u['nama_mapel'] ?></h4>
                                    <p class="text-muted small mb-3">Guru: <?= $u['nama_guru'] ?></p>
                                    
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            Tanggal
                                            <span class="fw-bold"><?= date('d M Y', strtotime($u['tanggal_ujian'])) ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            Waktu Mulai
                                            <span class="fw-bold"><?= date('H:i', strtotime($u['jam_mulai'])) ?> WIB</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            Durasi
                                            <span class="fw-bold"><?= $u['lama_ujian'] ?> Menit</span>
                                        </li>
                                    </ul>

                                    <div class="d-grid gap-2">
                                        <?php 
                                            $waktuSekarang = date('Y-m-d H:i:s');
                                            $waktuMulai = $u['tanggal_ujian'] . ' ' . $u['jam_mulai'];
                                        ?>

                                        <?php if ($u['status_siswa'] == 'selesai') : ?>
                                            <button class="btn btn-secondary" disabled>
                                                <i class="bi bi-check-circle-fill"></i> Sudah Dikerjakan
                                            </button>
                                        <?php elseif ($waktuSekarang < $waktuMulai) : ?>
                                            <button class="btn btn-warning" disabled>
                                                <i class="bi bi-clock"></i> Belum Dimulai
                                            </button>
                                            <small class="text-center text-muted">Dimulai pada <?= date('H:i', strtotime($u['jam_mulai'])) ?></small>
                                        <?php else : ?>
                                            <a href="<?= base_url('siswa/ujian/token/' . $u['id']) ?>" class="btn btn-primary">
                                                <i class="bi bi-play-circle-fill"></i> 
                                                <?= ($u['status_siswa'] == 'sedang_mengerjakan') ? 'Lanjutkan Ujian' : 'Mulai Kerjakan' ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
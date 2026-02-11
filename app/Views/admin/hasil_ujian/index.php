<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Monitoring & Hasil Ujian</h3>
                <p class="text-subtitle text-muted">Pantau progres dan nilai siswa secara real-time.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Hasil Ujian</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom pb-3">
            <h5 class="card-title m-0 text-primary"><i class="bi bi-funnel-fill me-2"></i> Filter Data</h5>
        </div>
        <div class="card-body pt-4">
            <form method="get" action="" class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark">Pilih Sekolah</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                        <select name="sekolah_id" class="form-select" required>
                            <option value="">-- Semua Sekolah --</option>
                            <?php foreach ($sekolah as $s) : ?>
                                <option value="<?= $s['id'] ?>" <?= ($selected_sekolah == $s['id']) ? 'selected' : '' ?>>
                                    <?= $s['nama_sekolah'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark">Pilih Mata Pelajaran</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-book"></i></span>
                        <select name="mapel_id" class="form-select" required>
                            <option value="">-- Semua Mapel --</option>
                            <?php foreach ($mapel as $m) : ?>
                                <option value="<?= $m['id'] ?>" <?= ($selected_mapel == $m['id']) ? 'selected' : '' ?>>
                                    <?= $m['nama_mapel'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                        <i class="bi bi-search me-2"></i> Tampilkan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($siswa_data)) : ?>
        <div class="card shadow border-0">
            <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div>
                    <h5 class="card-title m-0 mb-1 text-dark">
                        <i class="bi bi-table me-2 text-success"></i> Hasil Ujian
                    </h5>
                    <?php if($jadwal_info): ?>
                        <small class="text-muted">
                            <i class="bi bi-calendar-check me-1"></i> Tanggal: <?= date('d M Y', strtotime($jadwal_info['tanggal_ujian'])) ?> 
                            <span class="mx-2">|</span>
                            <i class="bi bi-clock me-1"></i> Mulai: <?= date('H:i', strtotime($jadwal_info['jam_mulai'])) ?>
                        </small>
                    <?php endif; ?>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="<?= base_url('admin/hasil/cetak/' . $selected_sekolah . '/' . $selected_mapel) ?>" target="_blank" class="btn btn-danger shadow-sm me-2">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> Export PDF
                    </a>
                    
                    <a href="<?= base_url('admin/hasil/export_excel/' . $selected_sekolah . '/' . $selected_mapel) ?>" target="_blank" class="btn btn-success shadow-sm">
                        <i class="bi bi-file-earmark-excel-fill me-2"></i> Export Excel
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-center" width="5%">No</th>
                                <th class="py-3">Identitas Siswa</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-center">Waktu Mulai</th>
                                <th class="py-3 text-center text-primary fw-bold" width="15%">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($siswa_data as $key => $s) : ?>
                                <tr>
                                    <td class="text-center fw-bold text-muted"><?= $key + 1 ?></td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md bg-primary me-3">
                                                <span class="avatar-content fw-bold text-white"><?= substr($s['nama'], 0, 1) ?></span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-dark"><?= $s['nama'] ?></h6>
                                                <small class="text-muted">NISN: <?= $s['nisn'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($s['status'] == 'selesai') : ?>
                                            <span class="badge bg-light-success text-success border border-success rounded-pill px-3">
                                                <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                            </span>
                                        <?php elseif ($s['status'] == 'sedang_mengerjakan') : ?>
                                            <span class="badge bg-light-warning text-warning border border-warning rounded-pill px-3">
                                                <i class="bi bi-hourglass-split me-1 spinner-border spinner-border-sm" style="font-size: 0.7rem;"></i> Sedang Ujian
                                            </span>
                                        <?php else : ?>
                                            <span class="badge bg-light-secondary text-secondary border border-secondary rounded-pill px-3">
                                                <i class="bi bi-dash-circle me-1"></i> Belum Ujian
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center text-muted font-monospace">
                                        <?= ($s['waktu_mulai'] && $s['waktu_mulai'] != '-') ? date('H:i', strtotime($s['waktu_mulai'])) : '-' ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($s['status'] == 'selesai'): ?>
                                            <h5 class="mb-0 fw-bold text-primary"><?= number_format($s['nilai_akhir'], 2) ?></h5>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php elseif ($selected_sekolah && $selected_mapel) : ?>
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <img src="<?= base_url('assets/static/images/samples/error-404.svg') ?>" alt="No Data" style="height: 150px; opacity: 0.5;">
                <h5 class="mt-4 text-muted">Belum ada Jadwal Ujian</h5>
                <p class="text-muted mb-0">Tidak ditemukan jadwal ujian aktif untuk Sekolah & Mata Pelajaran yang dipilih.</p>
                <a href="<?= base_url('admin/jadwal/create') ?>" class="btn btn-outline-primary mt-3 rounded-pill">
                    <i class="bi bi-plus-lg me-1"></i> Buat Jadwal Sekarang
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-light-info color-info border-0 shadow-sm text-center py-4">
            <i class="bi bi-info-circle-fill fs-3 d-block mb-2 text-info"></i>
            Silakan pilih <strong>Sekolah</strong> dan <strong>Mata Pelajaran</strong> terlebih dahulu untuk melihat hasil ujian.
        </div>
    <?php endif; ?>

</div>

<style>
    @media print {
        .sidebar, .navbar, .page-heading, .card-header button, form {
            display: none !important;
        }
        .page-content, .card, .card-body, .table-responsive {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            border-bottom: 2px solid #000 !important;
        }
    }
</style>
<?= $this->endSection(); ?>
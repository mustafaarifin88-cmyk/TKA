<?= $this->extend('layouts/ujian'); ?>

<?= $this->section('content'); ?>
<!-- Header Biru (Primary) -->
<nav class="navbar navbar-dark bg-primary shadow-sm mb-4" style="height: 80px;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <?php if (!empty($sekolah['logo']) && file_exists('uploads/sekolah/' . $sekolah['logo'])) : ?>
                <img src="<?= base_url('uploads/sekolah/' . $sekolah['logo']) ?>" alt="Logo" style="height: 50px;" class="me-3 bg-white rounded-circle p-1">
            <?php else: ?>
                <img src="<?= base_url('assets/static/images/logo/logo.png') ?>" alt="Logo" style="height: 50px;" class="me-3 bg-white rounded-circle p-1">
            <?php endif; ?>
            <div class="d-flex flex-column justify-content-center">
                <h5 class="m-0 fw-bold text-white" style="line-height: 1;"><?= $sekolah['nama_sekolah'] ?></h5>
                <small class="text-white-50" style="font-size: 0.85rem;">Computer Based Test (CBT)</small>
            </div>
        </a>
        <div class="d-none d-md-flex align-items-center gap-3">
             <div class="text-end text-white">
                 <span class="d-block fw-bold"><?= $siswa['nama_lengkap'] ?></span>
                 <span class="d-block text-white-50 small"><?= $siswa['username'] ?></span>
             </div>
             <div class="vr text-white opacity-50"></div>
             <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm border-0">
                <i class="bi bi-power me-1"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white text-center py-4 border-bottom-0">
                    <h4 class="mb-1 fw-bold text-primary">Konfirmasi Ujian</h4>
                    <p class="text-muted mb-0">Silakan periksa data ujian Anda sebelum memulai.</p>
                </div>
                <div class="card-body p-4 p-md-5 pt-0">
                    
                    <div id="alert-container"></div>

                    <form id="form-konfirmasi">
                        <?= csrf_field() ?>
                        
                        <div class="bg-light p-3 rounded-3 mb-4 border border-dashed">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Nama Peserta</span>
                                <span class="fw-bold text-dark"><?= $siswa['nama_lengkap'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">NISN / Username</span>
                                <span class="fw-bold text-dark"><?= $siswa['nisn'] ?? $siswa['username'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Asal Sekolah</span>
                                <span class="fw-bold text-dark"><?= $sekolah['nama_sekolah'] ?></span>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" placeholder="Tanggal Lahir" required>
                            <label for="tanggal_lahir">Konfirmasi Tanggal Lahir</label>
                        </div>

                        <div class="form-floating mb-4">
                            <select name="mapel_id" id="mapel_id" class="form-select" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                <?php foreach ($mapel as $m) : ?>
                                    <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="mapel_id">Mata Pelajaran yang Diujikan</label>
                        </div>

                        <!-- Status Area -->
                        <div id="status-area" class="d-none text-center mb-4">
                            <div id="countdown-box" class="alert alert-warning d-none fade show shadow-sm">
                                <h6 class="alert-heading fw-bold"><i class="bi bi-alarm"></i> Ujian Belum Dimulai</h6>
                                <p class="mb-1 small">Hitung mundur menuju waktu ujian:</p>
                                <h2 id="timer" class="fw-bold mb-0 font-monospace text-dark">00:00:00</h2>
                            </div>
                            
                            <div id="msg-box" class="alert d-none fade show"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" id="btn-cek" class="btn btn-primary btn-lg shadow-sm rounded-pill">
                                <i class="bi bi-search me-2"></i> Cek Ketersediaan Ujian
                            </button>
                            <button type="button" id="btn-mulai" class="btn btn-success btn-lg shadow rounded-pill pulse-button d-none">
                                <i class="bi bi-play-fill me-1"></i> MULAI MENGERJAKAN
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            <div class="text-center mt-4 text-muted small opacity-50">
                &copy; <?= date('Y') ?> <?= $sekolah['nama_sekolah'] ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        const btnCek = $('#btn-cek');
        const btnMulai = $('#btn-mulai');
        const statusArea = $('#status-area');
        const msgBox = $('#msg-box');
        const countdownBox = $('#countdown-box');
        const timerDisplay = $('#timer');

        btnCek.on('click', function() {
            const tglLahir = $('#tanggal_lahir').val();
            const mapelId = $('#mapel_id').val();

            if (!tglLahir || !mapelId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon isi Tanggal Lahir dan pilih Mata Pelajaran.',
                    confirmButtonColor: '#435ebe'
                });
                return;
            }

            // Loading State
            btnCek.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memeriksa...');
            statusArea.addClass('d-none');
            msgBox.addClass('d-none').removeClass('alert-danger alert-info alert-success');
            countdownBox.addClass('d-none');
            btnMulai.addClass('d-none');

            $.ajax({
                url: '<?= base_url('siswa/dashboard/cek_konfirmasi') ?>',
                type: 'POST',
                data: {
                    tanggal_lahir: tglLahir,
                    mapel_id: mapelId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    statusArea.removeClass('d-none');

                    if (response.status === 'error') {
                        msgBox.removeClass('d-none').addClass('alert-danger').html('<i class="bi bi-exclamation-circle-fill me-2"></i> ' + response.message);
                    } else if (response.status === 'no_schedule') {
                        msgBox.removeClass('d-none').addClass('alert-danger').html('<i class="bi bi-calendar-x me-2"></i> ' + response.message);
                    } else if (response.status === 'finished') {
                        msgBox.removeClass('d-none').addClass('alert-info').html('<i class="bi bi-check-circle-fill me-2"></i> ' + response.message);
                    } else if (response.status === 'countdown') {
                        countdownBox.removeClass('d-none');
                        startCountdown(response.waktu_mulai);
                    } else if (response.status === 'ready') {
                        msgBox.removeClass('d-none').addClass('alert-success').html('<strong>Data Valid!</strong> Ujian tersedia, silakan mulai.');
                        btnMulai.removeClass('d-none');
                        btnCek.addClass('d-none'); 
                        
                        btnMulai.off('click').on('click', function() {
                            window.location.href = response.redirect_url;
                        });
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Gagal terhubung ke server.', 'error');
                },
                complete: function() {
                    btnCek.prop('disabled', false).html('<i class="bi bi-search me-2"></i> Cek Ketersediaan Ujian');
                }
            });
        });

        let countdownInterval;
        function startCountdown(startTimeStr) {
            clearInterval(countdownInterval);
            const targetDate = new Date(startTimeStr.replace(/-/g, "/")).getTime();
            countdownInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = targetDate - now;
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    timerDisplay.text("00:00:00");
                    Swal.fire({ title: 'Waktu Ujian Tiba!', text: 'Silakan klik tombol Cek Jadwal lagi.', icon: 'info' }).then(() => { location.reload(); });
                    return;
                }
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                timerDisplay.text((hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds < 10 ? "0" + seconds : seconds));
            }, 1000);
        }
    });
</script>
<style>
    .pulse-button { animation: pulse 2s infinite; }
    @keyframes pulse { 0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7); } 70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); } 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); } }
    .border-dashed { border-style: dashed !important; }
</style>
<?= $this->endSection(); ?>
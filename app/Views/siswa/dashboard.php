<?= $this->extend('layouts/ujian'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                    <h4 class="mb-0 fw-bold">Konfirmasi Peserta Ujian</h4>
                    <p class="mb-0 text-white-50">Silakan verifikasi data diri Anda</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <div id="alert-container"></div>

                    <form id="form-konfirmasi">
                        <?= csrf_field() ?>
                        
                        <div class="form-group mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Sekolah</label>
                            <input type="text" class="form-control form-control-lg bg-light" value="<?= $sekolah['nama_sekolah'] ?>" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Nama Peserta</label>
                            <input type="text" class="form-control form-control-lg bg-light" value="<?= $siswa['nama_lengkap'] ?>" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Username</label>
                            <input type="text" class="form-control form-control-lg bg-light" value="<?= $siswa['username'] ?>" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tanggal_lahir" class="form-label fw-bold text-dark">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control form-control-lg border-primary" required>
                            <div class="form-text">Masukkan tanggal lahir untuk verifikasi.</div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="mapel_id" class="form-label fw-bold text-dark">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="mapel_id" id="mapel_id" class="form-select form-select-lg border-primary" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                <?php foreach ($mapel as $m) : ?>
                                    <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Area Status Jadwal (Hidden by default) -->
                        <div id="status-area" class="d-none text-center mb-4">
                            <div id="countdown-box" class="alert alert-warning d-none">
                                <h5 class="alert-heading"><i class="bi bi-clock-history"></i> Ujian Belum Dimulai</h5>
                                <p class="mb-1">Waktu tersisa menuju ujian:</p>
                                <h2 id="timer" class="fw-bold mb-0">00:00:00</h2>
                            </div>
                            
                            <div id="msg-box" class="alert d-none"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" id="btn-cek" class="btn btn-primary btn-lg shadow">
                                <i class="bi bi-search me-2"></i> Cek Jadwal & Verifikasi
                            </button>
                            <button type="button" id="btn-mulai" class="btn btn-success btn-lg shadow d-none pulse-button">
                                <i class="bi bi-play-circle-fill me-2"></i> MULAI UJIAN
                            </button>
                            <a href="<?= base_url('logout') ?>" class="btn btn-light text-danger mt-2">
                                <i class="bi bi-box-arrow-left me-2"></i> Logout
                            </a>
                        </div>

                    </form>
                </div>
            </div>
            <div class="text-center mt-3 text-muted small">
                &copy; <?= date('Y') ?> Aplikasi Ujian Berbasis Komputer
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
                    text: 'Mohon isi Tanggal Lahir dan pilih Mata Pelajaran.'
                });
                return;
            }

            // Reset UI
            btnCek.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
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
                        // Tanggal Lahir Salah
                        msgBox.removeClass('d-none').addClass('alert-danger').html('<i class="bi bi-x-circle-fill"></i> ' + response.message);
                        Swal.fire('Gagal', response.message, 'error');
                    } else if (response.status === 'no_schedule') {
                        // Jadwal Tidak Ada
                        msgBox.removeClass('d-none').addClass('alert-danger').html('<i class="bi bi-calendar-x-fill"></i> ' + response.message);
                    } else if (response.status === 'finished') {
                        // Sudah Ujian
                        msgBox.removeClass('d-none').addClass('alert-info').html('<i class="bi bi-check-circle-fill"></i> ' + response.message);
                    } else if (response.status === 'countdown') {
                        // Hitung Mundur
                        countdownBox.removeClass('d-none');
                        startCountdown(response.waktu_mulai);
                    } else if (response.status === 'ready') {
                        // Siap Ujian
                        msgBox.removeClass('d-none').addClass('alert-success').html('<i class="bi bi-check-lg"></i> Data Terverifikasi. Jadwal Tersedia.');
                        btnMulai.removeClass('d-none');
                        btnCek.addClass('d-none'); // Sembunyikan tombol cek jika sudah ready
                        
                        // Handler tombol mulai
                        btnMulai.off('click').on('click', function() {
                            window.location.href = response.redirect_url;
                        });
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan koneksi server.', 'error');
                },
                complete: function() {
                    btnCek.prop('disabled', false).html('<i class="bi bi-search me-2"></i> Cek Jadwal & Verifikasi');
                }
            });
        });

        let countdownInterval;

        function startCountdown(startTimeStr) {
            clearInterval(countdownInterval);
            
            // Konversi string "YYYY-MM-DD HH:mm:ss" ke object Date
            // Note: Safari mobile kadang butuh format "YYYY/MM/DD"
            const targetDate = new Date(startTimeStr.replace(/-/g, "/")).getTime();

            countdownInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance < 0) {
                    clearInterval(countdownInterval);
                    timerDisplay.text("00:00:00");
                    Swal.fire({
                        title: 'Waktu Ujian Tiba!',
                        text: 'Silakan klik tombol Cek Jadwal lagi untuk masuk.',
                        icon: 'info'
                    }).then(() => {
                        location.reload();
                    });
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerDisplay.text(
                    (hours < 10 ? "0" + hours : hours) + ":" + 
                    (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                    (seconds < 10 ? "0" + seconds : seconds)
                );
            }, 1000);
        }
    });
</script>

<style>
    .pulse-button {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7); }
        70% { transform: scale(1.02); box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
    }
</style>
<?= $this->endSection(); ?>
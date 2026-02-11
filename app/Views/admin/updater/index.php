<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<style>
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }
    
    .update-header {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 40px;
        color: white;
        text-align: center;
    }

    .version-badge {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(5px);
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.2rem;
        display: inline-block;
        margin-top: 10px;
    }

    .changelog-box {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        border: 1px solid #eef2f7;
    }

    /* Modern Progress Bar */
    .progress-container {
        position: relative;
        height: 30px;
        background-color: #e9ecef;
        border-radius: 50px;
        overflow: hidden;
        margin: 20px 0;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #11998e, #38ef7d);
        width: 0%;
        transition: width 0.3s ease;
        border-radius: 50px;
        position: relative;
    }

    .progress-fill::after {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
        background-size: 1rem 1rem;
        animation: progress-bar-stripes 1s linear infinite;
    }

    @keyframes progress-bar-stripes {
        0% { background-position: 1rem 0; }
        100% { background-position: 0 0; }
    }

    .status-text {
        font-weight: 600;
        color: #435ebe;
        margin-bottom: 5px;
        display: block;
        text-align: center;
    }
    
    .percentage-text {
        font-weight: 800;
        font-size: 2rem;
        color: #25396f;
        display: block;
        text-align: center;
        margin-bottom: 10px;
    }

    /* Modal Custom */
    .modal-content {
        border-radius: 25px;
        border: none;
        overflow: hidden;
    }
    .modal-header { border-bottom: none; padding-bottom: 0; }
    .modal-footer { border-top: none; justify-content: center; padding-bottom: 30px; }
</style>

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            
            <!-- Card Status -->
            <div class="card card-modern mb-4">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-muted mb-0">Versi Saat Ini</h5>
                        <h2 class="text-primary fw-bold mb-0"><?= $current_version ?></h2>
                    </div>
                    <div class="bg-light-primary p-3 rounded-circle text-primary">
                        <i class="bi bi-shield-check fs-2"></i>
                    </div>
                </div>
            </div>

            <?php if ($server_data): ?>
                <?php if ($has_update): ?>
                    <!-- Card Update Tersedia -->
                    <div class="card card-modern">
                        <div class="update-header">
                            <h3 class="mb-0 text-white">🎉 Pembaruan Tersedia!</h3>
                            <div class="version-badge">v<?= $server_data['latest_version'] ?></div>
                            <p class="text-white text-opacity-75 mt-2">Dirilis: <?= date('d M Y', strtotime($server_data['release_date'])) ?></p>
                        </div>
                        <div class="card-body p-5">
                            <h5 class="fw-bold mb-3"><i class="bi bi-journal-text me-2"></i> Apa yang baru?</h5>
                            <div class="changelog-box">
                                <ul class="mb-0 ps-3">
                                    <?php foreach ($server_data['changelog'] as $log): ?>
                                        <li class="mb-2"><?= $log ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="alert alert-light-warning mt-4 border-warning border-start border-4">
                                <strong><i class="bi bi-exclamation-triangle-fill"></i> PENTING:</strong> <br>
                                Jangan tutup halaman atau matikan internet saat proses update berlangsung.
                            </div>

                            <button onclick="startUpdate()" class="btn btn-success btn-lg w-100 rounded-pill mt-3 shadow-lg">
                                <i class="bi bi-cloud-download-fill me-2"></i> Mulai Update Sekarang
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Card Sudah Terbaru -->
                    <div class="card card-modern text-center p-5">
                        <div class="text-success mb-3">
                            <i class="bi bi-check-circle-fill" style="font-size: 5rem;"></i>
                        </div>
                        <h3>Sistem Sudah Paling Baru</h3>
                        <p class="text-muted">Tidak ada pembaruan yang tersedia saat ini.</p>
                        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-primary rounded-pill px-4 mt-3">Kembali ke Dashboard</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Card Error Koneksi -->
                <div class="card card-modern text-center p-5">
                    <div class="text-danger mb-3">
                        <i class="bi bi-wifi-off" style="font-size: 5rem;"></i>
                    </div>
                    <h3>Gagal Terhubung ke Server</h3>
                    <p class="text-muted">Tidak dapat mengambil data pembaruan.</p>
                    <a href="<?= base_url('admin/updater') ?>" class="btn btn-primary rounded-pill px-4 mt-3">Coba Lagi</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- MODAL PROGRESS -->
<div class="modal fade" id="progressModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <div id="updateIcon" class="mb-3 text-primary">
                    <div class="spinner-border" style="width: 4rem; height: 4rem;" role="status"></div>
                </div>
                
                <h4 class="fw-bold mb-1" id="progressTitle">Sedang Memproses...</h4>
                <span class="text-muted" id="progressMessage">Menyiapkan koneksi...</span>
                
                <div class="mt-4">
                    <span class="percentage-text" id="percentText">0%</span>
                    <div class="progress-container">
                        <div class="progress-fill" id="progressBar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let intervalId;
    const downloadUrl = '<?= $server_data['download_url'] ?? '' ?>';
    const newVersion = '<?= $server_data['latest_version'] ?? '' ?>';

    function startUpdate() {
        // Tampilkan Modal
        const modal = new bootstrap.Modal(document.getElementById('progressModal'));
        modal.show();
        
        // Mulai Polling Status (Cek persentase setiap 500ms)
        intervalId = setInterval(checkStatus, 500);

        // Langkah 1: Request Download
        fetch(`<?= base_url('admin/updater/init?url=') ?>${encodeURIComponent(downloadUrl)}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Langkah 2: Jika download sukses, mulai Ekstrak
                    startExtraction();
                } else {
                    showError('Gagal mendownload file update.');
                }
            })
            .catch(err => showError('Terjadi kesalahan koneksi saat download.'));
    }

    function startExtraction() {
        fetch(`<?= base_url('admin/updater/extract?version=') ?>${newVersion}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'completed') {
                    // SELESAI
                    finishUpdate();
                } else {
                    showError(data.message || 'Gagal mengekstrak file.');
                }
            })
            .catch(err => showError('Terjadi kesalahan saat ekstraksi.'));
    }

    function checkStatus() {
        fetch('<?= base_url('admin/updater/status') ?>')
            .then(res => res.json())
            .then(data => {
                // Update UI
                const percent = data.percent;
                document.getElementById('progressBar').style.width = percent + '%';
                document.getElementById('percentText').innerText = percent + '%';
                document.getElementById('progressMessage').innerText = data.message;

                // Ubah judul berdasarkan status
                if (data.status === 'processing') {
                     document.getElementById('progressTitle').innerText = "Mendownload...";
                } else if (data.status === 'downloaded') {
                     document.getElementById('progressTitle').innerText = "Mengekstrak...";
                }
            });
    }

    function finishUpdate() {
        clearInterval(intervalId);
        document.getElementById('progressBar').style.width = '100%';
        document.getElementById('percentText').innerText = '100%';
        document.getElementById('progressMessage').innerText = 'Pembaruan Selesai!';
        
        document.getElementById('updateIcon').innerHTML = '<i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>';
        
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Update Berhasil!',
                text: 'Sistem telah diperbarui ke versi terbaru.',
                confirmButtonText: 'Muat Ulang'
            }).then(() => {
                window.location.reload();
            });
        }, 1000);
    }

    function showError(msg) {
        clearInterval(intervalId);
        document.getElementById('progressModal').classList.remove('show'); // Hide modal simple way (or refresh)
        Swal.fire('Error', msg, 'error').then(() => location.reload());
    }
</script>
<?= $this->endSection(); ?>
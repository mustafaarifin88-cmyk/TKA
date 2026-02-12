<?= $this->extend('layouts/ujian'); ?>

<?= $this->section('content'); ?>
<!-- Header Ujian (Biru/Primary) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top" style="height: 80px;">
    <div class="container-fluid px-4">
        <!-- Logo & Identitas Sekolah -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <!-- Logo SD dari Link Eksternal -->
            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhQFOXlcj2tOqNuOKDC35tPNB_BcLIc8mnUuzdHJDLgIo3bz9FnNEqNgwzMROJDnnDHjfTSwi8XvimNwKfYmhBiTmiZcNta6luGpkB6vzLsMTlLcxqE2kJ4s1Yc7YJLFC659LKSkmrfZmU/s2048/Logo+Sekolah+Dasar+%2528Logo+SD%2529.png" alt="Logo" style="height: 50px;" class="me-3 bg-white rounded-circle p-1">
            
            <div class="d-flex flex-column">
                <span class="fw-bold text-white" style="line-height: 1.1; font-size: 1.1rem;"><?= $jadwal['nama_sekolah'] ?></span>
                <span class="text-white-50 small" style="font-size: 0.8rem;"><?= $jadwal['nama_mapel'] ?></span>
            </div>
        </a>

        <!-- Tools Kanan: Timer, Daftar Soal, Nama -->
        <div class="d-flex align-items-center gap-3">
            
            <!-- Timer Box (Putih agar kontras di biru) -->
            <div class="bg-white px-3 py-2 rounded-3 text-center shadow-sm" style="min-width: 110px;">
                <small class="d-block text-muted fw-bold text-uppercase" style="font-size: 0.65rem; line-height: 1;">Sisa Waktu</small>
                <span id="timer" class="fw-bold fs-5 text-dark" style="line-height: 1.1;">00:00:00</span>
            </div>

            <!-- Tombol Daftar Soal (Popup) -->
            <button class="btn btn-outline-light shadow-sm position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSoal">
                <i class="bi bi-grid-3x3-gap-fill"></i> 
                <span class="d-none d-md-inline ms-1">Daftar Soal</span>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" id="badge-not-answered" title="Belum dijawab"></span>
            </button>

            <!-- Info Peserta (Desktop) -->
            <div class="d-none d-lg-block text-end ms-2 border-start border-white border-opacity-50 ps-3 text-white">
                <div class="fw-bold text-truncate" style="max-width: 150px;"><?= $siswa['nama_lengkap'] ?></div>
                <div class="text-white-50 small">Peserta Ujian</div>
            </div>
        </div>
    </div>
</nav>

<!-- Container Ujian -->
<div class="container-fluid py-4" style="margin-top: 80px; min-height: calc(100vh - 80px);">
    <div class="row h-100 g-3">
        
        <!-- KOLOM KIRI: SOAL (PERTANYAAN) -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill shadow-sm">Soal No. <span id="nomor-soal-display">1</span></span>
                    <span class="ms-auto text-muted small"><i class="bi bi-info-circle me-1"></i> Baca soal dengan teliti</span>
                </div>
                
                <div class="card-body overflow-auto bg-light bg-opacity-25" style="max-height: 80vh; font-size: 1.15rem; line-height: 1.8;">
                    <?php foreach ($soal as $index => $s) : ?>
                        <div class="soal-content" id="soal-content-<?= $index ?>" style="<?= $index === 0 ? '' : 'display: none;' ?>">
                            <div class="p-3 bg-white rounded shadow-sm border mb-3">
                                <?= $s['pertanyaan'] ?>
                            </div>
                            <?php if ($s['file_soal']) : ?>
                                <div class="mt-3 text-center">
                                    <img src="<?= base_url('uploads/bank_soal/' . $s['file_soal']) ?>" class="img-fluid rounded border shadow-sm" style="max-height: 450px;">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: JAWABAN (OPSI) & NAVIGASI -->
        <div class="col-md-4 d-flex flex-column">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white fw-bold py-3 rounded-top">
                    <i class="bi bi-pencil-square me-2"></i> Lembar Jawaban
                </div>
                
                <div class="card-body overflow-auto d-flex flex-column" style="max-height: 80vh;">
                    
                    <div class="flex-grow-1">
                        <?php foreach ($soal as $index => $s) : ?>
                            <div class="soal-opsi-wrapper" id="opsi-wrapper-<?= $index ?>" style="<?= $index === 0 ? '' : 'display: none;' ?>">
                                
                                <p class="text-muted small mb-2">Pilih jawaban yang menurut Anda benar:</p>

                                <?php 
                                    $jawabanSiswa = $jawaban[$s['id']] ?? null;
                                    $jawabanSiswaArr = json_decode($jawabanSiswa ?? '[]', true);
                                ?>

                                <?php if ($s['jenis'] == 'pg') : ?>
                                    <div class="d-grid gap-2">
                                        <?php foreach (['a', 'b', 'c', 'd', 'e'] as $opt) : ?>
                                            <?php if (!empty($s['opsi_' . $opt])) : ?>
                                                <input type="radio" class="btn-check" name="jawaban_<?= $s['id'] ?>" 
                                                       id="opt_<?= $s['id'] ?>_<?= $opt ?>" value="<?= strtoupper($opt) ?>" 
                                                       <?= ($jawabanSiswa == strtoupper($opt)) ? 'checked' : '' ?>
                                                       onchange="saveAnswer(<?= $s['id'] ?>, 'pg', <?= $index ?>)">
                                                <label class="btn btn-outline-secondary text-start p-3 d-flex align-items-center option-card" for="opt_<?= $s['id'] ?>_<?= $opt ?>">
                                                    <span class="badge bg-secondary me-3 rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px;"><?= strtoupper($opt) ?></span>
                                                    <div class="flex-grow-1 lh-sm">
                                                        <?= $s['opsi_' . $opt] ?>
                                                        <?php if ($s['file_' . $opt]) : ?>
                                                            <br><img src="<?= base_url('uploads/bank_soal/' . $s['file_' . $opt]) ?>" class="img-thumbnail mt-2" style="max-height: 80px;">
                                                        <?php endif; ?>
                                                    </div>
                                                </label>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>

                                <?php elseif ($s['jenis'] == 'pg_kompleks') : ?>
                                    <div class="alert alert-info py-2 small"><i class="bi bi-check-all me-1"></i> Jawaban lebih dari satu.</div>
                                    <div class="d-grid gap-2">
                                        <?php foreach (['a', 'b', 'c', 'd', 'e'] as $opt) : ?>
                                            <?php if (!empty($s['opsi_' . $opt])) : ?>
                                                <input type="checkbox" class="btn-check" name="jawaban_<?= $s['id'] ?>[]" 
                                                       id="opt_<?= $s['id'] ?>_<?= $opt ?>" value="<?= strtoupper($opt) ?>"
                                                       <?= (is_array($jawabanSiswaArr) && in_array(strtoupper($opt), $jawabanSiswaArr)) ? 'checked' : '' ?>
                                                       onchange="saveAnswer(<?= $s['id'] ?>, 'pg_kompleks', <?= $index ?>)">
                                                <label class="btn btn-outline-secondary text-start p-3 option-card" for="opt_<?= $s['id'] ?>_<?= $opt ?>">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-square me-3 fs-5 check-icon"></i>
                                                        <div class="flex-grow-1 lh-sm">
                                                            <span class="fw-bold me-1 text-primary"><?= strtoupper($opt) ?>.</span> <?= $s['opsi_' . $opt] ?>
                                                        </div>
                                                    </div>
                                                </label>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>

                                <?php elseif ($s['jenis'] == 'benar_salah') : ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm mb-0">
                                            <thead class="bg-light text-center">
                                                <tr><th>Pernyataan</th><th width="15%">B</th><th width="15%">S</th></tr>
                                            </thead>
                                            <tbody>
                                                <?php $pernyataan = json_decode($s['opsi_a'], true) ?? []; ?>
                                                <?php foreach ($pernyataan as $idx => $per) : ?>
                                                    <tr>
                                                        <td class="small align-middle px-2 py-2"><?= $per ?></td>
                                                        <td class="text-center align-middle p-0">
                                                            <input type="radio" class="btn-check" name="jawaban_<?= $s['id'] ?>[<?= $idx ?>]" id="bs_<?= $s['id'] ?>_<?= $idx ?>_b" value="Benar" 
                                                                   <?= (isset($jawabanSiswaArr[$idx]) && $jawabanSiswaArr[$idx] == 'Benar') ? 'checked' : '' ?>
                                                                   onchange="saveAnswer(<?= $s['id'] ?>, 'benar_salah', <?= $index ?>)">
                                                            <label class="btn btn-outline-success w-100 h-100 rounded-0 border-0 d-flex align-items-center justify-content-center" for="bs_<?= $s['id'] ?>_<?= $idx ?>_b"><i class="bi bi-check-lg"></i></label>
                                                        </td>
                                                        <td class="text-center align-middle p-0">
                                                            <input type="radio" class="btn-check" name="jawaban_<?= $s['id'] ?>[<?= $idx ?>]" id="bs_<?= $s['id'] ?>_<?= $idx ?>_s" value="Salah" 
                                                                   <?= (isset($jawabanSiswaArr[$idx]) && $jawabanSiswaArr[$idx] == 'Salah') ? 'checked' : '' ?>
                                                                   onchange="saveAnswer(<?= $s['id'] ?>, 'benar_salah', <?= $index ?>)">
                                                            <label class="btn btn-outline-danger w-100 h-100 rounded-0 border-0 d-flex align-items-center justify-content-center" for="bs_<?= $s['id'] ?>_<?= $idx ?>_s"><i class="bi bi-x-lg"></i></label>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                <?php elseif ($s['jenis'] == 'esai') : ?>
                                    <label class="form-label fw-bold small text-muted">Jawaban Esai:</label>
                                    <textarea class="form-control bg-light" rows="10" id="jawaban_<?= $s['id'] ?>" 
                                              onblur="saveAnswer(<?= $s['id'] ?>, 'esai', <?= $index ?>)" placeholder="Ketik jawaban Anda disini..."><?= $jawabanSiswa ?></textarea>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Tombol Navigasi di Bagian Bawah Kolom Kanan (Jawaban) -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="row g-2">
                            <div class="col-4">
                                <button class="btn btn-outline-secondary w-100" id="btn-prev" onclick="changeSoal(-1)" disabled>
                                    <i class="bi bi-chevron-left"></i> Prev
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-warning text-white w-100" id="btn-ragu" onclick="toggleRagu()">
                                    <i class="bi bi-flag-fill"></i> Ragu
                                    <input type="checkbox" id="check-ragu" class="d-none"> 
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-primary w-100" id="btn-next" onclick="changeSoal(1)">
                                    Next <i class="bi bi-chevron-right"></i>
                                </button>
                                <button class="btn btn-success w-100 d-none" id="btn-selesai" onclick="finishExam()">
                                    Submit <i class="bi bi-send-fill ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Offcanvas Daftar Soal (Popup Kanan) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSoal" aria-labelledby="offcanvasSoalLabel">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title" id="offcanvasSoalLabel"><i class="bi bi-grid-3x3-gap-fill me-2"></i> Navigasi Soal</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row g-2">
            <?php foreach ($soal as $index => $s) : ?>
                <?php 
                    $bgClass = 'btn-outline-secondary';
                    if (isset($jawaban[$s['id']]) && !empty($jawaban[$s['id']])) {
                        $bgClass = 'btn-primary';
                    }
                ?>
                <div class="col-3 col-md-3">
                    <button type="button" class="btn w-100 <?= $bgClass ?> position-relative p-2 fw-bold" id="nav-<?= $index ?>" onclick="jumpToSoal(<?= $index ?>)">
                        <?= $index + 1 ?>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle d-none" id="badge-ragu-<?= $index ?>" style="width:10px; height:10px;"></span>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="mt-4 pt-3 border-top">
            <h6 class="text-muted small fw-bold mb-3">Keterangan Warna:</h6>
            <div class="d-flex justify-content-between small text-muted mb-2">
                <div class="d-flex align-items-center"><span class="bg-primary rounded d-inline-block me-2" style="width: 20px; height: 20px;"></span> Dijawab</div>
                <div class="d-flex align-items-center"><span class="border border-secondary rounded d-inline-block me-2" style="width: 20px; height: 20px;"></span> Belum</div>
            </div>
            <div class="d-flex align-items-center small text-muted"><span class="bg-warning rounded d-inline-block me-2" style="width: 20px; height: 20px;"></span> Ragu-ragu</div>
            
            <button class="btn btn-danger w-100 mt-4" onclick="finishExam()">
                <i class="bi bi-stop-circle-fill me-2"></i> Hentikan Ujian
            </button>
        </div>
    </div>
</div>

<form id="form-selesai" action="<?= base_url('siswa/ujian/selesai') ?>" method="post">
    <?= csrf_field() ?>
</form>

<script>
    let currentSoal = 0;
    const totalSoal = <?= count($soal) ?>;
    
    function updateTimer() {
        const jadwalSelesai = new Date("<?= date('Y/m/d') . ' ' . date('H:i:s', strtotime($jadwal['jam_mulai']) + ($jadwal['lama_ujian'] * 60)) ?>").getTime();
        
        const timerInterval = setInterval(function() {
            const now = new Date().getTime();
            const distance = jadwalSelesai - now;
            
            if (distance < 0) {
                clearInterval(timerInterval);
                document.getElementById("timer").innerHTML = "00:00:00";
                finishExam(true);
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById("timer").innerHTML = 
                (hours < 10 ? "0" + hours : hours) + ":" + 
                (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                (seconds < 10 ? "0" + seconds : seconds);
            
            if(distance < 300000) { // 5 menit
                document.getElementById("timer").classList.add("text-danger", "pulse-animation");
            }
        }, 1000);
    }

    function jumpToSoal(index) {
        document.querySelectorAll('.soal-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.soal-opsi-wrapper').forEach(el => el.style.display = 'none');
        
        document.getElementById('soal-content-' + index).style.display = 'block';
        document.getElementById('opsi-wrapper-' + index).style.display = 'block';
        
        currentSoal = index;
        document.getElementById('nomor-soal-display').innerText = currentSoal + 1;
        
        document.getElementById('btn-prev').disabled = (currentSoal === 0);
        
        if (currentSoal === totalSoal - 1) {
            document.getElementById('btn-next').classList.add('d-none');
            document.getElementById('btn-selesai').classList.remove('d-none');
        } else {
            document.getElementById('btn-next').classList.remove('d-none');
            document.getElementById('btn-selesai').classList.add('d-none');
        }
        
        // Reset Ragu Button UI based on stored state if needed (currently simple toggle)
        const badge = document.getElementById('badge-ragu-' + currentSoal);
        const btnRagu = document.getElementById('btn-ragu');
        if (!badge.classList.contains('d-none')) {
             btnRagu.classList.remove('btn-warning', 'text-white');
             btnRagu.classList.add('btn-outline-warning', 'text-dark'); // Visual feedback active
        } else {
             btnRagu.classList.add('btn-warning', 'text-white');
             btnRagu.classList.remove('btn-outline-warning', 'text-dark');
        }
    }

    function changeSoal(direction) {
        let next = currentSoal + direction;
        if (next >= 0 && next < totalSoal) {
            jumpToSoal(next);
        }
    }

    function saveAnswer(soalId, jenis, index) {
        let jawaban = null;

        if (jenis === 'pg') {
            const el = document.querySelector(`input[name="jawaban_${soalId}"]:checked`);
            if (el) jawaban = el.value;
        } else if (jenis === 'pg_kompleks') {
            jawaban = [];
            document.querySelectorAll(`input[name="jawaban_${soalId}[]"]:checked`).forEach((el) => {
                jawaban.push(el.value);
            });
        } else if (jenis === 'benar_salah') {
            jawaban = {};
            document.querySelectorAll(`input[name^="jawaban_${soalId}"]:checked`).forEach((el) => {
                let name = el.getAttribute('name');
                let idx = name.match(/\[(.*?)\]/)[1];
                jawaban[idx] = el.value;
            });
        } else if (jenis === 'esai') {
            jawaban = document.getElementById(`jawaban_${soalId}`).value;
        }

        $.ajax({
            url: '<?= base_url('siswa/ujian/simpan_jawaban') ?>',
            type: 'POST',
            data: {
                soal_id: soalId,
                jawaban: jawaban,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(res) {
                const navBtn = document.getElementById('nav-' + index);
                if ((Array.isArray(jawaban) && jawaban.length > 0) || (typeof jawaban === 'string' && jawaban.trim() !== '') || (typeof jawaban === 'object' && Object.keys(jawaban).length > 0)) {
                    navBtn.classList.remove('btn-outline-secondary');
                    navBtn.classList.add('btn-primary');
                } else {
                    navBtn.classList.remove('btn-primary');
                    navBtn.classList.add('btn-outline-secondary');
                }
            }
        });
    }

    function toggleRagu() {
        const badge = document.getElementById('badge-ragu-' + currentSoal);
        if (badge.classList.contains('d-none')) {
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }

    function finishExam(auto = false) {
        if (auto) {
            Swal.fire({
                title: 'Waktu Habis!',
                text: 'Jawaban Anda akan disubmit otomatis.',
                icon: 'warning',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                document.getElementById('form-selesai').submit();
            });
        } else {
            Swal.fire({
                title: 'Konfirmasi Selesai',
                text: "Apakah Anda yakin ingin mengakhiri ujian? Pastikan semua jawaban telah terisi.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-selesai').submit();
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateTimer();
        window.onbeforeunload = function() { return "Ujian sedang berlangsung!"; };
        document.addEventListener('contextmenu', event => event.preventDefault());
    });
</script>

<style>
    .option-card:hover { background-color: #f8f9fa; border-color: #0d6efd; }
    .btn-check:checked + .option-card { background-color: #e7f1ff; border-color: #0d6efd; color: #0d6efd; }
    .btn-check:checked + .option-card .badge { background-color: #0d6efd !important; }
    .btn-check:checked + .option-card .check-icon { color: #0d6efd; }
    .pulse-animation { animation: pulse-red 1s infinite; }
    @keyframes pulse-red { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
</style>
<?= $this->endSection(); ?>
<?= $this->extend('layouts/ujian'); ?>

<?= $this->section('content'); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top" style="height: 60px;">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-mortarboard-fill me-2"></i> UJIAN ONLINE
        </a>
        <div class="d-flex align-items-center text-white">
            <div class="me-4 d-none d-md-block">
                <small class="d-block text-white-50" style="font-size: 0.75rem;">Nama Peserta</small>
                <span class="fw-bold"><?= $siswa['nama_lengkap'] ?></span>
            </div>
            <div class="text-end bg-white text-primary px-3 py-1 rounded shadow-sm">
                <small class="d-block text-muted" style="font-size: 0.7rem; line-height: 1;">Sisa Waktu</small>
                <span id="timer" class="fw-bold fs-5">00:00:00</span>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid" style="margin-top: 60px; height: calc(100vh - 60px);">
    <div class="row h-100">
        <div class="col-md-9 h-100 overflow-auto bg-light p-4" id="soal-container">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="m-0 text-primary"><?= $jadwal['nama_mapel'] ?></h5>
                        <small class="text-muted"><?= $jadwal['nama_sekolah'] ?></small>
                    </div>
                    <div class="badge bg-primary fs-6">
                        No. <span id="nomor-soal-display">1</span>
                    </div>
                </div>
            </div>

            <?php foreach ($soal as $index => $s) : ?>
                <div class="card shadow-sm border-0 mb-4 soal-item" id="soal-<?= $index ?>" style="<?= $index === 0 ? '' : 'display: none;' ?>">
                    <div class="card-body p-4">
                        <div class="soal-text mb-4" style="font-size: 1.1rem;">
                            <?= $s['pertanyaan'] ?>
                            <?php if ($s['file_soal']) : ?>
                                <div class="mt-3">
                                    <img src="<?= base_url('uploads/bank_soal/' . $s['file_soal']) ?>" class="img-fluid rounded border" style="max-height: 400px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="opsi-container">
                            <?php 
                                $jawabanSiswa = $jawaban[$s['id']] ?? null;
                                $jawabanSiswaArr = json_decode($jawabanSiswa ?? '[]', true);
                            ?>

                            <?php if ($s['jenis'] == 'pg') : ?>
                                <?php foreach (['a', 'b', 'c', 'd', 'e'] as $opt) : ?>
                                    <?php if (!empty($s['opsi_' . $opt])) : ?>
                                        <div class="form-check mb-3 p-3 border rounded option-hover <?= ($jawabanSiswa == strtoupper($opt)) ? 'bg-light-primary border-primary' : '' ?>">
                                            <input class="form-check-input ms-1" type="radio" name="jawaban_<?= $s['id'] ?>" 
                                                   id="opt_<?= $s['id'] ?>_<?= $opt ?>" value="<?= strtoupper($opt) ?>" 
                                                   <?= ($jawabanSiswa == strtoupper($opt)) ? 'checked' : '' ?>
                                                   onchange="saveAnswer(<?= $s['id'] ?>, 'pg', <?= $index ?>)">
                                            <label class="form-check-label w-100 ps-2 cursor-pointer" for="opt_<?= $s['id'] ?>_<?= $opt ?>">
                                                <span class="fw-bold me-2"><?= strtoupper($opt) ?>.</span> 
                                                <?= $s['opsi_' . $opt] ?>
                                                <?php if ($s['file_' . $opt]) : ?>
                                                    <div class="mt-2">
                                                        <img src="<?= base_url('uploads/bank_soal/' . $s['file_' . $opt]) ?>" class="img-thumbnail" style="max-height: 150px;">
                                                    </div>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            <?php elseif ($s['jenis'] == 'pg_kompleks') : ?>
                                <div class="alert alert-info py-2 mb-3"><i class="bi bi-info-circle me-1"></i> Pilih lebih dari satu jawaban yang benar.</div>
                                <?php foreach (['a', 'b', 'c', 'd', 'e'] as $opt) : ?>
                                    <?php if (!empty($s['opsi_' . $opt])) : ?>
                                        <div class="form-check mb-3 p-3 border rounded option-hover">
                                            <input class="form-check-input ms-1" type="checkbox" name="jawaban_<?= $s['id'] ?>[]" 
                                                   id="opt_<?= $s['id'] ?>_<?= $opt ?>" value="<?= strtoupper($opt) ?>"
                                                   <?= (is_array($jawabanSiswaArr) && in_array(strtoupper($opt), $jawabanSiswaArr)) ? 'checked' : '' ?>
                                                   onchange="saveAnswer(<?= $s['id'] ?>, 'pg_kompleks', <?= $index ?>)">
                                            <label class="form-check-label w-100 ps-2 cursor-pointer" for="opt_<?= $s['id'] ?>_<?= $opt ?>">
                                                <span class="fw-bold me-2"><?= strtoupper($opt) ?>.</span> 
                                                <?= $s['opsi_' . $opt] ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            <?php elseif ($s['jenis'] == 'benar_salah') : ?>
                                <div class="alert alert-info py-2 mb-3"><i class="bi bi-info-circle me-1"></i> Tentukan Benar atau Salah untuk setiap pernyataan.</div>
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Pernyataan</th>
                                            <th class="text-center" width="15%">Benar</th>
                                            <th class="text-center" width="15%">Salah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $pernyataan = json_decode($s['opsi_a'], true) ?? [];
                                        ?>
                                        <?php foreach ($pernyataan as $idx => $per) : ?>
                                            <tr>
                                                <td><?= $per ?></td>
                                                <td class="text-center align-middle">
                                                    <input type="radio" name="jawaban_<?= $s['id'] ?>[<?= $idx ?>]" value="Benar" 
                                                           <?= (isset($jawabanSiswaArr[$idx]) && $jawabanSiswaArr[$idx] == 'Benar') ? 'checked' : '' ?>
                                                           onchange="saveAnswer(<?= $s['id'] ?>, 'benar_salah', <?= $index ?>)">
                                                </td>
                                                <td class="text-center align-middle">
                                                    <input type="radio" name="jawaban_<?= $s['id'] ?>[<?= $idx ?>]" value="Salah" 
                                                           <?= (isset($jawabanSiswaArr[$idx]) && $jawabanSiswaArr[$idx] == 'Salah') ? 'checked' : '' ?>
                                                           onchange="saveAnswer(<?= $s['id'] ?>, 'benar_salah', <?= $index ?>)">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            <?php elseif ($s['jenis'] == 'esai') : ?>
                                <div class="form-group">
                                    <label class="form-label fw-bold">Jawaban Anda:</label>
                                    <textarea class="form-control" rows="6" id="jawaban_<?= $s['id'] ?>" 
                                              onblur="saveAnswer(<?= $s['id'] ?>, 'esai', <?= $index ?>)"><?= $jawabanSiswa ?></textarea>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="d-flex justify-content-between mt-4 mb-5">
                <button class="btn btn-secondary px-4" id="btn-prev" onclick="changeSoal(-1)" disabled>
                    <i class="bi bi-arrow-left me-1"></i> Sebelumnya
                </button>
                
                <button class="btn btn-warning text-white px-4" id="btn-ragu" onclick="toggleRagu()">
                    <input type="checkbox" id="check-ragu" class="d-none"> 
                    <i class="bi bi-square me-1" id="icon-ragu"></i> Ragu-ragu
                </button>

                <button class="btn btn-primary px-4" id="btn-next" onclick="changeSoal(1)">
                    Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                </button>
                
                <button class="btn btn-success px-4 d-none" id="btn-selesai" onclick="finishExam()">
                    <i class="bi bi-check-circle-fill me-1"></i> Selesai Ujian
                </button>
            </div>
        </div>

        <div class="col-md-3 h-100 bg-white border-start p-0 d-flex flex-column shadow-lg" style="z-index: 10;">
            <div class="p-3 bg-light border-bottom text-center">
                <h6 class="m-0 fw-bold text-uppercase">Nomor Soal</h6>
            </div>
            <div class="p-3 flex-grow-1 overflow-auto">
                <div class="d-grid gap-2" style="grid-template-columns: repeat(5, 1fr);">
                    <?php foreach ($soal as $index => $s) : ?>
                        <?php 
                            $bgClass = 'btn-outline-secondary';
                            if (isset($jawaban[$s['id']]) && !empty($jawaban[$s['id']])) {
                                $bgClass = 'btn-primary';
                            }
                        ?>
                        <button type="button" class="btn btn-sm <?= $bgClass ?> position-relative" id="nav-<?= $index ?>" onclick="jumpToSoal(<?= $index ?>)">
                            <?= $index + 1 ?>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle d-none" id="badge-ragu-<?= $index ?>"></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="p-3 border-top bg-light">
                <div class="d-flex justify-content-between small text-muted mb-2">
                    <div class="d-flex align-items-center"><span class="bg-primary rounded-circle d-inline-block me-1" style="width: 10px; height: 10px;"></span> Dijawab</div>
                    <div class="d-flex align-items-center"><span class="border border-secondary rounded-circle d-inline-block me-1" style="width: 10px; height: 10px;"></span> Belum</div>
                    <div class="d-flex align-items-center"><span class="bg-warning rounded-circle d-inline-block me-1" style="width: 10px; height: 10px;"></span> Ragu</div>
                </div>
                <button class="btn btn-danger w-100" onclick="finishExam()">Hentikan Ujian</button>
            </div>
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
                document.getElementById("timer").innerHTML = "WAKTU HABIS";
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
            
            // Warning 5 minutes left
            if(distance < 300000) {
                document.getElementById("timer").classList.add("text-danger", "pulse-animation");
            }
        }, 1000);
    }

    function jumpToSoal(index) {
        document.querySelectorAll('.soal-item').forEach(el => el.style.display = 'none');
        document.getElementById('soal-' + index).style.display = 'block';
        
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

        // Reset ragu button UI based on state (Not implemented fully for persistence, just visual toggle per session if needed)
        // Here we just uncheck it visually for simplicity unless we store ragu state
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
            
            // UI Update
            document.querySelectorAll(`input[name="jawaban_${soalId}"]`).forEach(inp => {
                inp.parentElement.classList.remove('bg-light-primary', 'border-primary');
            });
            if(el) el.parentElement.classList.add('bg-light-primary', 'border-primary');

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
        const icon = document.getElementById('icon-ragu');
        
        if (badge.classList.contains('d-none')) {
            badge.classList.remove('d-none');
            icon.classList.remove('bi-square');
            icon.classList.add('bi-check-square-fill');
        } else {
            badge.classList.add('d-none');
            icon.classList.remove('bi-check-square-fill');
            icon.classList.add('bi-square');
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
        
        // Prevent refresh or back
        window.onbeforeunload = function() {
            return "Ujian sedang berlangsung, jangan tinggalkan halaman ini!";
        };
        
        // Disable context menu
        document.addEventListener('contextmenu', event => event.preventDefault());
    });
</script>

<style>
    .cursor-pointer { cursor: pointer; }
    .option-hover:hover { background-color: #f8f9fa; }
    .pulse-animation { animation: pulse-red 1s infinite; }
    @keyframes pulse-red {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    /* Hide Scrollbar but keep functionality */
    #soal-container::-webkit-scrollbar { width: 8px; }
    #soal-container::-webkit-scrollbar-track { background: #f1f1f1; }
    #soal-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
    #soal-container::-webkit-scrollbar-thumb:hover { background: #aaa; }
</style>
<?= $this->endSection(); ?>
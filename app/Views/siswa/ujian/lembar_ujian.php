<?= $this->extend('layouts/app_ujian'); ?>

<?= $this->section('content'); ?>
<div class="page-content">
    <div class="row" id="exam-interface">
        <div class="col-12 col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                    <div>
                        <h4 class="card-title m-0">Soal No. <span id="nomor-soal-display">1</span></h4>
                        <span class="badge bg-secondary mt-1" id="jenis-soal-badge">Tipe Soal</span>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">Mata Pelajaran</small>
                        <span class="fw-bold text-primary"><?= $jadwal->nama_mapel ?></span>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div id="soal-container" class="mb-4">
                        <div id="text-pertanyaan" class="fs-5 lh-base text-dark">Sedang memuat soal...</div>
                    </div>

                    <div id="jawaban-container">
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between py-3">
                    <button class="btn btn-secondary px-4" id="btn-prev" onclick="navigasiSoal(-1)">
                        <i class="bi bi-arrow-left me-2"></i> Sebelumnya
                    </button>
                    
                    <button class="btn btn-warning px-4 text-white" id="btn-ragu" onclick="toggleRagu()">
                        <div class="form-check form-check-inline m-0">
                            <input class="form-check-input pointer-events-none" type="checkbox" id="check-ragu">
                            <label class="form-check-label pointer-events-none fw-bold">Ragu-ragu</label>
                        </div>
                    </button>

                    <button class="btn btn-primary px-4" id="btn-next" onclick="navigasiSoal(1)">
                        Selanjutnya <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-3 mt-4 mt-lg-0">
            <div class="card mb-3 bg-primary text-white shadow-sm border-0">
                <div class="card-body text-center py-4">
                    <h6 class="text-white-50 mb-1">Sisa Waktu</h6>
                    <h2 id="timer-display" class="fw-bold m-0 display-6">--:--:--</h2>
                </div>
            </div>

            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md me-3 bg-light-primary">
                            <?php if(!empty($active_user['foto']) && $active_user['foto'] != 'default.jpg'): ?>
                                <img src="<?= base_url('uploads/profil/' . $active_user['foto']) ?>" alt="User" style="object-fit:cover;">
                            <?php else: ?>
                                <span class="avatar-content text-primary fw-bold"><?= substr($active_user['nama_lengkap'], 0, 1) ?></span>
                            <?php endif; ?>
                        </div>
                        <div style="overflow: hidden;">
                            <h6 class="mb-0 text-truncate"><?= $active_user['nama_lengkap'] ?></h6>
                            <small class="text-muted">Peserta Ujian</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom bg-white">
                    <h6 class="m-0 fw-bold text-dark">Navigasi Soal</h6>
                </div>
                <div class="card-body pt-3">
                    <div class="row g-2" id="navigasi-grid" style="max-height: 350px; overflow-y: auto; padding-right: 5px;">
                    </div>
                    
                    <hr class="my-3">
                    <div class="d-grid gap-2">
                        <button class="btn btn-danger btn-lg shadow-sm" onclick="konfirmasiSelesai()">
                            <i class="bi bi-check-circle-fill me-2"></i> Kumpulkan Ujian
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-selesai" action="<?= base_url('siswa/ujian/selesai_ujian') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="jadwal_id" value="<?= $jadwal->id ?>">
</form>

<script>
    const daftarSoal = <?= json_encode($daftar_soal) ?>;
    const jawabanTersimpan = <?= json_encode($jawaban_map) ?>; 
    const waktuSelesaiStr = "<?= $waktu_selesai ?>"; 
    const jadwalId = <?= $jadwal->id ?>;
    const baseUrl = "<?= base_url() ?>";

    let indexSoalAktif = 0;
    const totalSoal = daftarSoal.length;
    let raguRaguList = {}; 

    function startTimer() {
        const endTime = new Date(waktuSelesaiStr.replace(/-/g, "/")).getTime();
        
        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("timer-display").innerHTML = "00:00:00";
                
                Swal.fire({
                    title: 'Waktu Habis!',
                    text: 'Ujian akan dikumpulkan secara otomatis.',
                    icon: 'warning',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'OK'
                }).then(() => {
                    document.getElementById("form-selesai").submit();
                });
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("timer-display").innerHTML = 
                (hours < 10 ? "0"+hours : hours) + ":" + 
                (minutes < 10 ? "0"+minutes : minutes) + ":" + 
                (seconds < 10 ? "0"+seconds : seconds);
        }, 1000);
    }

    function renderSoal(index) {
        const soal = daftarSoal[index];
        const soalId = soal.id;
        
        document.getElementById("nomor-soal-display").innerText = index + 1;
        
        const jenisBadge = document.getElementById("jenis-soal-badge");
        if(soal.jenis === 'pg') {
            jenisBadge.innerText = 'Pilihan Ganda';
            jenisBadge.className = 'badge bg-info';
        } else if(soal.jenis === 'pg_kompleks') {
            jenisBadge.innerText = 'Pilihan Ganda Kompleks';
            jenisBadge.className = 'badge bg-primary';
        } else if(soal.jenis === 'benar_salah') {
            jenisBadge.innerText = 'Benar / Salah';
            jenisBadge.className = 'badge bg-success';
        } else {
            jenisBadge.innerText = 'Esai / Uraian';
            jenisBadge.className = 'badge bg-warning text-dark';
        }
        
        let htmlPertanyaan = soal.pertanyaan.replace(/\n/g, '<br>');
        
        if (soal.file_soal) {
            htmlPertanyaan += `
                <div class="mt-3 text-center">
                    <img src="${baseUrl}/uploads/bank_soal/${soal.file_soal}" class="img-fluid rounded border shadow-sm" style="max-height: 400px;">
                </div>
            `;
        }
        document.getElementById("text-pertanyaan").innerHTML = htmlPertanyaan;

        let htmlJawaban = '';
        let jawabanUser = jawabanTersimpan[soalId];

        if (soal.jenis === 'pg') {
            const opsiLabels = ['A', 'B', 'C', 'D', 'E'];
            const opsiTexts = [soal.opsi_a, soal.opsi_b, soal.opsi_c, soal.opsi_d, soal.opsi_e];
            const opsiFiles = [soal.file_a, soal.file_b, soal.file_c, soal.file_d, soal.file_e];

            opsiTexts.forEach((text, idx) => {
                if((text && text.trim() !== "") || (opsiFiles[idx] && opsiFiles[idx] !== "")) {
                    const label = opsiLabels[idx];
                    const checked = (jawabanUser === label) ? 'checked' : '';
                    const activeClass = (jawabanUser === label) ? 'border-primary bg-light-primary' : '';
                    const imgOpsi = opsiFiles[idx] ? `<div class="mt-2"><img src="${baseUrl}/uploads/bank_soal/${opsiFiles[idx]}" class="img-thumbnail" style="max-height: 150px;"></div>` : '';
                    
                    htmlJawaban += `
                        <div class="card border mb-3 ${activeClass} hover-shadow option-card" onclick="selectRadio('opsi_${label}')">
                            <div class="card-body py-3 d-flex align-items-start">
                                <div class="form-check me-3 mt-1">
                                    <input class="form-check-input" type="radio" name="jawaban_aktif" 
                                        id="opsi_${label}" value="${label}" ${checked} 
                                        onchange="simpanJawaban('${soalId}', '${label}')">
                                </div>
                                <div class="w-100 cursor-pointer" for="opsi_${label}">
                                    <div class="fw-bold mb-1">${label}.</div>
                                    <div>${text}</div>
                                    ${imgOpsi}
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
        
        } else if (soal.jenis === 'pg_kompleks') {
            const opsiLabels = ['A', 'B', 'C', 'D', 'E'];
            const opsiTexts = [soal.opsi_a, soal.opsi_b, soal.opsi_c, soal.opsi_d, soal.opsi_e];
            const opsiFiles = [soal.file_a, soal.file_b, soal.file_c, soal.file_d, soal.file_e];
            
            let savedArr = [];
            try {
                if(jawabanUser) savedArr = JSON.parse(jawabanUser);
            } catch(e) { savedArr = []; }

            htmlJawaban += `<div class="alert alert-info py-2 mb-3"><i class="bi bi-info-circle me-2"></i> Pilih lebih dari satu jawaban yang benar.</div>`;

            opsiTexts.forEach((text, idx) => {
                if((text && text.trim() !== "") || (opsiFiles[idx] && opsiFiles[idx] !== "")) {
                    const label = opsiLabels[idx];
                    const isChecked = savedArr.includes(label) ? 'checked' : '';
                    const activeClass = savedArr.includes(label) ? 'border-primary bg-light-primary' : '';
                    const imgOpsi = opsiFiles[idx] ? `<div class="mt-2"><img src="${baseUrl}/uploads/bank_soal/${opsiFiles[idx]}" class="img-thumbnail" style="max-height: 150px;"></div>` : '';

                    htmlJawaban += `
                        <div class="card border mb-3 ${activeClass} hover-shadow option-card-complex" onclick="selectCheckbox('check_${label}')">
                            <div class="card-body py-3 d-flex align-items-start">
                                <div class="form-check me-3 mt-1">
                                    <input class="form-check-input" type="checkbox" name="jawaban_aktif_complex" 
                                        id="check_${label}" value="${label}" ${isChecked} 
                                        onchange="simpanJawabanKompleks('${soalId}')">
                                </div>
                                <div class="w-100 cursor-pointer">
                                    <div class="fw-bold mb-1">${label}.</div>
                                    <div>${text}</div>
                                    ${imgOpsi}
                                </div>
                            </div>
                        </div>
                    `;
                }
            });

        } else if (soal.jenis === 'benar_salah') {
            
            let statements = [];
            try { statements = JSON.parse(soal.opsi_a); } catch(e) { statements = []; }

            let savedArr = [];
            try { if(jawabanUser) savedArr = JSON.parse(jawabanUser); } catch(e) { savedArr = []; }

            htmlJawaban += `<div class="alert alert-info py-2 mb-3"><i class="bi bi-info-circle me-2"></i> Tentukan apakah pernyataan berikut Benar atau Salah.</div>`;
            
            htmlJawaban += `
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="bg-light text-center">
                            <tr>
                                <th width="60%">Pernyataan</th>
                                <th width="20%">Benar</th>
                                <th width="20%">Salah</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            if(Array.isArray(statements)) {
                statements.forEach((stmt, idx) => {
                    const savedVal = savedArr[idx] || '';
                    const checkB = (savedVal === 'Benar') ? 'checked' : '';
                    const checkS = (savedVal === 'Salah') ? 'checked' : '';

                    htmlJawaban += `
                        <tr>
                            <td>${stmt}</td>
                            <td class="text-center bg-light-success">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input" type="radio" name="bs_row_${idx}" 
                                        value="Benar" ${checkB} 
                                        onchange="simpanJawabanBenarSalah('${soalId}', ${statements.length})">
                                </div>
                            </td>
                            <td class="text-center bg-light-danger">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input" type="radio" name="bs_row_${idx}" 
                                        value="Salah" ${checkS} 
                                        onchange="simpanJawabanBenarSalah('${soalId}', ${statements.length})">
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }
            htmlJawaban += `</tbody></table></div>`;

        } else {
            if (!jawabanUser) jawabanUser = '';
            htmlJawaban = `
                <div class="form-group">
                    <label class="form-label text-muted mb-2">Tulis jawaban Anda di bawah ini:</label>
                    <textarea class="form-control" rows="8" placeholder="Ketik jawaban..."
                        onblur="simpanJawaban('${soalId}', this.value)" style="resize: none;">${jawabanUser}</textarea>
                </div>
            `;
        }
        
        document.getElementById("jawaban-container").innerHTML = htmlJawaban;

        document.getElementById("btn-prev").disabled = (index === 0);
        if (index === totalSoal - 1) {
            document.getElementById("btn-next").style.display = 'none';
        } else {
            document.getElementById("btn-next").style.display = 'inline-block';
        }

        const isRagu = raguRaguList[soalId] || false;
        document.getElementById("check-ragu").checked = isRagu;
        
        updateNavigasiGrid();
    }

    function selectRadio(id) {
        const radio = document.getElementById(id);
        if(radio) {
            radio.click();
            
            document.querySelectorAll('.option-card').forEach(el => {
                el.classList.remove('border-primary', 'bg-light-primary');
            });
            
            const card = radio.closest('.card');
            card.classList.add('border-primary', 'bg-light-primary');
        }
    }

    function selectCheckbox(id) {
        const chk = document.getElementById(id);
        const card = chk.closest('.card');
        
        if (window.event.target !== chk) {
            chk.checked = !chk.checked;
            chk.dispatchEvent(new Event('change'));
        }

        if(chk.checked) card.classList.add('border-primary', 'bg-light-primary');
        else card.classList.remove('border-primary', 'bg-light-primary');
    }

    function updateNavigasiGrid() {
        const container = document.getElementById("navigasi-grid");
        container.innerHTML = "";

        daftarSoal.forEach((soal, idx) => {
            const isAnswered = jawabanTersimpan[soal.id] ? true : false;
            let btnClass = "btn-outline-secondary"; 
            
            if (isAnswered) btnClass = "btn-primary";
            else btnClass = "btn-outline-danger"; 

            if (raguRaguList[soal.id]) btnClass = "btn-warning text-white";

            const activeBorder = (idx === indexSoalAktif) ? "border-dark border-3" : "";
            const opacity = (idx === indexSoalAktif) ? "opacity-100" : "opacity-75";

            const btn = `
                <div class="col-3 col-md-3 col-lg-3 p-1">
                    <button class="btn ${btnClass} w-100 ${activeBorder} ${opacity} p-2 fw-bold" onclick="pindahSoal(${idx})">
                        ${idx + 1}
                    </button>
                </div>
            `;
            container.innerHTML += btn;
        });
    }

    function navigasiSoal(step) {
        indexSoalAktif += step;
        renderSoal(indexSoalAktif);
    }

    function pindahSoal(index) {
        indexSoalAktif = index;
        renderSoal(indexSoalAktif);
    }

    function toggleRagu() {
        const soalId = daftarSoal[indexSoalAktif].id;
        raguRaguList[soalId] = !raguRaguList[soalId];
        document.getElementById("check-ragu").checked = raguRaguList[soalId];
        updateNavigasiGrid();
    }

    function simpanJawaban(soalId, jawabanVal) {
        jawabanTersimpan[soalId] = jawabanVal;
        kirimData(soalId, jawabanVal);
    }

    function simpanJawabanKompleks(soalId) {
        const checkboxes = document.querySelectorAll('input[name="jawaban_aktif_complex"]:checked');
        let values = [];
        checkboxes.forEach((cb) => {
            values.push(cb.value);
        });
        
        const jsonVal = JSON.stringify(values);
        
        if (values.length === 0) {
            delete jawabanTersimpan[soalId];
            kirimData(soalId, ''); 
        } else {
            jawabanTersimpan[soalId] = jsonVal;
            kirimData(soalId, values);
        }
    }

    function simpanJawabanBenarSalah(soalId, totalRows) {
        let values = [];
        let isComplete = true;

        for (let i = 0; i < totalRows; i++) {
            const rad = document.querySelector(`input[name="bs_row_${i}"]:checked`);
            if (rad) {
                values.push(rad.value);
            } else {
                values.push(null);
                isComplete = false;
            }
        }

        const jsonVal = JSON.stringify(values);
        jawabanTersimpan[soalId] = jsonVal;
        kirimData(soalId, values);
    }

    function kirimData(soalId, val) {
        updateNavigasiGrid();

        const formData = new FormData();
        formData.append('jadwal_id', jadwalId);
        formData.append('soal_id', soalId);
        
        if (Array.isArray(val)) {
            val.forEach(v => formData.append('jawaban[]', v));
        } else {
            formData.append('jawaban', val);
        }

        fetch(baseUrl + '/siswa/ujian/simpan_jawaban', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.status !== 'success') console.error('Gagal simpan');
        })
        .catch(error => console.error('Error:', error));
    }

    function konfirmasiSelesai() {
        const terisi = Object.keys(jawabanTersimpan).length;
        const belum = totalSoal - terisi;

        let pesanTitle = 'Kumpulkan Ujian?';
        let pesanText = "Pastikan semua jawaban sudah benar.";
        let icon = 'question';

        if (belum > 0) {
            pesanTitle = 'Masih Ada Soal Kosong!';
            pesanText = `Terdapat ${belum} soal yang belum dijawab. Yakin ingin mengakhiri?`;
            icon = 'warning';
        }

        Swal.fire({
            title: pesanTitle,
            text: pesanText,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kumpulkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("form-selesai").submit();
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        startTimer();
        renderSoal(0);
        
        const style = document.createElement('style');
        style.innerHTML = `
            .hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; cursor: pointer; transition: all 0.2s; }
            .bg-light-primary { background-color: #eef2ff !important; }
            .bg-light-success { background-color: #d1e7dd !important; }
            .bg-light-danger { background-color: #f8d7da !important; }
            .cursor-pointer { cursor: pointer; }
            .option-card { transition: all 0.2s; }
        `;
        document.head.appendChild(style);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection(); ?>
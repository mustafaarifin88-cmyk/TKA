<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<style>
    /* Modern Header Style */
    .tutorial-header {
        background: linear-gradient(135deg, #435ebe 0%, #2575fc 100%);
        padding: 3rem 2rem;
        border-radius: 1rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .tutorial-header::after {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 200px; height: 100%;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,160L48,176C96,192,192,224,288,224C384,224,480,192,576,165.3C672,139,768,117,864,128C960,139,1056,181,1152,197.3C1248,213,1344,203,1392,197.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat bottom right;
        background-size: cover;
    }

    /* Custom Tabs */
    .nav-pills-custom .nav-link {
        color: #6c757d;
        background: #fff;
        position: relative;
        font-weight: 600;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        margin-right: 10px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    .nav-pills-custom .nav-link.active {
        background: #435ebe;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(67, 94, 190, 0.3);
    }
    .nav-pills-custom .nav-link i {
        font-size: 1.2rem;
        margin-right: 8px;
        vertical-align: middle;
    }

    /* Timeline Step Style */
    .step-card {
        border-left: 4px solid #435ebe;
        background: #fff;
        padding: 1.5rem;
        border-radius: 0 10px 10px 0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        margin-bottom: 1.5rem;
        position: relative;
        transition: transform 0.3s;
    }
    .step-card:hover {
        transform: translateX(5px);
    }
    .step-number {
        position: absolute;
        left: -16px;
        top: 20px;
        width: 28px;
        height: 28px;
        background: #435ebe;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 28px;
        font-weight: bold;
        font-size: 0.9rem;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
</style>

<div class="page-content">
    
    <!-- Hero Header -->
    <div class="tutorial-header shadow-lg">
        <h2 class="text-white mb-2"><i class="bi bi-journal-bookmark-fill me-2"></i> Pusat Bantuan</h2>
        <p class="text-white-50 mb-0">Panduan lengkap penggunaan aplikasi ujian online untuk semua pengguna.</p>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills nav-pills-custom mb-4" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= (session()->get('role') == 'admin') ? 'active' : '' ?>" id="pills-admin-tab" data-bs-toggle="pill" data-bs-target="#pills-admin" type="button" role="tab">
                <i class="bi bi-person-badge-fill"></i> Administrator
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= (session()->get('role') == 'guru') ? 'active' : '' ?>" id="pills-guru-tab" data-bs-toggle="pill" data-bs-target="#pills-guru" type="button" role="tab">
                <i class="bi bi-briefcase-fill"></i> Guru
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= (session()->get('role') == 'siswa') ? 'active' : '' ?>" id="pills-siswa-tab" data-bs-toggle="pill" data-bs-target="#pills-siswa" type="button" role="tab">
                <i class="bi bi-people-fill"></i> Siswa
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="pills-tabContent">
        
        <!-- PANDUAN ADMIN -->
        <div class="tab-pane fade <?= (session()->get('role') == 'admin') ? 'show active' : '' ?>" id="pills-admin" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <h4 class="mb-4 text-primary">Langkah Awal Administrator</h4>
                    
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h5>Lengkapi Profil Sekolah</h5>
                        <p class="text-muted mb-0">Buka menu <b>Profil Sekolah</b>. Upload logo sekolah (format PNG/JPG) dan isi data alamat. Logo ini akan muncul di kop laporan dan favicon website.</p>
                    </div>

                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h5>Master Data (Urutan Wajib)</h5>
                        <p class="text-muted">Isi data dengan urutan berikut agar tidak error relasi:</p>
                        <ol class="text-muted ps-3 mb-0">
                            <li><b>Mata Pelajaran:</b> Input semua mapel yang ada.</li>
                            <li><b>Data Kelas:</b> Input nama kelas (misal: X IPA 1, XII IPS 2).</li>
                            <li><b>Set Mapel Kelas:</b> Hubungkan mapel apa saja yang diajarkan di kelas tertentu.</li>
                        </ol>
                    </div>

                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h5>Input Data Pengguna (Import Excel)</h5>
                        <p class="text-muted mb-0">Gunakan fitur <b>Import Excel</b> pada menu Data Guru dan Data Siswa. Download template yang disediakan terlebih dahulu agar format kolom sesuai.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card bg-light-primary">
                        <div class="card-body">
                            <h6><i class="bi bi-lightbulb-fill text-warning me-2"></i> Tips Admin</h6>
                            <ul class="text-sm ps-3 mb-0">
                                <li>Selalu gunakan username unik untuk setiap pengguna.</li>
                                <li>Jika siswa lupa password, Anda bisa mengeditnya di menu Data Siswa.</li>
                                <li>Pastikan bobot nilai diatur oleh Guru sebelum mencetak laporan.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANDUAN GURU -->
        <div class="tab-pane fade <?= (session()->get('role') == 'guru') ? 'show active' : '' ?>" id="pills-guru" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <h4 class="mb-4 text-primary">Panduan Mengelola Ujian</h4>

                    <div class="step-card" style="border-left-color: #198754;">
                        <div class="step-number bg-success">1</div>
                        <h5>Membuat Bank Soal</h5>
                        <p class="text-muted mb-0">Buka menu <b>Bank Soal</b> > Pilih Kelas > Pilih Mapel. Anda bisa membuat soal Pilihan Ganda (PG) dengan 5 opsi jawaban dan soal Esai. Kunci jawaban PG wajib dipilih.</p>
                    </div>

                    <div class="step-card" style="border-left-color: #198754;">
                        <div class="step-number bg-success">2</div>
                        <h5>Jadwal Ujian</h5>
                        <p class="text-muted mb-0">Setelah soal siap, buat jadwal di menu <b>Jadwal Ujian</b>. Tentukan tanggal, jam mulai, dan durasi dalam menit.</p>
                    </div>

                    <div class="step-card" style="border-left-color: #198754;">
                        <div class="step-number bg-success">3</div>
                        <h5>Koreksi & Penilaian</h5>
                        <p class="text-muted mb-0">Masuk ke menu <b>Rekap Nilai</b>.
                        <br>- Atur bobot persentase (misal: PG 60%, Esai 40%).
                        <br>- Klik "Koreksi" pada siswa yang sudah selesai.
                        <br>- Nilai PG otomatis muncul, nilai Esai harus diinput manual (0-100).
                        <br>- Simpan, lalu cetak laporan.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card bg-light-success">
                        <div class="card-body">
                            <h6><i class="bi bi-star-fill text-warning me-2"></i> Info Penting</h6>
                            <ul class="text-sm ps-3 mb-0">
                                <li>Siswa hanya bisa mengerjakan saat waktu ujian berlangsung.</li>
                                <li>Soal akan diacak otomatis oleh sistem.</li>
                                <li>Pastikan status ujian siswa "Selesai" sebelum dikoreksi.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANDUAN SISWA -->
        <div class="tab-pane fade <?= (session()->get('role') == 'siswa') ? 'show active' : '' ?>" id="pills-siswa" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <h4 class="mb-4 text-primary">Cara Mengerjakan Ujian</h4>

                    <div class="step-card" style="border-left-color: #0dcaf0;">
                        <div class="step-number bg-info">1</div>
                        <h5>Cek Jadwal</h5>
                        <p class="text-muted mb-0">Login dan buka menu <b>Daftar Ujian</b>. Jika tombol berwarna kuning (Belum Dimulai), tunggu hingga jam ujian tiba dan refresh halaman.</p>
                    </div>

                    <div class="step-card" style="border-left-color: #0dcaf0;">
                        <div class="step-number bg-info">2</div>
                        <h5>Mengerjakan Soal</h5>
                        <p class="text-muted mb-0">
                            - Klik tombol nomor di kanan untuk lompat soal.<br>
                            - Warna <b>Biru</b> = Sudah dijawab.<br>
                            - Warna <b>Kuning</b> = Ragu-ragu.<br>
                            - Warna <b>Merah</b> = Belum dijawab.
                        </p>
                    </div>

                    <div class="step-card" style="border-left-color: #0dcaf0;">
                        <div class="step-number bg-info">3</div>
                        <h5>Selesai Ujian</h5>
                        <p class="text-muted mb-0">Jika sudah yakin, klik tombol <b>Kumpulkan Ujian</b>. Pastikan waktu tidak habis. Jika waktu habis, jawaban akan tersimpan otomatis dan ujian tertutup.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card bg-light-info">
                        <div class="card-body">
                            <h6><i class="bi bi-wifi text-primary me-2"></i> Tips Koneksi</h6>
                            <p class="text-sm mb-0">
                                Pastikan koneksi internet stabil. Jika tiba-tiba logout atau browser tertutup, silakan login kembali dan lanjutkan ujian. Jawaban Anda tersimpan otomatis.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection(); ?>
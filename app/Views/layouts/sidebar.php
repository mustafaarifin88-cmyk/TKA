<style>
    .sidebar-wrapper {
        background: #ffffff;
        box-shadow: 4px 0 20px rgba(0,0,0,0.05);
        border-right: 1px solid rgba(0,0,0,0.05);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        height: 100vh;
    }
    
    html[data-bs-theme="dark"] .sidebar-wrapper {
        background: #151521;
        border-right: 1px solid #2b2b40;
    }

    .sidebar-menu {
        flex-grow: 1;
        overflow-y: auto;
        padding-bottom: 50px;
    }

    .sidebar-profile-card {
        background: linear-gradient(135deg, #b71c1c 0%, #ef5350 100%);
        margin: 20px 20px 10px 20px;
        padding: 20px;
        border-radius: 16px;
        color: white;
        box-shadow: 0 8px 20px rgba(183, 28, 28, 0.3);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    @media (min-width: 1200px) {
        .sidebar-profile-card:hover {
            transform: translateY(-3px);
        }
    }
    
    .sidebar-profile-card::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
        pointer-events: none;
    }
    
    .sidebar-profile-card h6 { 
        color: white !important; 
        font-weight: 700; 
        font-size: 0.95rem;
        letter-spacing: 0.3px; 
    }
    .sidebar-profile-card small { 
        color: rgba(255,255,255,0.85); 
        font-size: 0.75rem;
    }
    .sidebar-profile-card .avatar img { 
        border: 2px solid rgba(255,255,255,0.4); 
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .sidebar-mobile-toggler {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
        color: #555;
        background: rgba(255,255,255,0.8);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .sidebar-menu .menu .sidebar-item {
        margin-bottom: 5px;
        padding: 0 15px;
    }
    .sidebar-menu .menu .sidebar-item .sidebar-link {
        border-radius: 10px;
        transition: all 0.3s ease;
        padding: 10px 15px;
        color: #607080;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }
    
    html[data-bs-theme="dark"] .sidebar-menu .menu .sidebar-item .sidebar-link {
        color: #a0a0c0;
    }

    .sidebar-menu .menu .sidebar-item .sidebar-link i {
        font-size: 1.1rem;
        margin-right: 12px;
        transition: transform 0.2s ease;
        color: #c62828; 
    }
    
    .sidebar-menu .menu .sidebar-item .sidebar-link:hover {
        background-color: rgba(198, 40, 40, 0.08); 
        color: #b71c1c;
    }
    .sidebar-menu .menu .sidebar-item .sidebar-link:hover i {
        transform: translateX(3px);
    }

    .sidebar-menu .menu .sidebar-item.active .sidebar-link {
        background: linear-gradient(90deg, #c62828, #ef5350);
        box-shadow: 0 4px 12px rgba(198, 40, 40, 0.25);
        color: white;
    }
    .sidebar-menu .menu .sidebar-item.active .sidebar-link i {
        color: white;
    }

    .sidebar-menu .menu .sidebar-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #aab;
        margin-top: 20px;
        margin-bottom: 8px;
        padding-left: 20px;
    }

    .sidebar-menu::-webkit-scrollbar { width: 4px; }
    .sidebar-menu::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    .sidebar-wrapper:hover .sidebar-menu::-webkit-scrollbar-thumb { background: #bbb; }
</style>

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        
        <div class="sidebar-header position-relative p-0">
            
            <a href="#" class="sidebar-hide d-xl-none d-block sidebar-mobile-toggler">
                <i class="bi bi-x-lg"></i>
            </a>

            <div class="sidebar-profile-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-lg flex-shrink-0">
                        <img src="<?= base_url('uploads/profil/' . ($active_user['foto'] ?? 'default.jpg')) ?>" 
                             alt="Profile" 
                             style="object-fit: cover; width: 50px; height: 50px;">
                    </div>
                    <div style="overflow: hidden;">
                        <h6 class="mb-0 text-truncate"><?= $active_user['nama_lengkap'] ?? 'User' ?></h6>
                        <small class="text-xs d-block opacity-75"><?= ucfirst($active_user['role'] ?? 'Guest') ?></small>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-white border-opacity-25">
                    <span style="font-size: 0.75rem; opacity: 0.9;"><i class="bi bi-moon-stars me-1"></i> Mode Gelap</span>
                    <div class="form-check form-switch fs-6 m-0">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer; opacity: 0.9;">
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu Utama</li>

                <?php if (session()->get('role') == 'admin') : ?>
                    <li class="sidebar-item <?= (uri_string() == 'admin/dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/dashboard') ?>" class='sidebar-link'>
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= (uri_string() == 'admin/profile') ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/profile') ?>" class='sidebar-link'>
                            <i class="bi bi-person-circle"></i>
                            <span>Profile Saya</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= (uri_string() == 'admin/instansi') ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/instansi') ?>" class='sidebar-link'>
                            <i class="bi bi-building"></i>
                            <span>Profil Instansi</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-title">Master Data Wilayah</li>
                    
                    <li class="sidebar-item <?= (str_contains(uri_string(), 'admin/sekolah')) ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/sekolah') ?>" class='sidebar-link'>
                            <i class="bi bi-houses-fill"></i>
                            <span>Data Sekolah</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= (str_contains(uri_string(), 'admin/mapel')) ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/mapel') ?>" class='sidebar-link'>
                            <i class="bi bi-book-half"></i>
                            <span>Mata Pelajaran</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= (str_contains(uri_string(), 'admin/pengaturan_sekolah')) ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/pengaturan_sekolah') ?>" class='sidebar-link'>
                            <i class="bi bi-gear-wide-connected"></i>
                            <span>Set Mapel Sekolah</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Pengguna</li>

                    <li class="sidebar-item <?= (str_contains(uri_string(), 'admin/pembuat_soal')) ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/pembuat_soal') ?>" class='sidebar-link'>
                            <i class="bi bi-person-badge-fill"></i>
                            <span>User Pembuat Soal</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= (str_contains(uri_string(), 'admin/siswa')) ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/siswa') ?>" class='sidebar-link'>
                            <i class="bi bi-people-fill"></i>
                            <span>Data Siswa</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-title">Manajemen Ujian</li>
                    
                    <li class="sidebar-item <?= (str_contains(uri_string(), 'admin/jadwal')) ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/jadwal') ?>" class='sidebar-link'>
                            <i class="bi bi-calendar-date-fill"></i>
                            <span>Atur Jadwal</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= (str_contains(uri_string(), 'admin/bank_soal')) ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/bank_soal') ?>" class='sidebar-link'>
                            <i class="bi bi-file-earmark-check-fill"></i>
                            <span>Koreksi Soal</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= (str_contains(uri_string(), 'admin/hasil')) ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/hasil') ?>" class='sidebar-link'>
                            <i class="bi bi-bar-chart-line-fill"></i>
                            <span>Monitoring Hasil</span>
                        </a>
                    </li>

                    <li class="sidebar-title">System</li>

                    <li class="sidebar-item <?= (str_contains(uri_string(), 'admin/updater')) ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/updater') ?>" class='sidebar-link'>
                            <i class="bi bi-cloud-arrow-down-fill"></i>
                            <span>Update Sistem</span>
                        </a>
                    </li>

                <?php elseif (session()->get('role') == 'guru') : ?>
                    <li class="sidebar-item <?= (uri_string() == 'guru/dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url('guru/dashboard') ?>" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= (uri_string() == 'guru/profile') ? 'active' : '' ?>">
                        <a href="<?= base_url('guru/profile') ?>" class='sidebar-link'>
                            <i class="bi bi-person-fill"></i>
                            <span>Profile Saya</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-title">Akademik</li>
                    
                    <li class="sidebar-item <?= (str_contains(uri_string(), 'guru/soal')) ? 'active' : '' ?>">
                        <a href="<?= base_url('guru/soal') ?>" class='sidebar-link'>
                            <i class="bi bi-collection-fill"></i>
                            <span>Bank Soal</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= (str_contains(uri_string(), 'guru/nilai')) ? 'active' : '' ?>">
                        <a href="<?= base_url('guru/nilai') ?>" class='sidebar-link'>
                            <i class="bi bi-calculator-fill"></i>
                            <span>Rekap Nilai</span>
                        </a>
                    </li>

                <?php elseif (session()->get('role') == 'siswa') : ?>
                    <li class="sidebar-item <?= (uri_string() == 'siswa/dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url('siswa/dashboard') ?>" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= (uri_string() == 'siswa/profile') ? 'active' : '' ?>">
                        <a href="<?= base_url('siswa/profile') ?>" class='sidebar-link'>
                            <i class="bi bi-person-fill"></i>
                            <span>Profile Saya</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-title">Ujian</li>
                    
                    <li class="sidebar-item <?= (str_contains(uri_string(), 'siswa/ujian')) ? 'active' : '' ?>">
                        <a href="<?= base_url('siswa/ujian') ?>" class='sidebar-link'>
                            <i class="bi bi-pen-fill"></i>
                            <span>Daftar Ujian</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="sidebar-title">Bantuan & Lainnya</li>

                <li class="sidebar-item <?= (uri_string() == 'tutorial') ? 'active' : '' ?>">
                    <a href="<?= base_url('tutorial') ?>" class='sidebar-link'>
                        <i class="bi bi-question-circle-fill"></i>
                        <span>Panduan Aplikasi</span>
                    </a>
                </li>

                <li class="sidebar-item mt-3 mb-4">
                    <a href="<?= base_url('logout') ?>" class='sidebar-link text-danger bg-light-danger border border-danger border-opacity-10' style="border-radius: 12px;">
                        <i class="bi bi-box-arrow-left text-danger"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
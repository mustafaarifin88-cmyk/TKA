<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin & Guru</title>

    <!-- FAVICON DINAMIS -->
    <?php if (!empty($sekolah_data['logo'])) : ?>
        <link rel="shortcut icon" href="<?= base_url('uploads/sekolah/' . $sekolah_data['logo']) ?>" type="image/x-icon">
    <?php else : ?>
        <link rel="shortcut icon" href="<?= base_url('assets/static/images/logo/favicon.svg') ?>" type="image/x-icon">
    <?php endif; ?>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff; /* Background Putih Polos */
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* --- HEADER MERAH (ADMIN THEME) --- */
        .admin-header {
            height: 85px;
            width: 100%;
            display: flex;
            align-items: center;
            padding: 0 50px;
            position: relative;
            z-index: 10;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            
            /* Gradient Dasar Merah */
            background: #c62828; 
            overflow: hidden;
        }

        /* Efek Campuran Warna/Geometris di Header */
        .admin-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100px;
            width: 600px;
            height: 100%;
            background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.15) 50%, transparent 70%);
            transform: skewX(-20deg);
        }

        .admin-header::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            /* Gradient merah tua */
            background: linear-gradient(to left, #b71c1c, transparent);
            opacity: 0.6;
        }

        .header-logo {
            height: 50px;
            margin-right: 15px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
            position: relative;
            z-index: 2;
        }

        .header-title {
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .header-title h1 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            line-height: 1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .header-title span {
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 1px;
            margin-top: 4px;
            opacity: 0.9;
        }

        /* --- LOGIN CARD --- */
        .login-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background-color: #ffffff;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 50px 40px;
            border-radius: 10px;
            
            /* Efek Terangkat */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0,0,0,0.05);
            
            text-align: left;
            position: relative;
            z-index: 5;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .login-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 15px;
        }

        .login-card p {
            font-size: 14px;
            color: #616161;
            margin-bottom: 35px;
            line-height: 1.6;
        }

        /* --- FORM INPUT (MERAH ACCENT) --- */
        .custom-input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .custom-input-group input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
            transition: all 0.3s;
            background-color: #fafafa;
        }

        /* Fokus Merah */
        .custom-input-group input:focus {
            border-color: #e53935;
            background-color: #fff;
            outline: none;
            box-shadow: 0 0 0 4px rgba(229, 57, 53, 0.1);
        }

        .custom-input-group i.icon-start {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #757575;
            font-size: 1.2rem;
            pointer-events: none;
            transition: color 0.3s;
        }

        /* Icon berubah merah saat fokus */
        .custom-input-group input:focus + i.icon-start,
        .custom-input-group:focus-within i.icon-start {
            color: #c62828;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9e9e9e;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 5px;
        }
        .toggle-password:hover {
            color: #616161;
        }

        /* --- TOMBOL LOGIN MERAH --- */
        .btn-admin {
            background-color: #d32f2f; /* Merah */
            border: none;
            color: #fff;
            width: 100%;
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-admin:hover {
            background-color: #b71c1c; /* Merah lebih gelap */
            box-shadow: 0 6px 18px rgba(183, 28, 28, 0.4);
            transform: translateY(-2px);
        }

        /* Alert Error */
        .alert-error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
            padding: 12px 15px;
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-header {
                padding: 0 20px;
                height: 70px;
            }
            .login-card {
                padding: 30px 25px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body>

    <!-- Header Merah -->
    <header class="admin-header">
        <!-- Logo Sekolah Dinamis -->
        <?php if (!empty($sekolah_data['logo'])) : ?>
            <img src="<?= base_url('uploads/sekolah/' . $sekolah_data['logo']) ?>" alt="Logo Sekolah" class="header-logo">
        <?php else : ?>
            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhQFOXlcj2tOqNuOKDC35tPNB_BcLIc8mnUuzdHJDLgIo3bz9FnNEqNgwzMROJDnnDHjfTSwi8XvimNwKfYmhBiTmiZcNta6luGpkB6vzLsMTlLcxqE2kJ4s1Yc7YJLFC659LKSkmrfZmU/s2048/Logo+Sekolah+Dasar+%2528Logo+SD%2529.png" alt="Logo Default" class="header-logo">
        <?php endif; ?>

        <div class="header-title">
            <h1>ADMIN Try Out TKA</h1>
            <span><?= $sekolah_data['nama_sekolah'] ?? 'Aplikasi Ujian Online' ?></span>
        </div>
    </header>

    <!-- Area Login -->
    <div class="login-wrapper">
        <div class="login-card">
            <h3>Login Pengelola</h3>
            <p>Silakan masukkan kredensial Administrator atau Guru untuk melanjutkan.</p>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> 
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Form mengarah ke Auth::prosesLogin (Bukan siswa) -->
            <form action="<?= base_url('auth/proses') ?>" method="POST">
                
                <!-- Username -->
                <div class="custom-input-group">
                    <input type="text" name="username" placeholder="Username / NIP" required autocomplete="off">
                    <i class="bi bi-person-badge-fill icon-start"></i>
                </div>

                <!-- Password -->
                <div class="custom-input-group">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <i class="bi bi-shield-lock-fill icon-start"></i>
                    <i class="bi bi-eye-fill toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
                </div>

                <!-- Tombol -->
                <button type="submit" class="btn btn-admin">Masuk Dashboard</button>

            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var toggleIcon = document.getElementById("toggleIcon");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("bi-eye-fill");
                toggleIcon.classList.add("bi-eye-slash-fill");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("bi-eye-slash-fill");
                toggleIcon.classList.add("bi-eye-fill");
            }
        }
    </script>

</body>
</html>
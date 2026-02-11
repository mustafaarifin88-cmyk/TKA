<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Ujian</title>

    <!-- FAVICON DINAMIS -->
    <?php if (!empty($sekolah_data['logo'])) : ?>
        <link rel="shortcut icon" href="<?= base_url('uploads/sekolah/' . $sekolah_data['logo']) ?>" type="image/x-icon">
    <?php else : ?>
        <link rel="shortcut icon" href="<?= base_url('assets/static/images/logo/favicon.svg') ?>" type="image/x-icon">
    <?php endif; ?>


    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app-dark.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/iconly.css') ?>">
    
    <style>
        body {
            background-color: #f2f7ff;
        }
        #main {
            margin-left: 0 !important;
            padding: 2rem;
        }
        .card {
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>
    <script src="<?= base_url('assets/static/js/initTheme.js') ?>"></script>
    
    <div id="app">
        <div id="main">
            <?= $this->renderSection('content'); ?>
        </div>
    </div>

    <script src="<?= base_url('assets/static/js/components/dark.js') ?>"></script>
    <script src="<?= base_url('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('assets/compiled/js/app.js') ?>"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Ujian Berbasis Komputer' ?></title>
    
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/extensions/bootstrap-icons/font/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/ujian.css') ?>">

    <style>
        body {
            background-color: #f2f7ff;
            overflow: hidden; 
        }
    </style>
</head>

<body>
    <div id="app">
        <?= $this->renderSection('content'); ?>
    </div>

    <script src="<?= base_url('assets/compiled/js/app.js') ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?= $this->renderSection('scripts'); ?>
</body>

</html>
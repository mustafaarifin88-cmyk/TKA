<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Akun Siswa</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #2d3436; }
        .header { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; text-align: center; position: relative; }
        .logo { width: 70px; position: absolute; left: 10px; top: 0; }
        .title { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .subtitle { margin: 2px 0; font-size: 10pt; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 9pt; }
        .table th, .table td { border: 1px solid #000; padding: 6px; }
        .table th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .footer { margin-top: 20px; text-align: right; font-size: 9pt; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <?php if (!empty($instansi['logo']) && file_exists('uploads/sekolah/' . $instansi['logo'])) : ?>
            <img src="uploads/sekolah/<?= $instansi['logo'] ?>" class="logo">
        <?php endif; ?>
        
        <h2 class="title"><?= $instansi['nama_instansi'] ?? 'APLIKASI UJIAN' ?></h2>
        <p class="subtitle"><?= $instansi['alamat'] ?? '' ?> <?= $instansi['kota'] ?? '' ?></p>
        <p class="subtitle">Data Akun Peserta Ujian</p>
    </div>

    <div style="margin-bottom: 10px;">
        <strong>Sekolah :</strong> <?= $sekolah['nama_sekolah'] ?? '-' ?><br>
        <strong>Kecamatan :</strong> <?= $sekolah['kecamatan'] ?? '-' ?>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Lengkap</th>
                <th width="10%">L/P</th>
                <th width="15%">Tanggal Lahir</th>
                <th width="20%">Username</th>
                <th width="15%">Password Default</th>
                <th width="10%">Ket</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($siswa as $index => $row) : ?>
                <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td class="text-center"><?= $row['jenis_kelamin'] ?></td>
                    <td class="text-center">
                        <?= $row['tanggal_lahir'] ? date('d-m-Y', strtotime($row['tanggal_lahir'])) : '-' ?>
                    </td>
                    <td class="text-center" style="font-weight: bold;"><?= $row['username'] ?></td>
                    <td class="text-center">123456</td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: <?= date('d F Y H:i') ?>
    </div>
</body>
</html>
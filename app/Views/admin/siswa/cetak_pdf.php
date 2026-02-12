<!DOCTYPE html>
<html lang=\"id\">
<head>
    <meta charset=\"UTF-8\">
    <title>Data Akun Siswa - <?= $sekolah['nama_sekolah'] ?></title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #2d3436;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 60px;
            height: auto;
        }
        .text-header {
            text-align: center;
            margin-left: 70px; 
        }
        .text-header h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .text-header p {
            margin: 2px 0;
            font-size: 10pt;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .table th {
            background-color: #f1f2f6;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <div class=\"header\">
        <?php if (!empty($instansi['logo']) && file_exists('uploads/sekolah/' . $instansi['logo'])) : ?>
            <img src=\"uploads/sekolah/<?= $instansi['logo'] ?>\" class=\"logo\">
        <?php else: ?>
            <img src=\"assets/static/images/logo/logo.png\" class=\"logo\">
        <?php endif; ?>

        <div class=\"text-header\">
            <h2><?= $instansi['nama_instansi'] ?? 'APLIKASI UJIAN' ?></h2>
            <p><?= $instansi['alamat'] ?? '' ?> <?= $instansi['kota'] ?? '' ?></p>
        </div>
    </div>

    <div style=\"margin-bottom: 15px;\">
        <strong>Sekolah:</strong> <?= $sekolah['nama_sekolah'] ?><br>
        <strong>Kecamatan:</strong> <?= $sekolah['kecamatan'] ?>
    </div>

    <table class=\"table\">
        <thead>
            <tr>
                <th width=\"5%\">No</th>
                <th width=\"25%\">Nama Lengkap</th>
                <th width=\"5%\">L/P</th>
                <th width=\"15%\">Tgl Lahir</th>
                <th width=\"20%\">Username</th>
                <th width=\"15%\">Password</th>
                <th width=\"15%\">Ket</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($siswa as $index => $row) : ?>
                <tr>
                    <td class=\"text-center\"><?= $index + 1 ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td class=\"text-center\"><?= $row['jenis_kelamin'] ?></td>
                    <td class=\"text-center\"><?= date('d-m-Y', strtotime($row['tanggal_lahir'])) ?></td>
                    <td class=\"text-center text-bold\"><?= $row['username'] ?></td>
                    <td class=\"text-center\">123456</td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class=\"footer\">
        <p>Dicetak pada: <?= date('d F Y') ?></p>
    </div>
</body>
</html>
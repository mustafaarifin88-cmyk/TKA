<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Ujian</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #2d3436;
            line-height: 1.4;
        }
        .header {
            width: 100%;
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 3px solid #0984e3;
            position: relative;
            min-height: 80px;
        }
        .logo {
            width: 80px;
            height: auto;
            position: absolute;
            top: 0;
            left: 0;
        }
        .header-text {
            text-align: center;
            margin-left: 90px;
        }
        .header-text h2 {
            margin: 0;
            font-size: 20pt;
            color: #2d3436;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header-text p {
            margin: 4px 0;
            font-size: 10pt;
            color: #636e72;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 25px;
            background-color: #f1f2f6;
            padding: 15px;
            border-radius: 5px;
        }
        .meta-table {
            width: 100%;
            font-size: 10pt;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #2d3436;
            width: 130px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .content-table th, .content-table td {
            border: 1px solid #dfe6e9;
            padding: 10px;
            font-size: 10pt;
        }
        .content-table th {
            background-color: #0984e3;
            color: #ffffff;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
            border-color: #0984e3;
        }
        .content-table tr:nth-child(even) {
            background-color: #f1f2f6;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            color: white;
            display: inline-block;
        }
        .badge-success { background-color: #00b894; color: #fff; }
        .badge-danger { background-color: #d63031; color: #fff; }
        
        .score {
            font-weight: bold;
            color: #0984e3;
        }

        .footer {
            width: 100%;
            margin-top: 40px;
        }
        .signature-section {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-line {
            margin-top: 70px;
            border-bottom: 1px solid #2d3436;
            width: 100%;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="header">
        <?php 
            $logoBase64 = '';
            if (!empty($sekolah['logo'])) {
                $path = FCPATH . 'uploads/sekolah/' . $sekolah['logo'];
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            }
        ?>

        <?php if (!empty($logoBase64)) : ?>
            <img src="<?= $logoBase64 ?>" class="logo">
        <?php endif; ?>
        
        <div class="header-text">
            <h2><?= $sekolah['nama_instansi'] ?? 'Instansi' ?></h2>
            <p><?= $sekolah['alamat'] ?? '' ?></p>
            <p><?= $sekolah['kota'] ?? '' ?> - <?= $sekolah['kode_pos'] ?? '' ?></p>
        </div>
    </div>

    <div class="meta-info">
        <table class="meta-table">
            <tr>
                <td class="label">Mata Pelajaran</td>
                <td width="35%">: <?= $jadwal['nama_mapel'] ?></td>
                <td class="label">Sekolah</td>
                <td>: <?= $jadwal['nama_sekolah'] ?></td>
            </tr>
            <tr>
                <td class="label">Pembuat Soal</td>
                <td>: <?= $jadwal['nama_guru'] ?? '-' ?></td>
                <td class="label">Waktu Ujian</td>
                <td>: <?= date('d F Y', strtotime($jadwal['tanggal_ujian'])) ?> (<?= date('H:i', strtotime($jadwal['jam_mulai'])) ?>)</td>
            </tr>
            <tr>
                <td class="label">Bobot Nilai</td>
                <td colspan="3">
                    : PG (<?= $jadwal['bobot_pg'] ?>%) - 
                    PG Komp (<?= $jadwal['bobot_pg_kompleks'] ?>%) - 
                    B/S (<?= $jadwal['bobot_benar_salah'] ?>%) - 
                    Esai (<?= $jadwal['bobot_esai'] ?>%)
                </td>
            </tr>
        </table>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NISN</th>
                <th width="25%">Nama Siswa</th>
                <th width="10%">PG</th>
                <th width="10%">PG Komp</th>
                <th width="10%">B/S</th>
                <th width="10%">Esai</th>
                <th width="15%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($siswa)): ?>
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">Belum ada data siswa yang mengikuti ujian.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($siswa as $key => $s) : ?>
                    <tr>
                        <td class="text-center"><?= $key + 1 ?></td>
                        <td class="text-center"><?= $s['nisn'] ?></td>
                        <td class="text-left" style="font-weight: 500;"><?= $s['nama'] ?></td>
                        <td class="text-center"><?= number_format($s['nilai_pg'], 2) ?></td>
                        <td class="text-center"><?= number_format($s['nilai_pg_kompleks'], 2) ?></td>
                        <td class="text-center"><?= number_format($s['nilai_benar_salah'], 2) ?></td>
                        <td class="text-center"><?= number_format($s['nilai_esai'], 2) ?></td>
                        <td class="text-center score"><?= number_format($s['nilai_akhir'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-section">
            <p><?= $sekolah['kota'] ?? 'Kota' ?>, <?= date('d F Y') ?></p>
            <p style="margin-bottom: 5px;">Mengetahui,</p>
            
            <div class="signature-line"></div>
            
            <p style="margin-top: 5px; font-weight: bold;"><?= $jadwal['nama_guru'] ?? '..........................' ?></p>
        </div>
    </div>

</body>
</html>
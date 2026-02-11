<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Nilai Ujian</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11pt; color: #2d3436; line-height: 1.4; }
        .header { width: 100%; padding-bottom: 20px; margin-bottom: 30px; border-bottom: 3px solid #000; position: relative; min-height: 80px; }
        .logo { width: 80px; height: auto; position: absolute; top: 0; left: 0; }
        .header-text { text-align: center; margin-left: 90px; }
        .header-text h2 { margin: 0; font-size: 18pt; text-transform: uppercase; }
        .header-text p { margin: 2px 0; font-size: 10pt; }
        
        .meta-table { width: 100%; font-size: 10pt; margin-bottom: 20px; }
        .meta-table td { padding: 3px 0; vertical-align: top; }
        .label { font-weight: bold; width: 140px; }

        .grade-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .grade-table th, .grade-table td { border: 1px solid #000; padding: 8px; text-align: center; font-size: 10pt; }
        .grade-table th { background-color: #f2f2f2; font-weight: bold; }
        .grade-table td.left { text-align: left; }
        
        .footer { width: 100%; margin-top: 40px; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-name { margin-top: 70px; font-weight: bold; text-decoration: underline; }
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
            <h2><?= $sekolah['nama_sekolah'] ?></h2>
            <p><?= $sekolah['alamat'] ?></p>
            <p><?= $sekolah['kota'] ?> - <?= $sekolah['kode_pos'] ?></p>
        </div>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Mata Pelajaran</td>
            <td width="35%">: <?= $jadwal['nama_mapel'] ?></td>
            <td class="label">Kelas</td>
            <td>: <?= $jadwal['nama_kelas'] ?></td>
        </tr>
        <tr>
            <td class="label">Guru Pengampu</td>
            <td>: <?= $guru['nama_lengkap'] ?></td>
            <td class="label">Tanggal Ujian</td>
            <td>: <?= date('d F Y', strtotime($jadwal['tanggal_ujian'])) ?></td>
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

    <table class="grade-table">
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
                <tr><td colspan="8">Belum ada data siswa.</td></tr>
            <?php else: ?>
                <?php foreach ($siswa as $key => $s) : ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $s['nisn'] ?></td>
                        <td class="left"><?= $s['nama_lengkap'] ?></td>
                        <td><?= number_format($s['nilai_pg'] ?? 0, 2) ?></td>
                        <td><?= number_format($s['nilai_pg_kompleks'] ?? 0, 2) ?></td>
                        <td><?= number_format($s['nilai_benar_salah'] ?? 0, 2) ?></td>
                        <td><?= number_format($s['nilai_esai'] ?? 0, 2) ?></td>
                        <td style="font-weight: bold; background-color: #f9f9f9;"><?= number_format($s['nilai_total'] ?? 0, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p><?= $sekolah['kota'] ?>, <?= date('d F Y') ?></p>
            <p>Guru Mata Pelajaran,</p>
            <p class="signature-name"><?= $guru['nama_lengkap'] ?></p>
            <p>NIP. <?= $guru['nip'] ?></p>
        </div>
    </div>

</body>
</html>
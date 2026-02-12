<?= $this->extend('layouts/ujian'); ?>

<?= $this->section('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-success text-white text-center py-4">
                    <div class="avatar-lg mb-2">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-0">Ujian Selesai!</h3>
                    <p class="mb-0 text-white-50">Jawaban Anda telah berhasil disimpan.</p>
                </div>
                
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h5 class="text-primary fw-bold"><?= $jadwal['nama_mapel'] ?></h5>
                        <p class="text-muted mb-1"><?= $jadwal['nama_sekolah'] ?></p>
                        <small>Diselesaikan pada: <?= date('d F Y H:i', strtotime($status['waktu_mulai'])) ?></small>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded text-center bg-light">
                                <small class="text-muted d-block text-uppercase">Nilai PG</small>
                                <h4 class="mb-0 fw-bold text-dark"><?= number_format($status['nilai_pg'], 2) ?></h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded text-center bg-light">
                                <small class="text-muted d-block text-uppercase">Nilai Kompleks</small>
                                <h4 class="mb-0 fw-bold text-dark"><?= number_format($status['nilai_pg_kompleks'], 2) ?></h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded text-center bg-light">
                                <small class="text-muted d-block text-uppercase">Nilai B/S</small>
                                <h4 class="mb-0 fw-bold text-dark"><?= number_format($status['nilai_benar_salah'], 2) ?></h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Catatan:</strong> Nilai total akhir akan dikalkulasi setelah Guru memeriksa jawaban Esai Anda.
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3"><i class="bi bi-list-check me-2"></i> Rekapan Jawaban</h5>
                    <div class="accordion" id="accordionReview">
                        <?php foreach ($soal as $index => $s) : ?>
                            <?php 
                                $jawab = $jawaban[$s['id']] ?? null;
                                $kunci = $s['kunci_jawaban'];
                                $isCorrect = false;
                                $reviewClass = 'collapsed';
                                $headerColor = 'text-dark';

                                if ($s['jenis'] == 'pg') {
                                    $isCorrect = ($jawab == $kunci);
                                } elseif ($s['jenis'] == 'pg_kompleks') {
                                    $kArr = json_decode($kunci, true) ?? [];
                                    $jArr = json_decode($jawab ?? '[]', true) ?? [];
                                    sort($kArr); sort($jArr);
                                    $isCorrect = ($kArr === $jArr);
                                    $jawab = implode(', ', $jArr);
                                    $kunci = implode(', ', $kArr);
                                } elseif ($s['jenis'] == 'benar_salah') {
                                    $kArr = json_decode($kunci, true) ?? [];
                                    $jArr = json_decode($jawab ?? '[]', true) ?? [];
                                    $isCorrect = ($kArr === $jArr);
                                    $jawab = "Lihat detail";
                                } elseif ($s['jenis'] == 'esai') {
                                    $headerColor = 'text-warning'; // Menunggu koreksi
                                }

                                if ($s['jenis'] != 'esai') {
                                    $headerColor = $isCorrect ? 'text-success' : 'text-danger';
                                    $icon = $isCorrect ? '<i class="bi bi-check-lg me-2"></i>' : '<i class="bi bi-x-lg me-2"></i>';
                                } else {
                                    $icon = '<i class="bi bi-hourglass-split me-2"></i>';
                                }
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $index ?>">
                                    <button class="accordion-button <?= $reviewClass ?> <?= $headerColor ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
                                        <?= $icon ?> Soal No. <?= $index + 1 ?>
                                    </button>
                                </h2>
                                <div id="collapse<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#accordionReview">
                                    <div class="accordion-body">
                                        <p class="fw-bold mb-2"><?= $s['pertanyaan'] ?></p>
                                        
                                        <?php if ($s['jenis'] == 'benar_salah') : ?>
                                            <table class="table table-sm table-bordered mt-2">
                                                <thead class="bg-light">
                                                    <tr><th>Pernyataan</th><th>Jawaban Kamu</th><th>Kunci</th></tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $stmts = json_decode($s['opsi_a'], true) ?? [];
                                                        $userAns = json_decode($jawaban[$s['id']] ?? '[]', true) ?? [];
                                                        $keyAns = json_decode($s['kunci_jawaban'], true) ?? [];
                                                    ?>
                                                    <?php foreach ($stmts as $idx => $st) : ?>
                                                        <tr>
                                                            <td><?= $st ?></td>
                                                            <td class="<?= ($userAns[$idx]??'') == ($keyAns[$idx]??'') ? 'text-success' : 'text-danger' ?> fw-bold">
                                                                <?= $userAns[$idx] ?? '-' ?>
                                                            </td>
                                                            <td><?= $keyAns[$idx] ?? '-' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="d-flex flex-column gap-1 bg-light p-3 rounded">
                                                <div><span class="badge bg-secondary me-2">Jawaban Kamu:</span> <span class="fw-bold"><?= $jawab ?? '(Kosong)' ?></span></div>
                                                <?php if ($s['jenis'] != 'esai') : ?>
                                                    <div><span class="badge bg-success me-2">Kunci Jawaban:</span> <span class="fw-bold"><?= $kunci ?></span></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card-footer bg-light p-4 text-center">
                    <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-lg px-5">
                        <i class="bi bi-box-arrow-left me-2"></i> Logout
                    </a>
                </div>
            </div>
            
            <div class="text-center mt-3 text-muted">
                &copy; <?= date('Y') ?> Aplikasi Ujian Berbasis Komputer
            </div>
        </div>
    </div>
</div>

<script>
    // Disable Back Button to prevent re-taking exam
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>
<?= $this->endSection(); ?>
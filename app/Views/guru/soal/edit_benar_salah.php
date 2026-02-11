<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Soal Benar / Salah (Majemuk)</h3>
                <p class="text-subtitle text-muted">
                    <span class="badge bg-light-primary text-primary me-2"><i class="bi bi-book me-1"></i> <?= $mapel['nama_mapel'] ?></span>
                    <span class="badge bg-light-info text-info"><i class="bi bi-people me-1"></i> <?= $kelas['nama_kelas'] ?></span>
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-end">
                <a href="<?= base_url("guru/soal/list/{$soal['kelas_id']}/{$soal['mapel_id']}/benar_salah") ?>" class="btn btn-light-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/update/' . $soal['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="jenis" value="benar_salah">

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom pb-3">
                <h5 class="card-title m-0 text-primary">
                    <i class="bi bi-pencil-square me-2"></i> Konten Soal
                </h5>
            </div>
            <div class="card-body pt-4">
                <div class="form-group mb-4">
                    <label class="form-label fw-bold text-dark mb-2">Pertanyaan Utama (Stimulus)</label>
                    <textarea name="pertanyaan" class="form-control" rows="4" required><?= $soal['pertanyaan'] ?></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label text-muted"><i class="bi bi-image me-1"></i> Gambar (Opsional)</label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="file" name="file_soal" class="form-control w-50" accept="image/*">
                        <?php if ($soal['file_soal']) : ?>
                            <img src="<?= base_url('uploads/bank_soal/' . $soal['file_soal']) ?>" height="80" class="img-thumbnail rounded">
                        <?php endif; ?>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="mb-3 text-info"><i class="bi bi-list-check me-2"></i> Tabel Pernyataan & Kunci</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="table_bs">
                        <thead class="bg-light text-center">
                            <tr>
                                <th width="60%">Pernyataan</th>
                                <th width="15%">Benar</th>
                                <th width="15%">Salah</th>
                                <th width="10%"><i class="bi bi-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $pernyataanArr = json_decode($soal['opsi_a'], true) ?? [];
                                $kunciArr = json_decode($soal['kunci_jawaban'], true) ?? [];
                                
                                foreach($pernyataanArr as $idx => $teks) : 
                                    $kunci = $kunciArr[$idx] ?? '';
                            ?>
                                <tr>
                                    <td>
                                        <input type="text" name="pernyataan_sub[]" class="form-control" value="<?= htmlspecialchars($teks) ?>" required>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="kunci_sub[<?= $idx ?>]" value="Benar" <?= ($kunci == 'Benar') ? 'checked' : '' ?> required>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="kunci_sub[<?= $idx ?>]" value="Salah" <?= ($kunci == 'Salah') ? 'checked' : '' ?> required>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-light-danger" onclick="removeRow(this)">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-info text-white mt-2" onclick="addTableRow()">
                        <i class="bi bi-plus-lg"></i> Tambah Baris
                    </button>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-5">
                    <a href="<?= base_url("guru/soal/list/{$soal['kelas_id']}/{$soal['mapel_id']}/benar_salah") ?>" class="btn btn-light-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 shadow"><i class="bi bi-save me-2"></i> Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let globalRowIndex = <?= count($pernyataanArr) > 0 ? count($pernyataanArr) - 1 : 0 ?>;

    window.removeRow = function(btn) {
        let tbody = document.getElementById('table_bs').getElementsByTagName('tbody')[0];
        if (tbody.rows.length > 1) {
            btn.closest('tr').remove();
        } else {
            alert('Minimal harus ada satu pernyataan.');
        }
    }

    window.addTableRow = function() {
        const table = document.getElementById('table_bs').getElementsByTagName('tbody')[0];
        globalRowIndex++; 
        
        const newRow = table.insertRow();
        newRow.innerHTML = `
            <td>
                <input type="text" name="pernyataan_sub[]" class="form-control" placeholder="Isi pernyataan..." required>
            </td>
            <td class="text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="kunci_sub[${globalRowIndex}]" value="Benar" required>
                </div>
            </td>
            <td class="text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="kunci_sub[${globalRowIndex}]" value="Salah" required>
                </div>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-light-danger" onclick="removeRow(this)">
                    <i class="bi bi-x-lg"></i>
                </button>
            </td>
        `;
    }
</script>
<?= $this->endSection(); ?>
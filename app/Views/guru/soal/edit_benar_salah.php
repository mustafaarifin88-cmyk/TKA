<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<div class="page-heading">
    <h3>Edit Soal Benar / Salah</h3>
</div>

<div class="page-content">
    <form action="<?= base_url('guru/soal/update/' . $soal['id']) ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="jenis" value="benar_salah">

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body pt-4">
                <div class="form-group mb-4">
                    <label class="form-label fw-bold">Stimulus</label>
                    <textarea name="pertanyaan" class="summernote-editor" required><?= $soal['pertanyaan'] ?></textarea>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="table_bs">
                        <thead class="bg-light text-center">
                            <tr><th width="60%">Pernyataan</th><th width="15%">Benar</th><th width="15%">Salah</th><th width="10%"><i class="bi bi-trash"></i></th></tr>
                        </thead>
                        <tbody>
                            <?php 
                                $pernyataanArr = json_decode($soal['opsi_a'], true) ?? [];
                                $kunciArr = json_decode($soal['kunci_jawaban'], true) ?? [];
                                foreach($pernyataanArr as $idx => $teks) : 
                                    $kunci = $kunciArr[$idx] ?? '';
                            ?>
                                <tr>
                                    <td><textarea name="pernyataan_sub[]" class="form-control" rows="2" required><?= htmlspecialchars($teks) ?></textarea></td>
                                    <td class="text-center"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="kunci_sub[<?= $idx ?>]" value="Benar" <?= ($kunci == 'Benar') ? 'checked' : '' ?> required></div></td>
                                    <td class="text-center"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="kunci_sub[<?= $idx ?>]" value="Salah" <?= ($kunci == 'Salah') ? 'checked' : '' ?> required></div></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-light-danger" onclick="removeRow(this)"><i class="bi bi-x-lg"></i></button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-info text-white mt-2" onclick="addTableRow()"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-5">
                    <a href="<?= base_url("guru/soal/list/{$soal['sekolah_id']}/{$soal['mapel_id']}/benar_salah") ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 shadow">Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() { $('.summernote-editor').summernote({ height: 150 }); });
    let globalRowIndex = <?= count($pernyataanArr) > 0 ? count($pernyataanArr) - 1 : 0 ?>;
    window.removeRow = function(btn) { if(document.getElementById('table_bs').rows.length > 2) btn.closest('tr').remove(); else alert('Minimal satu pernyataan.'); }
    window.addTableRow = function() {
        globalRowIndex++;
        const table = document.getElementById('table_bs').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        newRow.innerHTML = `<td><textarea name="pernyataan_sub[]" class="form-control" rows="2" required></textarea></td><td class="text-center"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="kunci_sub[${globalRowIndex}]" value="Benar" required></div></td><td class="text-center"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="kunci_sub[${globalRowIndex}]" value="Salah" required></div></td><td class="text-center"><button type="button" class="btn btn-sm btn-light-danger" onclick="removeRow(this)"><i class="bi bi-x-lg"></i></button></td>`;
    }
</script>
<?= $this->endSection(); ?>
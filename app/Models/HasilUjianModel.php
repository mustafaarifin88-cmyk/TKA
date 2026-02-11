<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilUjianModel extends Model
{
    protected $table            = 'hasil_ujian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'jadwal_id',
        'siswa_id',
        'soal_id',
        'jawaban_siswa',
        'nilai_koreksi',
        'waktu_submit'
    ];
}
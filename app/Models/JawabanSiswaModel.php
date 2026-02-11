<?php

namespace App\Models;

use CodeIgniter\Model;

class JawabanSiswaModel extends Model
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
        'ragu_ragu',
        'waktu_submit'
    ];
}
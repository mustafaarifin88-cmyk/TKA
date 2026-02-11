<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusUjianSiswaModel extends Model
{
    protected $table            = 'status_ujian_siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'jadwal_id',
        'siswa_id',
        'waktu_mulai',
        'status',
        'nilai_pg',
        'nilai_pg_kompleks',
        'nilai_benar_salah',
        'nilai_esai',
        'nilai_total'
    ];
}
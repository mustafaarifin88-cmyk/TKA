<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalUjianModel extends Model
{
    protected $table            = 'jadwal_ujian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'guru_id',
        'sekolah_id', // Updated
        'mapel_id',
        'tanggal_ujian',
        'jam_mulai',
        'lama_ujian',
        'status',
        'bobot_pg',
        'bobot_pg_kompleks',
        'bobot_benar_salah',
        'bobot_esai'
    ];
}
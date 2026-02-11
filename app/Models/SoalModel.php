<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table            = 'soal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'guru_id',
        'mapel_id',
        'sekolah_id', // Updated
        'jenis',
        'pertanyaan',
        'file_soal',
        'opsi_a', 'file_a',
        'opsi_b', 'file_b',
        'opsi_c', 'file_c',
        'opsi_d', 'file_d',
        'opsi_e', 'file_e',
        'kunci_jawaban',
        'created_at'
    ];
}
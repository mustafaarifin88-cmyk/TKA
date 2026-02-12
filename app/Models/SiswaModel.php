<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nisn', 
        'nama_lengkap', 
        'tanggal_lahir', 
        'jenis_kelamin', 
        'username', 
        'password', 
        'sekolah_id', 
        'foto'
    ];
}
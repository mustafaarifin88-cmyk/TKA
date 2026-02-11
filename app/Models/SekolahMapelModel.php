<?php

namespace App\Models;

use CodeIgniter\Model;

class SekolahMapelModel extends Model
{
    protected $table            = 'sekolah_mapel';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['sekolah_id', 'mapel_id'];
}
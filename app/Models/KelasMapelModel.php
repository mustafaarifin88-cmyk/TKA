<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasMapelModel extends Model
{
    protected $table            = 'kelas_mapel';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kelas_id', 'mapel_id'];
}
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSoalTable extends Migration
{
    public function up()
    {
        $fields = [
            'jenis' => [
                'name'       => 'jenis',
                'type'       => 'ENUM',
                'constraint' => ['pg', 'esai', 'pg_kompleks', 'benar_salah'],
                'null'       => false,
            ],
            'kunci_jawaban' => [
                'name' => 'kunci_jawaban',
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->modifyColumn('soal', $fields);
    }

    public function down()
    {
        // Mengembalikan ke struktur sebelumnya (Hati-hati: Data jenis baru bisa hilang)
        $fields = [
            'jenis' => [
                'name'       => 'jenis',
                'type'       => 'ENUM',
                'constraint' => ['pg', 'esai'],
                'null'       => false,
            ],
            'kunci_jawaban' => [
                'name'       => 'kunci_jawaban',
                'type'       => 'VARCHAR',
                'constraint' => '1', 
                'null'       => true,
            ],
        ];

        $this->forge->modifyColumn('soal', $fields);
    }
}
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFileToSoal extends Migration
{
    public function up()
    {
        $fields = [
            'file_soal' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'pertanyaan'
            ],
            'file_a' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'opsi_a'
            ],
            'file_b' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'opsi_b'
            ],
            'file_c' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'opsi_c'
            ],
            'file_d' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'opsi_d'
            ],
            'file_e' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'opsi_e'
            ],
        ];

        $this->forge->addColumn('soal', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('soal', ['file_soal', 'file_a', 'file_b', 'file_c', 'file_d', 'file_e']);
    }
}
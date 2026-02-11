<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyOpsiAToText extends Migration
{
    public function up()
    {
        $fields = [
            'opsi_a' => [
                'name' => 'opsi_a',
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->modifyColumn('soal', $fields);
    }

    public function down()
    {
        // Mengembalikan ke tipe VARCHAR(255) jika di-rollback
        // PERINGATAN: Data JSON yang panjang mungkin terpotong jika di-rollback
        $fields = [
            'opsi_a' => [
                'name'       => 'opsi_a',
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ];

        $this->forge->modifyColumn('soal', $fields);
    }
}
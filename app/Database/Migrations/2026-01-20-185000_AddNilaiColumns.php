<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNilaiColumns extends Migration
{
    public function up()
    {
        // 1. Tambah kolom bobot di tabel jadwal_ujian
        $fieldsJadwal = [
            'bobot_pg_kompleks' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 25,
                'after'      => 'bobot_pg',
            ],
            'bobot_benar_salah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 25,
                'after'      => 'bobot_pg_kompleks',
            ],
        ];
        
        // Cek dulu apakah kolom sudah ada agar tidak error saat migrate ulang
        if (!$this->db->fieldExists('bobot_pg_kompleks', 'jadwal_ujian')) {
             $this->forge->addColumn('jadwal_ujian', $fieldsJadwal);
        }

        // 2. Tambah kolom nilai di tabel status_ujian_siswa (Ini penyebab error utama Anda)
        $fieldsStatus = [
            'nilai_pg_kompleks' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'after'      => 'nilai_pg',
            ],
            'nilai_benar_salah' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'after'      => 'nilai_pg_kompleks',
            ],
            // Pastikan nilai_esai dan nilai_total juga ada (jika belum)
            'nilai_esai' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'after'      => 'nilai_benar_salah',
            ],
            'nilai_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'after'      => 'nilai_esai',
            ],
        ];

        if (!$this->db->fieldExists('nilai_pg_kompleks', 'status_ujian_siswa')) {
            // Kita add satu per satu atau batch, tergantung driver. 
            // Untuk aman, kita cek satu persatu yang krusial.
            $colsToAdd = [];
            foreach($fieldsStatus as $key => $field) {
                if (!$this->db->fieldExists($key, 'status_ujian_siswa')) {
                    $colsToAdd[$key] = $field;
                }
            }
            if (!empty($colsToAdd)) {
                $this->forge->addColumn('status_ujian_siswa', $colsToAdd);
            }
        }
        
        // 3. Tambah kolom nilai_koreksi di tabel hasil_ujian (untuk nilai manual esai)
        $fieldsHasil = [
             'nilai_koreksi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'jawaban_siswa',
            ]
        ];
        
        if (!$this->db->fieldExists('nilai_koreksi', 'hasil_ujian')) {
            $this->forge->addColumn('hasil_ujian', $fieldsHasil);
        }
    }

    public function down()
    {
        // Hapus kolom jika rollback
        if ($this->db->fieldExists('bobot_pg_kompleks', 'jadwal_ujian')) {
            $this->forge->dropColumn('jadwal_ujian', ['bobot_pg_kompleks', 'bobot_benar_salah']);
        }
        
        if ($this->db->fieldExists('nilai_pg_kompleks', 'status_ujian_siswa')) {
            $this->forge->dropColumn('status_ujian_siswa', ['nilai_pg_kompleks', 'nilai_benar_salah']);
        }

        if ($this->db->fieldExists('nilai_koreksi', 'hasil_ujian')) {
            $this->forge->dropColumn('hasil_ujian', ['nilai_koreksi']);
        }
    }
}
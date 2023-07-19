<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SumberDanaAlokasi extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sumber_dana' => [
                'type' => 'ENUM',
                'constraint' => ['BOS REGULER', 'BOS DAERAH', 'AFIRMASI/KERJA', 'SILPA', 'BOS LAINNYA'],
                'default' => 'BOS LAINNYA'
            ],
            'belanja_operasi' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => 0
            ],
            'belanja_modal' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => 0
            ],
            'pengeluaran_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('pengeluaran_id', 'pengeluaran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sumber_dana_alokasi_anggaran', true);
    }

    public function down()
    {
        $this->forge->dropTable('sumber_dana_alokasi_anggaran', true);
    }
}
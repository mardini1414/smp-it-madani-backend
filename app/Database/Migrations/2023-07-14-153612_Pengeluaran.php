<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pengeluaran extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_belanja' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'kode_rekening' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'kode_kegiatan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'bulan_tahun' => [
                'type' => 'DATE',
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
        $this->forge->createTable('pengeluaran', true);
    }

    public function down()
    {
        $this->forge->dropTable('pengeluaran', true);
    }
}
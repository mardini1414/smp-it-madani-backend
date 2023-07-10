<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penerimaan extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'kode' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'unique' => true,
            ],
            'bulan_tahun' => [
                'type' => 'DATE'
            ],
            'jumlah' => [
                'type' => 'BIGINT'
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
        $this->forge->createTable('penerimaan', true);
    }

    public function down()
    {
        $this->forge->dropTable('penerimaan');
    }
}
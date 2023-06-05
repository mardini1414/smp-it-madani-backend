<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Classes extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'TINYINT'
            ]
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('kelas', true);
    }

    public function down()
    {
        $this->forge->dropTable('kelas', true);
    }
}
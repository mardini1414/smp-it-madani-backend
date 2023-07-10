<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Admin extends Migration
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
            'jenis_kelamin' => [
                'type' => 'ENUM',
                'constraint' => ['L', 'P'],
                'default' => 'L'
            ],
            'tempat_lahir' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'tanggal_lahir' => [
                'type' => 'DATE'
            ],
            'agama' => [
                'type' => 'VARCHAR',
                'constraint' => 25
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'unique' => true
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('admin', true);
    }

    public function down()
    {
        $this->forge->dropTable('admin', true);
    }
}
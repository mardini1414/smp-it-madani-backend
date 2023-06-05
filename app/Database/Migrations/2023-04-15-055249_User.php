<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'unique' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'password' => [
                'type' => 'TEXT',
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['ADMIN', 'STUDENT'],
                'default' => 'STUDENT'
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
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
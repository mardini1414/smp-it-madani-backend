<?php

namespace Modules\User\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserMigration extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => false
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'unique' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ]
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
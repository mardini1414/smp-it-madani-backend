<?php

namespace Modules\User\Database\Migrations;

use CodeIgniter\Database\Migration;

class RoleMigration extends Migration
{
    public function up()
    {
        $this->forge->addField(['id' => ['type' => 'VARCHAR', 'constraint' => 36]]);
        $this->forge->addField(['name' => ['type' => 'VARCHAR', 'constraint' => 10]]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('roles');
    }

    public function down()
    {
        $this->forge->dropTable('roles');
    }
}
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class News extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true
            ],
            'author' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'Madani Admin'
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'body' => [
                'type' => 'TEXT'
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ]
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('news', true);
    }

    public function down()
    {
        $this->forge->dropTable('news', true);
    }
}
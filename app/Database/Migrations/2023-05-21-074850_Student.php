<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Student extends Migration
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
            'nisn' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'unique' => true,
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
            'nama_wali_murid' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'nik_wali_murid' => [
                'type' => 'VARCHAR',
                'constraint' => 16,
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['AKTIF', 'TIDAK AKTIF'],
                'default' => 'AKTIF'
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'unique' => true
            ],
            'kelas' => [
                'type' => 'TINYINT',
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
        $this->forge->addForeignKey('kelas', 'kelas', 'id');
        $this->forge->createTable('students', true);
    }

    public function down()
    {
        $this->forge->dropTable('students', true);
    }
}
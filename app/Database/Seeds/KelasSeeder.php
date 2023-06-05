<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 10],
            ['id' => 11],
            ['id' => 12],
        ];
        $kelas = $this->db->table('kelas');
        $kelas->insertBatch($data);
    }
}
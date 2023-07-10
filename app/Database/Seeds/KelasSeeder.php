<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ];
        $kelas = $this->db->table('kelas');
        $kelas->insertBatch($data);
    }
}
<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Modules\User\Models\Admin;
use Modules\User\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new User();
        $adminModel = new Admin();
        $dataUser = [
            'username' => 'madaniadmin',
            'email' => 'madaniadmin@gmail.com',
            'role' => 'ADMIN',
            'password' => 'madaniadmin'
        ];
        $userId = $userModel->insert($dataUser);
        $dataAdmin = [
            'nama' => 'Nabila Putri Salsabila',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Bogor',
            'agama' => 'islam',
            'tanggal_lahir' => '2000-01-12',
            'alamat' => 'Jl.Kebon Nangka NO 12 Sukabumi',
            'user_id' => $userId
        ];
        $adminModel->insert($dataAdmin);
    }
}
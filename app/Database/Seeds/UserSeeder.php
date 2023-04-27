<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Modules\User\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'madaniadmin',
            'email' => 'madaniadmin@gmail.com',
            'role' => 'ADMIN',
            'password' => 'madaniadmin'
        ];
        $userModel = new User();
        $userModel->save($data);
    }
}
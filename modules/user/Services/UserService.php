<?php

namespace Modules\User\Services;

use Modules\User\Models\UserModel;

class UserService
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    public function getUser()
    {
        $user = $this->userModel->first();
        unset($user['password']);
        return $user;
    }
}
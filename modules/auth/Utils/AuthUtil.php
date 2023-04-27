<?php

namespace Modules\Auth\Utils;

use Modules\User\Models\User;

class AuthUtil
{
    private static $user;

    public static function getUser()
    {
        return self::$user;
    }

    public static function setUserByUsername($username)
    {
        $userModel = new User();
        $user = $userModel->where('username', $username)->first();
        unset($user['password']);
        self::$user = $user;
    }

}
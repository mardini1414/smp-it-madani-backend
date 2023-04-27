<?php

namespace Modules\Auth\Services;

use App\Exceptions\UnauthorizedException;
use Modules\Auth\Utils\JWTUtil;
use Modules\User\Models\User;

class AuthService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function getJWT($username, $password)
    {
        $user = $this->verifyUser($username, $password);
        if (!$user) {
            throw new UnauthorizedException('user is not valid');
        }
        $jwt = JWTUtil::generate($user);
        return $jwt;
    }

    private function verifyUser($username, $password)
    {
        $user = $this->userModel->where('username', $username)->first();
        if (!$user) {
            return null;
        }
        $isVerifiedPassword = password_verify($password, $user['password']);
        if (!$isVerifiedPassword) {
            return null;
        }
        return $user;
    }
}
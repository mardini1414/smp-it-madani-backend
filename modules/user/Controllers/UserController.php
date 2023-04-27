<?php

namespace Modules\User\Controllers;

use Codeigniter\API\ResponseTrait;
use App\Controllers\BaseController;
use Modules\User\Models\UserModel;
use Modules\User\Services\UserService;

class UserController extends BaseController
{
    use ResponseTrait;

    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function getAll()
    {
        $userModel = new UserModel();
        $userModel->save([
            'username' => 'zainudin',
            'email' => 'dwi@gmail.com',
            'password' => 'password'
        ]);
        return 'user';
    }

    public function getUser()
    {
        $user = $this->userService->getUser();
        return $this->respond(['data' => $user]);
    }
}
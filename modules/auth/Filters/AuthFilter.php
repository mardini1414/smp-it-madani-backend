<?php

namespace Modules\Auth\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Auth\Utils\AuthUtil;
use Modules\Auth\Utils\JWTUtil;

class AuthFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $authorization = $request->getHeaderLine('Authorization');
        $jwt = $authorization ? explode(' ', $authorization)[1] : '';
        $response = service('response');
        if (!$jwt) {
            $response->setStatusCode(401);
            $response->setJSON(['message' => 'unauthorized']);
            return $response;
        }
        try {
            $decoded = JWTUtil::decode($jwt);
            AuthUtil::setUserByUsername($decoded->username);
        } catch (\UnexpectedValueException $e) {
            $response->setStatusCode(401);
            $response->setJSON(['message' => 'unauthorized']);
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
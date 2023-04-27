<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait UUIDTrait
{
    function generateIdWithUUID($data)
    {
        $uuid = Uuid::uuid4();
        $data['data']['id'] = $uuid;
        return $data;
    }
}
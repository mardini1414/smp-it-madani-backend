<?php

namespace Modules\User\Models;

use CodeIgniter\Model;
use App\Traits\UUIDTrait;

class User extends Model
{
    use UUIDTrait;
    protected $DBGroup = 'default';
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['username', 'email', 'password'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['generateIdWithUUID', 'hashPassword'];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function hashPassword($data)
    {
        $hasedPassword = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        $data['data']['password'] = $hasedPassword;
        return $data;
    }
}
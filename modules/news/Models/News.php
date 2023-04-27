<?php

namespace Modules\News\Models;

use CodeIgniter\Model;
use App\Traits\UUIDTrait;

class News extends Model
{
    use UUIDTrait;
    protected $DBGroup = 'default';
    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['title', 'slug', 'author', 'image', 'body'];

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
    protected $beforeInsert = ['generateIdWithUUID', 'generateSlug'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['generateSlug'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function generateSlug($data)
    {
        $title = $data['data']['title'];
        $slug = strtolower(str_replace(' ', '-', $title));
        $data['data']['slug'] = $slug;
        return $data;
    }
}
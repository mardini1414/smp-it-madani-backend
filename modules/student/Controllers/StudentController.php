<?php

namespace Modules\Student\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Modules\Student\Services\StudentService;

class StudentController extends BaseController
{
    use ResponseTrait;

    private $studentService;

    public function __construct()
    {
        $this->studentService = new StudentService();
    }

    public function import()
    {
        $file = $this->request->getFile('file');
        $isValidated = $this->validateFileCSV();
        if (!$isValidated) {
            $data = [
                'errors' => $this->validator->getErrors()
            ];
            return $this->respond($data, 400);
        }
        try {
            $this->studentService->importFromCSV($file);
        } catch (DatabaseException $e) {
            return $this->respond(['message' => 'import gagal'], 400);
        }
        return $this->respondCreated(['message' => 'import berhasil']);
    }

    public function getAll()
    {
        $data = $this->studentService->getAll();
        return $this->respond(['data' => $data]);
    }

    public function export()
    {
        $data = $this->studentService->export();
        $this->response->setContentType('application/pdf');
        return $data;
    }

    public function deleteAll()
    {
        $this->studentService->deleteAll();
        return $this->respond(['message' => 'semua siswa berhasil di hapus']);
    }

    private function validateFileCSV()
    {
        $rule = [
            'file' => 'uploaded[file]|max_size[file,2048]|mime_in[file,text/csv,text/plain]|ext_in[file,csv]'
        ];
        $isValidated = $this->validate($rule);
        return $isValidated;
    }
}
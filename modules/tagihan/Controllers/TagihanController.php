<?php

namespace Modules\Tagihan\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

use Modules\Auth\Utils\AuthUtil;
use Modules\Tagihan\Services\TagihanService;

class TagihanController extends BaseController
{
    use ResponseTrait;

    private $tagihanService;

    public function __construct()
    {
        $this->tagihanService = new TagihanService();
    }

    public function create()
    {
        $request = [
            'nama' => $this->request->getVar('nama'),
            'kode' => $this->request->getvar('kode'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'jatuh_tempo' => $this->request->getVar('jatuh_tempo'),
            'jumlah' => $this->request->getVar('jumlah')
        ];

        $isValidatedTagihan = $this->validatedAddTagihan();
        if (!$isValidatedTagihan) {
            $data = [
                'errors' => $this->validator->getErrors()
            ];
            return $this->respond($data, 400);
        }
        try {
            $this->tagihanService->create($request);
            return $this->respond(['message' => 'tagihan berhasil dibuat']);
        } catch (\Throwable $th) {
            return $this->respond(['message' => 'terjadi kesalahan ketika membuat tagihan'], 400);
        }
    }

    public function getAll()
    {
        $data = $this->tagihanService->getAll();
        return $this->respond($data);
    }

    public function getOne($id)
    {
        $data = $this->tagihanService->getOne($id);
        if ($data !== null) {
            return $this->respond(['data' => $data]);
        } else {
            return $this->respond(['message' => 'tagihan tidak ditemukan'], 404);
        }
    }

    public function getByStudent()
    {
        $user = AuthUtil::getUser();
        $data = $this->tagihanService->getByStudent($user['id']);
        return $this->respond(['data' => $data]);
    }

    public function cancle($id)
    {
        $data = $this->tagihanService->getOne($id);
        if ($data !== null) {
            $this->tagihanService->cancle($id);
            return $this->respond(['message' => 'tagihan berhasil dibatalkan']);
        } else {
            return $this->respond(['message' => 'tagihan tidak di temukan'], 404);
        }
    }

    public function export()
    {
        $data = $this->tagihanService->export();
        $this->response->setContentType('application/pdf');
        return $data;
    }

    private function validatedAddTagihan()
    {
        $rules = [
            'nama' => 'required|max_length[50]|min_length[10]',
            'kode' => 'required|min_length[3]|max_length[50]|is_unique[tagihan.kode]',
            'deskripsi' => 'required|min_length[10]',
            'jatuh_tempo' => 'required|valid_date[Y-m-d]',
            'jumlah' => 'required|integer|greater_than[0]'
        ];
        $isValidated = $this->validate($rules);
        return $isValidated;
    }
}
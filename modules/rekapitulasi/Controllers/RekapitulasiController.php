<?php

namespace Modules\Rekapitulasi\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Modules\Auth\Utils\AuthUtil;
use Modules\Rekapitulasi\Services\RekapitulasiService;

class RekapitulasiController extends BaseController
{
    use ResponseTrait;

    private $rekapitulasiService;

    public function __construct()
    {
        $this->rekapitulasiService = new RekapitulasiService();
    }

    public function getAll()
    {
        $status = $this->request->getVar('status');
        $kelas = $this->request->getVar('kelas');
        $data = $this->rekapitulasiService->getAll($status, $kelas);
        return $this->respond($data);
    }

    public function getOne($id)
    {
        $data = $this->rekapitulasiService->getOne($id);
        if (!$data) {
            return $this->respond(['message' => 'rekapitulasi tidak di temukan'], 404);
        } else {
            return $this->respond(['data' => $data]);
        }
    }

    public function getByStudent()
    {
        $user = AuthUtil::getUser();
        $data = $this->rekapitulasiService->getByStudent($user['id']);
        return $this->respond($data);
    }

    public function export()
    {
        $status = $this->request->getVar('status');
        $kelas = $this->request->getVar('kelas');
        $data = $this->rekapitulasiService->export($status, $kelas);
        $this->response->setContentType('application/pdf');
        return $data;
    }

    public function exportByStudent()
    {
        $user = AuthUtil::getUser();
        $data = $this->rekapitulasiService->exportByStudent($user['id']);
        $this->response->setContentType('application/pdf');
        return $data;
    }
}
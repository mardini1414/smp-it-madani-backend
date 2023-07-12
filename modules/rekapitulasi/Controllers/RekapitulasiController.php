<?php

namespace Modules\Rekapitulasi\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Modules\Rekapitulasi\Services\RekapitulasiService;

class RekapitulasiController extends BaseController
{
    use ResponseTrait;

    private $rekapitulasiService;

    public function __construct()
    {
        $this->rekapitulasiService = new RekapitulasiService();
    }

    public function get()
    {
        $status = $this->request->getVar('status');
        $kelas = $this->request->getVar('kelas');
        $data = $this->rekapitulasiService->get($status, $kelas);
        return $this->respond($data);
    }

    public function getByStudent()
    {

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

    }
}
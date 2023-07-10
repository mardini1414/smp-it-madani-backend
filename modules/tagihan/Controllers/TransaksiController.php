<?php

namespace Modules\Tagihan\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Modules\Tagihan\Services\TransaksiService;

class TransaksiController extends BaseController
{

    use ResponseTrait;

    private $transaksiService;

    public function __construct()
    {
        $this->transaksiService = new TransaksiService();
    }
    public function createInvoice($id)
    {
        try {
            $data = $this->transaksiService->createInvoice($id);
            return $this->respond(['data' => $data]);
        } catch (\Throwable $th) {
            return $this->respond(['message' => 'gagal membuat invoice'], 400);
        }
    }

    public function pay()
    {
        try {
            $this->transaksiService->pay();
            return $this->respond(['message' => 'success membayar']);
        } catch (\Throwable $th) {
            return $this->respond(['message' => "gagal membayar"]);
        }
    }
}
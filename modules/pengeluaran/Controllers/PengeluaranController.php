<?php

namespace Modules\Pengeluaran\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Modules\Pengeluaran\Services\PengeluaranService;

class PengeluaranController extends BaseController
{

    use ResponseTrait;

    private $pengeluaranService;

    public function __construct()
    {
        $this->pengeluaranService = new PengeluaranService();
    }

    public function getAll()
    {
        $date = $this->request->getVar('date');
        $data = $this->pengeluaranService->getAll($date);
        return $this->respond($data);
    }

    public function add()
    {
        $request = $this->request->getVar();
        if (!$this->isValidatedPengeluaran()) {
            $data = [
                'errors' => $this->validator->getErrors()
            ];
            return $this->respond($data, 400);
        }
        $this->pengeluaranService->add($request);
        return $this->respond(['message' => 'pengeluaran berhasil dibuat']);
    }

    public function getOne($id)
    {
        $data = $this->pengeluaranService->getOne($id);
        if (!$data['pengeluaran']) {
            return $this->respond(['message' => 'pengeluaran tidak di temukan'], 404);
        }
        return $this->respond(['data' => $data]);
    }

    public function update($id)
    {
        $request = $this->request->getVar();
        $data = $this->pengeluaranService->getOne($id);
        if (!$data['pengeluaran']) {
            return $this->respond(['message' => 'pengeluaran tidak di temukan'], 404);
        }
        if (!$this->isValidatedPengeluaran()) {
            $data = [
                'errors' => $this->validator->getErrors()
            ];
            return $this->respond($data, 400);
        }
        $this->pengeluaranService->update($request, $id);
        return $this->respond(['message' => 'pengeluaran berhasil diupdate']);
    }

    public function delete($id)
    {
        $data = $this->pengeluaranService->getOne($id);
        if (!$data['pengeluaran']) {
            return $this->respond(['message' => 'pengeluaran tidak di temukan'], 404);
        }
        $this->pengeluaranService->delete($id);
        return $this->respond(['message' => 'pengeluaran berhasil dihapus']);
    }

    public function export()
    {
        $date = $this->request->getVar('date');
        $data = $this->pengeluaranService->export($date);
        $this->response->setContentType('application/pdf');
        return $data;
    }

    private function isValidatedPengeluaran()
    {
        $rules = [
            'nama_belanja' => 'required|max_length[50]|min_length[10]',
            'kode_rekening' => 'required|min_length[10]|max_length[20]',
            'kode_kegiatan' => 'required|min_length[3]|max_length[5]',
            'bulan_tahun' => 'required|valid_date[Y-m-d]',
            'sumber_dana_alokasi_anggaran.*.belanja_operasi' => 'integer',
            'sumber_dana_alokasi_anggaran.*.belanja_modal' => 'integer'
        ];
        $isValidated = $this->validate($rules);
        return $isValidated;
    }
}
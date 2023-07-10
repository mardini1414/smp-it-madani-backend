<?php

namespace Modules\Penerimaan\Controllers;

use App\Controllers\BaseController;
use Codeigniter\API\ResponseTrait;
use Modules\Penerimaan\Services\PenerimaanService;

class PenerimaanController extends BaseController
{
    use ResponseTrait;

    private $penerimaanService;

    public function __construct()
    {
        $this->penerimaanService = new PenerimaanService();
    }

    public function getAll()
    {
        $date = $this->request->getVar('date');
        $data = $this->penerimaanService->getAll($date);
        return $this->respond($data);
    }

    public function getOne($id)
    {
        $data = $this->penerimaanService->getOne($id);
        if ($data !== null) {
            return $this->respond(['data' => $data]);
        } else {
            return $this->respond(['message' => 'penerimaan tidak di temukan'], 404);
        }
    }

    public function store()
    {
        $request = [
            'nama' => $this->request->getVar('nama'),
            'kode' => $this->request->getVar('kode'),
            'bulan_tahun' => $this->request->getVar('bulan_tahun'),
            'jumlah' => $this->request->getVar('jumlah'),
        ];
        $isValidatedPenerimaan = $this->validatedAddPenerimaan();
        if (!$isValidatedPenerimaan) {
            $data = [
                'errors' => $this->validator->getErrors()
            ];
            return $this->respond($data, 400);
        }
        $this->penerimaanService->store($request);
        return $this->respond(['message' => 'penerimaan berhasil dibuat']);
    }

    public function update($id)
    {
        $request = [
            'nama' => $this->request->getVar('nama'),
            'kode' => $this->request->getVar('kode'),
            'bulan_tahun' => $this->request->getVar('bulan_tahun'),
            'jumlah' => $this->request->getVar('jumlah'),
        ];
        $isValidatedPenerimaan = $this->validatedUpdatePenerimaan($id);
        if (!$isValidatedPenerimaan) {
            $data = [
                'errors' => $this->validator->getErrors()
            ];
            return $this->respond($data, 400);
        }
        $this->penerimaanService->update($id, $request);
        return $this->respond(['message' => 'penerimaan berhasil diupdate']);
    }

    public function delete($id)
    {
        $data = $this->penerimaanService->getOne($id);
        if ($data !== null) {
            $this->penerimaanService->delete($id);
            return $this->respond(['message' => 'penerimaan berhasil dihapus']);
        } else {
            return $this->respond(['message' => 'penerimaan tidak di temukan'], 404);
        }
    }

    public function export()
    {
        $date = $this->request->getVar('date');
        $data = $this->penerimaanService->export($date);
        $this->response->setContentType('application/pdf');
        return $data;
    }

    private function validatedAddPenerimaan()
    {
        $rules = [
            'nama' => 'required|max_length[50]|min_length[10]',
            'kode' => 'required|min_length[3]|max_length[50]|is_unique[penerimaan.kode]',
            'bulan_tahun' => 'required|valid_date[Y-m-d]',
            'jumlah' => 'required|integer|greater_than[0]'
        ];
        $isValidated = $this->validate($rules);
        return $isValidated;
    }

    private function validatedUpdatePenerimaan($id)
    {
        $rules = [
            'nama' => 'required|max_length[50]|min_length[10]',
            'kode' => "required|min_length[3]|max_length[50]|is_unique[penerimaan.kode,id,{$id}]",
            'bulan_tahun' => 'required|valid_date[Y-m-d]',
            'jumlah' => 'required|integer|greater_than[0]'
        ];
        $isValidated = $this->validate($rules);
        return $isValidated;
    }
}
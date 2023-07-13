<?php

namespace Modules\Penerimaan\Services;

use Modules\Penerimaan\Models\Penerimaan;

class PenerimaanService
{

    private $penerimaanModel;

    public function __construct()
    {
        $this->penerimaanModel = new Penerimaan();
    }

    public function getAll($date)
    {
        if ($date !== null) {
            $year = date('Y', strtotime($date));
            $month = date('m', strtotime($date));
            $penerimaan = $this->penerimaanModel
                ->where('YEAR(bulan_tahun)', $year)
                ->where('MONTH(bulan_tahun)', $month)
                ->orderBy('created_at', 'DESC')
                ->findAll();
            $total = $this->penerimaanModel->selectSum('jumlah')
                ->where('YEAR(bulan_tahun)', $year)
                ->where('MONTH(bulan_tahun)', $month)
                ->first();
            $data = [
                'data' => $penerimaan,
                'total' => $total['jumlah'] ?? 0,
            ];
            return $data;
        } else {
            $penerimaan = $this->penerimaanModel->findAll();
            $total = $this->penerimaanModel->selectSum('jumlah')->first();
            $data = [
                'data' => $penerimaan,
                'total' => $total['jumlah'] ?? 0,
            ];
            return $data;
        }
    }

    public function getOne($id)
    {
        $data = $this->penerimaanModel->find($id);
        return $data;
    }

    public function store($request)
    {
        $this->penerimaanModel->insert($request);
    }

    public function update($id, $request)
    {
        $this->penerimaanModel->update($id, $request);
    }

    public function delete($id)
    {
        $data = $this->penerimaanModel->delete($id);
        return $data;
    }

    public function export($date)
    {
        $data = $this->getAll($date);
        $mpdf = new \Mpdf\Mpdf();
        $html = view('pdf/penerimaan', ['data' => $data]);
        $mpdf->WriteHTML($html);
        return $mpdf->OutputHttpDownload('penerimaan.pdf');
    }
}
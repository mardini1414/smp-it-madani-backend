<?php

namespace Modules\Tagihan\Services;

use Modules\Tagihan\Models\Transaksi;

class TransaksiService
{

    private $apiKey;
    private $merchanCode;
    private $duitkuConfig;
    private $db;
    private $transaksiModel;

    public function __construct()
    {
        $this->apiKey = env('DUITKU_API_KEY');
        $this->merchanCode = env('DUITKU_MERCHANT');
        $this->duitkuConfig = new \Duitku\Config($this->apiKey, $this->merchanCode);
        $this->db = \Config\Database::connect();
        $this->transaksiModel = new Transaksi();
    }

    public function createInvoice($id)
    {
        $transaction = $this->getTransaction($id);
        $paymentAmount = $transaction->jumlah;
        $email = $transaction->email_siswa;
        $productDetails = $transaction->nama_tagihan;
        $merchantOrderId = $transaction->transaksi_id;
        $callbackUrl = env('DUITKU_URL_CALLBACK');
        $customerVaName = $transaction->nama_siswa;
        $returnUrl = env('DUITKU_URL_RETURN');
        $expiryPeriod = 60;

        $item1 = array(
            'name' => $productDetails,
            'price' => $paymentAmount,
            'quantity' => 1
        );

        $itemDetails = array(
            $item1
        );

        $params = array(
            'paymentAmount' => $paymentAmount,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $productDetails,
            'customerVaName' => $customerVaName,
            'email' => $email,
            'itemDetails' => $itemDetails,
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'expiryPeriod' => $expiryPeriod
        );
        try {
            $data = \Duitku\Pop::createInvoice($params, $this->duitkuConfig);
            return json_decode($data);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function pay()
    {
        try {
            $callback = \Duitku\Pop::callback($this->duitkuConfig);
            $notif = json_decode(strval($callback));
            $id = $notif->merchantOrderId;
            if ($notif->resultCode == "00") {
                log_message('debug', 'success');
                $this->transaksiModel->update($id, ['status' => 'success']);
            } else if ($notif->resultCode == "01") {
                log_message('debug', 'failed');
                $this->transaksiModel->update($id, ['status' => 'failed']);
            }
        } catch (\Exception $e) {
            log_message('debug', 'error');
            throw new \Exception($e->getMessage());
        }
    }

    private function getTransaction($id)
    {
        $data = $this->db->table('transaksi')
            ->select(
                'transaksi.id AS transaksi_id, tagihan.nama AS nama_tagihan, 
                tagihan.jatuh_tempo AS jatuh_tempo, tagihan.jumlah AS jumlah, 
                transaksi.status AS status, students.nama AS nama_siswa, users.email AS email_siswa,
                transaksi.created_at AS created_at, transaksi.updated_at AS updated_at'
            )
            ->join('tagihan', 'tagihan.id = transaksi.tagihan_id')
            ->join('users', 'users.id = transaksi.user_id')
            ->join('students', 'students.user_id = users.id')
            ->where('transaksi.id', $id)
            ->get()->getFirstRow();
        return $data;
    }
}
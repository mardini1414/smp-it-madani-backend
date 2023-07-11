<?php

namespace Modules\Tagihan\Services;

use CodeIgniter\Database\Exceptions\DatabaseException;
use Modules\Tagihan\Models\Tagihan;
use Modules\Tagihan\Models\Transaksi;
use Modules\User\Models\User;

class TagihanService
{

    private $db;
    private $tagihanModel;
    private $userModel;
    private $transaksiModel;
    private $transaksiService;
    private $tagihanId;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->tagihanModel = new Tagihan();
        $this->userModel = new User();
        $this->transaksiModel = new Transaksi();
        $this->transaksiService = new TransaksiService();
    }

    public function create($request)
    {
        try {
            $this->db->transException(true)->transStart();
            $tagihanId = $this->tagihanModel->insert($request);
            $this->tagihanId = $tagihanId;
            $transactions = $this->createTransactionList();
            $this->transaksiModel->insertBatch($transactions);
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function getAll()
    {
        $tagihan = $this->tagihanModel->findAll();
        $total = $this->tagihanModel->selectSum('jumlah')->first();
        $data = [
            'data' => $tagihan,
            'total' => $total['jumlah'] ?? 0,
        ];
        return $data;
    }

    public function getOne($id)
    {
        $data = $this->tagihanModel->find($id);
        return $data;
    }

    public function getByStudent($userId, $status)
    {
        $statusList = explode('|', $status);
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
            ->where('transaksi.user_id', $userId)
            ->whereIn('transaksi.status', $statusList)
            ->get()->getResult();
        return $data;
    }

    public function cancle($id)
    {
        $data = $this->tagihanModel->delete($id);
        return $data;
    }

    public function export()
    {
        $data = $this->getAll();
        $mpdf = new \Mpdf\Mpdf();
        $html = view('pdf/tagihan', ['data' => $data]);
        $mpdf->WriteHTML($html);
        return $mpdf->OutputHttpDownload('tagihan.pdf');
    }


    public function exportOne($id)
    {
        $data = $this->transaksiService->getTransaction($id);
        $mpdf = new \Mpdf\Mpdf();
        $html = view('pdf/bukti-pembayaran', ['data' => $data]);
        $mpdf->WriteHTML($html);
        return $mpdf->OutputHttpDownload('bukti-pembayaran.pdf');
    }

    public function exportByStudent()
    {

    }

    private function createTransactionList()
    {
        $users = $this->userModel->select('id')
            ->where('role', 'STUDENT')->findAll();
        $transactions = array_map(function ($user) {
            return [
                'user_id' => $user['id'],
                'tagihan_id' => $this->tagihanId
            ];
        }, $users);
        return $transactions;
    }
}
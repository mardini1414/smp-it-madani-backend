<?php

namespace Modules\Rekapitulasi\Services;

class RekapitulasiService
{

    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getAll($status, $kelas)
    {
        $statusList = explode('|', $status);
        $transactions = $this->db->table('transaksi')
            ->select(
                "transaksi.id AS transaksi_id, tagihan.nama AS nama_tagihan, 
                tagihan.jatuh_tempo AS jatuh_tempo, tagihan.jumlah AS jumlah,
                IF(transaksi.status = 'success', transaksi.updated_at, '-') AS tanggal_bayar, 
                transaksi.status AS status, students.nama AS nama_siswa, 
                students.kelas AS kelas,
                users.email AS email_siswa,
                users.username AS NIS,
                transaksi.created_at AS created_at, transaksi.updated_at AS updated_at"
            )
            ->join('tagihan', 'tagihan.id = transaksi.tagihan_id')
            ->join('users', 'users.id = transaksi.user_id')
            ->join('students', 'students.user_id = users.id')
            ->whereIn('transaksi.status', $statusList)
            ->where('students.kelas', $kelas)
            ->orderBy('transaksi.created_at', 'DESC')
            ->get()->getResult();

        $total = $this->db->table('transaksi')
            ->selectSum('tagihan.jumlah')->join('tagihan', 'tagihan.id = transaksi.tagihan_id')
            ->join('users', 'users.id = transaksi.user_id')
            ->join('students', 'students.user_id = users.id')
            ->whereIn('transaksi.status', $statusList)
            ->where('students.kelas', $kelas)
            ->get()->getFirstRow();

        $data = [
            'data' => $transactions,
            'total' => $total->jumlah ?? 0
        ];
        return $data;
    }

    public function getOne($id)
    {
        $data = $this->db->table('transaksi')
            ->select(
                "transaksi.id AS transaksi_id, tagihan.nama AS nama_tagihan, 
                tagihan.jatuh_tempo AS jatuh_tempo, tagihan.jumlah AS jumlah,
                tagihan.kode AS tagihan_kode, tagihan.deskripsi AS tagihan_deskripsi,
                tagihan.created_at AS tanggal_dibuat,
                IF(transaksi.status = 'success', transaksi.updated_at, '-') AS tanggal_bayar, 
                transaksi.status AS status, students.nama AS nama_siswa, 
                students.kelas AS kelas,
                users.email AS email_siswa,
                users.username AS NIS,
                transaksi.created_at AS created_at, transaksi.updated_at AS updated_at"
            )
            ->join('tagihan', 'tagihan.id = transaksi.tagihan_id')
            ->join('users', 'users.id = transaksi.user_id')
            ->join('students', 'students.user_id = users.id')
            ->where('transaksi.id', $id)
            ->orderBy('transaksi.created_at', 'DESC')
            ->get()->getFirstRow();
        return $data;
    }

    public function getByStudent($userId)
    {
        $transactions = $this->db->table('transaksi')
            ->select(
                "transaksi.id AS transaksi_id, tagihan.nama AS nama_tagihan, 
            tagihan.jatuh_tempo AS jatuh_tempo, tagihan.jumlah AS jumlah, 
            IF(transaksi.status = 'success', transaksi.updated_at, '-') AS tanggal_bayar,
            transaksi.status AS status, students.nama AS nama_siswa, students.kelas AS kelas,
            users.email AS email_siswa,
            users.username AS NIS,
            transaksi.created_at AS created_at, transaksi.updated_at AS updated_at"
            )
            ->join('tagihan', 'tagihan.id = transaksi.tagihan_id')
            ->join('users', 'users.id = transaksi.user_id')
            ->join('students', 'students.user_id = users.id')
            ->where('transaksi.status', 'success')
            ->where('users.id', $userId)
            ->orderBy('transaksi.created_at', 'DESC')
            ->get()->getResult();

        $total = $this->db->table('transaksi')
            ->selectSum('tagihan.jumlah')->join('tagihan', 'tagihan.id = transaksi.tagihan_id')
            ->join('users', 'users.id = transaksi.user_id')
            ->join('students', 'students.user_id = users.id')
            ->where('transaksi.status', 'success')
            ->where('users.id', $userId)
            ->get()->getFirstRow();

        $data = [
            'data' => $transactions,
            'total' => $total->jumlah ?? 0
        ];
        return $data;
    }

    public function export($status, $kelas)
    {
        $data = $this->getAll($status, $kelas);
        $mpdf = new \Mpdf\Mpdf();
        $html = view('pdf/rekapitulasi', ['data' => $data]);
        $mpdf->WriteHTML($html);
        return $mpdf->OutputHttpDownload('rekapitulasi.pdf');
    }

    public function exportByStudent($userId)
    {
        $data = $this->getByStudent($userId);
        $mpdf = new \Mpdf\Mpdf();
        $html = view('pdf/rekapitulasi', ['data' => $data]);
        $mpdf->WriteHTML($html);
        return $mpdf->OutputHttpDownload('rekapitulasi.pdf');
    }
}
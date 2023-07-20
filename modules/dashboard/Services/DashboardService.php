<?php

namespace Modules\Dashboard\Services;

use Modules\Penerimaan\Models\Penerimaan;

class DashboardService
{
    private $db;
    private $penerimaanModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->penerimaanModel = new Penerimaan();
    }

    public function getAllTotal()
    {
        $totalPemasukan = $this->getTotalPemasukan();
        $totalPengeluaran = intval($this->getTotalPengeluaran());
        $totalKas = $totalPemasukan - $totalPengeluaran;

        $data = [
            'Kas' => $totalKas,
            'Pemasukan' => $totalPemasukan,
            'Pengeluaran' => $totalPengeluaran
        ];
        return $data;
    }
    public function getPieChart()
    {
        $totalPemasukan = $this->getTotalPemasukan();
        $totalPengeluaran = intval($this->getTotalPengeluaran());
        $totalKas = $totalPemasukan - $totalPengeluaran;
        $data = [
            [
                'name' => 'Kas',
                'value' => $totalKas
            ],
            [
                'name' => 'Pemasukan',
                'value' => $totalPemasukan,
            ],
            [
                'name' => 'Pengeluaran',
                'value' => $totalPengeluaran,
            ]
        ];
        return $data;
    }

    public function getLineChart()
    {
        $data = [
            [
                'name' => "Januari",
                'pemasukan' => $this->getTotalPemasukanByMonth(1),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(1)),
                'kas' => $this->getTotalKasByMonth(1),
            ],
            [
                'name' => "Februari",
                'pemasukan' => $this->getTotalPemasukanByMonth(2),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(2)),
                'kas' => $this->getTotalKasByMonth(2),
            ],
            [
                'name' => "Maret",
                'pemasukan' => $this->getTotalPemasukanByMonth(3),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(3)),
                'kas' => $this->getTotalKasByMonth(3),
            ],
            [
                'name' => "April",
                'pemasukan' => $this->getTotalPemasukanByMonth(4),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(4)),
                'kas' => $this->getTotalKasByMonth(4),
            ],
            [
                'name' => "Mei",
                'pemasukan' => $this->getTotalPemasukanByMonth(5),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(5)),
                'kas' => $this->getTotalKasByMonth(5),
            ],
            [
                'name' => "Juni",
                'pemasukan' => $this->getTotalPemasukanByMonth(6),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(6)),
                'kas' => $this->getTotalKasByMonth(6),
            ],
            [
                'name' => "Juli",
                'pemasukan' => $this->getTotalPemasukanByMonth(7),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(7)),
                'kas' => $this->getTotalKasByMonth(7),
            ],
            [
                'name' => "Agustus",
                'pemasukan' => $this->getTotalPemasukanByMonth(8),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(8)),
                'kas' => $this->getTotalKasByMonth(8),
            ],
            [
                'name' => "September",
                'pemasukan' => $this->getTotalPemasukanByMonth(9),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(9)),
                'kas' => $this->getTotalKasByMonth(9),
            ],
            [
                'name' => "Oktober",
                'pemasukan' => $this->getTotalPemasukanByMonth(10),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(10)),
                'kas' => $this->getTotalKasByMonth(10),
            ],
            [
                'name' => "November",
                'pemasukan' => $this->getTotalPemasukanByMonth(11),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(11)),
                'kas' => $this->getTotalKasByMonth(11),
            ],
            [
                'name' => "Desember",
                'pemasukan' => $this->getTotalPemasukanByMonth(12),
                'pengeluran' => intval($this->getTotalPengeluaranByMonth(12)),
                'kas' => $this->getTotalKasByMonth(12),
            ],
        ];
        return $data;
    }

    private function getTotalKasByMonth($month)
    {
        $pemasukan = $this->getTotalPemasukanByMonth($month);
        $pengeluaran = intval($this->getTotalPengeluaranByMonth($month));
        $total = $pemasukan - $pengeluaran;
        return $total;
    }

    private function getTotalPemasukan()
    {
        $penerimaan = $this->penerimaanModel->selectSum('jumlah')->first();
        $rekapitulasi = $this->db->table('transaksi')
            ->selectSum('tagihan.jumlah')->join('tagihan', 'tagihan.id = transaksi.tagihan_id')
            ->join('users', 'users.id = transaksi.user_id')
            ->join('students', 'students.user_id = users.id')
            ->where('transaksi.status', 'success')
            ->get()->getFirstRow();
        $totalPenerimaan = $penerimaan['jumlah'] ?? 0;
        $totalRekapitulasi = $rekapitulasi->jumlah ?? 0;
        return $totalPenerimaan + $totalRekapitulasi;
    }

    private function getTotalPemasukanByMonth($month)
    {
        $year = date('Y');
        $penerimaan = $this->penerimaanModel->selectSum('jumlah')
            ->where('MONTH(created_at)', $month)
            ->where('YEAR(created_at)', $year)
            ->first();
        $rekapitulasi = $this->db->table('transaksi')
            ->selectSum('tagihan.jumlah')->join('tagihan', 'tagihan.id = transaksi.tagihan_id')
            ->join('users', 'users.id = transaksi.user_id')
            ->join('students', 'students.user_id = users.id')
            ->where('transaksi.status', 'success')
            ->where('MONTH(transaksi.created_at)', $month)
            ->where('YEAR(transaksi.created_at)', $year)
            ->get()->getFirstRow();
        $totalPenerimaan = $penerimaan['jumlah'] ?? 0;
        $totalRekapitulasi = $rekapitulasi->jumlah ?? 0;
        return $totalPenerimaan + $totalRekapitulasi;
    }

    private function getTotalPengeluaran()
    {
        $builder = $this->db->table('pengeluaran');
        $data = $builder->select('SUM(sumber_dana_alokasi_anggaran.belanja_operasi) + SUM(sumber_dana_alokasi_anggaran.belanja_modal) AS jumlah')
            ->join('sumber_dana_alokasi_anggaran', 'sumber_dana_alokasi_anggaran.pengeluaran_id = pengeluaran.id')
            ->get()->getFirstRow();
        return $data->jumlah ?? 0;
    }

    private function getTotalPengeluaranByMonth($month)
    {
        $year = date('Y');
        $builder = $this->db->table('pengeluaran');
        $data = $builder->select('SUM(sumber_dana_alokasi_anggaran.belanja_operasi) + SUM(sumber_dana_alokasi_anggaran.belanja_modal) AS jumlah')
            ->join('sumber_dana_alokasi_anggaran', 'sumber_dana_alokasi_anggaran.pengeluaran_id = pengeluaran.id')
            ->where('MONTH(pengeluaran.created_at)', $month)
            ->where('YEAR(pengeluaran.created_at)', $year)
            ->get()->getFirstRow();
        return $data->jumlah ?? 0;
    }
}
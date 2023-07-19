<?php

namespace Modules\Pengeluaran\Services;

use CodeIgniter\Database\Exceptions\DatabaseException;
use Modules\Pengeluaran\Models\Pengeluaran;
use Modules\Pengeluaran\Models\SumberDanaAlokasiAnggaran;

class PengeluaranService
{

    private $db;
    private $pengeluaranModel;
    private $sumberDanaAlokasiAnggaranModel;
    private $pengeluaranId;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->pengeluaranModel = new Pengeluaran();
        $this->sumberDanaAlokasiAnggaranModel = new SumberDanaAlokasiAnggaran();
    }

    public function getAll($date)
    {
        if ($date !== null) {
            $year = date('Y', strtotime($date));
            $month = date('m', strtotime($date));
            $builder = $this->db->table('pengeluaran');
            $pengeluaran = $builder->select(
                'pengeluaran.id AS id,
            pengeluaran.nama_belanja AS nama_belanja, pengeluaran.kode_rekening AS kode_rekening,
            pengeluaran.kode_kegiatan AS kode_kegiatan, pengeluaran.bulan_tahun AS bulan_tahun,
            (select SUM(sumber_dana_alokasi_anggaran.belanja_operasi) + SUM(sumber_dana_alokasi_anggaran.belanja_modal)) AS jumlah'
            )->join('sumber_dana_alokasi_anggaran', 'sumber_dana_alokasi_anggaran.pengeluaran_id = pengeluaran.id')
                ->where('YEAR(bulan_tahun)', $year)
                ->where('MONTH(bulan_tahun)', $month)
                ->groupBy('sumber_dana_alokasi_anggaran.pengeluaran_id')
                ->orderBy('pengeluaran.created_at', 'DESC')->get()->getResult();

            $total = $builder->select('SUM(sumber_dana_alokasi_anggaran.belanja_operasi) + SUM(sumber_dana_alokasi_anggaran.belanja_modal) AS jumlah')
                ->join('sumber_dana_alokasi_anggaran', 'sumber_dana_alokasi_anggaran.pengeluaran_id = pengeluaran.id')
                ->where('YEAR(bulan_tahun)', $year)
                ->where('MONTH(bulan_tahun)', $month)
                ->get()->getFirstRow();
            $data = [
                'pengeluaran' => $pengeluaran,
                'total' => $total->jumlah ?? 0
            ];
            return $data;
        } else {
            $builder = $this->db->table('pengeluaran');
            $pengeluaran = $builder->select(
                'pengeluaran.id AS id,
            pengeluaran.nama_belanja AS nama_belanja, pengeluaran.kode_rekening AS kode_rekening,
            pengeluaran.kode_kegiatan AS kode_kegiatan, pengeluaran.bulan_tahun AS bulan_tahun,
            (select SUM(sumber_dana_alokasi_anggaran.belanja_operasi) + SUM(sumber_dana_alokasi_anggaran.belanja_modal)) AS jumlah'
            )->join('sumber_dana_alokasi_anggaran', 'sumber_dana_alokasi_anggaran.pengeluaran_id = pengeluaran.id')
                ->groupBy('sumber_dana_alokasi_anggaran.pengeluaran_id')
                ->orderBy('pengeluaran.created_at', 'DESC')->get()->getResult();

            $total = $builder->select('SUM(sumber_dana_alokasi_anggaran.belanja_operasi) + SUM(sumber_dana_alokasi_anggaran.belanja_modal) AS jumlah')
                ->join('sumber_dana_alokasi_anggaran', 'sumber_dana_alokasi_anggaran.pengeluaran_id = pengeluaran.id')
                ->get()->getFirstRow();
            $data = [
                'pengeluaran' => $pengeluaran,
                'total' => $total->jumlah ?? 0
            ];
            return $data;
        }
    }

    public function getOne($id)
    {
        $builder = $this->db->table('pengeluaran');
        $pengeluaran = $builder->select(
            'pengeluaran.id AS id,
            pengeluaran.nama_belanja AS nama_belanja, pengeluaran.kode_rekening AS kode_rekening,
            pengeluaran.kode_kegiatan AS kode_kegiatan, pengeluaran.bulan_tahun AS bulan_tahun,
            (select SUM(sumber_dana_alokasi_anggaran.belanja_operasi) + SUM(sumber_dana_alokasi_anggaran.belanja_modal)) AS jumlah'
        )->join('sumber_dana_alokasi_anggaran', 'sumber_dana_alokasi_anggaran.pengeluaran_id = pengeluaran.id')
            ->where('pengeluaran.id', $id)
            ->groupBy('sumber_dana_alokasi_anggaran.pengeluaran_id')
            ->get()->getFirstRow();

        $sumberDanaAlokasiAnggaran = $this->sumberDanaAlokasiAnggaranModel
            ->where('pengeluaran_id', $id)->findAll();
        $data = [
            'pengeluaran' => $pengeluaran,
            'sumber_dana_alokasi_anggaran' => $sumberDanaAlokasiAnggaran
        ];
        return $data;
    }

    public function add($request)
    {
        try {
            $this->db->transException(true)->transStart();
            $sumberDanaAlokasiAnggaran = $request->sumber_dana_alokasi_anggaran;
            unset($request->sumber_dana_alokasi_anggaran);
            $this->pengeluaranId = $this->pengeluaranModel->insert($request);
            if ($sumberDanaAlokasiAnggaran->bos_reguler) {
                $this->sumberDanaAlokasiAnggaranModel->insert([
                    'sumber_dana' => 'BOS REGULER',
                    'belanja_operasi' => $sumberDanaAlokasiAnggaran->bos_reguler->belanja_operasi,
                    'belanja_modal' => $sumberDanaAlokasiAnggaran->bos_reguler->belanja_modal,
                    'pengeluaran_id' => $this->pengeluaranId
                ]);
            }
            if ($sumberDanaAlokasiAnggaran->bos_daerah) {
                $this->sumberDanaAlokasiAnggaranModel->insert([
                    'sumber_dana' => 'BOS DAERAH',
                    'belanja_operasi' => $sumberDanaAlokasiAnggaran->bos_daerah->belanja_operasi,
                    'belanja_modal' => $sumberDanaAlokasiAnggaran->bos_daerah->belanja_modal,
                    'pengeluaran_id' => $this->pengeluaranId
                ]);
            }
            if ($sumberDanaAlokasiAnggaran->afirmasi_kerja) {
                $this->sumberDanaAlokasiAnggaranModel->insert([
                    'sumber_dana' => 'AFIRMASI/KERJA',
                    'belanja_operasi' => $sumberDanaAlokasiAnggaran->afirmasi_kerja->belanja_operasi,
                    'belanja_modal' => $sumberDanaAlokasiAnggaran->afirmasi_kerja->belanja_modal,
                    'pengeluaran_id' => $this->pengeluaranId
                ]);
            }
            if ($sumberDanaAlokasiAnggaran->silpa) {
                $this->sumberDanaAlokasiAnggaranModel->insert([
                    'sumber_dana' => 'SILPA',
                    'belanja_operasi' => $sumberDanaAlokasiAnggaran->silpa->belanja_operasi,
                    'belanja_modal' => $sumberDanaAlokasiAnggaran->silpa->belanja_modal,
                    'pengeluaran_id' => $this->pengeluaranId
                ]);
            }
            if ($sumberDanaAlokasiAnggaran->bos_lainnya) {
                $this->sumberDanaAlokasiAnggaranModel->insert([
                    'sumber_dana' => 'BOS LAINNYA',
                    'belanja_operasi' => $sumberDanaAlokasiAnggaran->bos_lainnya->belanja_operasi,
                    'belanja_modal' => $sumberDanaAlokasiAnggaran->bos_lainnya->belanja_modal,
                    'pengeluaran_id' => $this->pengeluaranId
                ]);
            }
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function update($request, $id)
    {
        try {
            $this->db->transException(true)->transStart();
            $sumberDanaAlokasiAnggaran = $request->sumber_dana_alokasi_anggaran;
            unset($request->sumber_dana_alokasi_anggaran);
            $this->pengeluaranModel->update($id, $request);
            if ($sumberDanaAlokasiAnggaran->bos_reguler) {
                $this->sumberDanaAlokasiAnggaranModel->where('sumber_dana', 'BOS REGULER')
                    ->where('pengeluaran_id', $id)->set([
                            'belanja_operasi' => $sumberDanaAlokasiAnggaran->bos_reguler->belanja_operasi,
                            'belanja_modal' => $sumberDanaAlokasiAnggaran->bos_reguler->belanja_modal,
                        ])->update();
            }
            if ($sumberDanaAlokasiAnggaran->bos_daerah) {
                $this->sumberDanaAlokasiAnggaranModel->where('sumber_dana', 'BOS DAERAH')
                    ->where('pengeluaran_id', $id)->set([
                            'belanja_operasi' => $sumberDanaAlokasiAnggaran->bos_daerah->belanja_operasi,
                            'belanja_modal' => $sumberDanaAlokasiAnggaran->bos_daerah->belanja_modal,
                        ])->update();
            }
            if ($sumberDanaAlokasiAnggaran->afirmasi_kerja) {
                $this->sumberDanaAlokasiAnggaranModel->where('sumber_dana', 'AFIRMASI/KERJA')
                    ->where('pengeluaran_id', $id)->set([
                            'belanja_operasi' => $sumberDanaAlokasiAnggaran->afirmasi_kerja->belanja_operasi,
                            'belanja_modal' => $sumberDanaAlokasiAnggaran->afirmasi_kerja->belanja_modal,
                        ])->update();
            }
            if ($sumberDanaAlokasiAnggaran->silpa) {
                $this->sumberDanaAlokasiAnggaranModel->where('sumber_dana', 'SILPA')
                    ->where('pengeluaran_id', $id)->set([
                            'belanja_operasi' => $sumberDanaAlokasiAnggaran->silpa->belanja_operasi,
                            'belanja_modal' => $sumberDanaAlokasiAnggaran->silpa->belanja_modal,
                        ])->update();
            }
            if ($sumberDanaAlokasiAnggaran->bos_lainnya) {
                $this->sumberDanaAlokasiAnggaranModel->where('sumber_dana', 'BOS LAINNYA')
                    ->where('pengeluaran_id', $id)->set([
                            'belanja_operasi' => $sumberDanaAlokasiAnggaran->bos_lainnya->belanja_operasi,
                            'belanja_modal' => $sumberDanaAlokasiAnggaran->bos_lainnya->belanja_modal,
                        ])->update();
            }
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function delete($id)
    {
        $this->pengeluaranModel->delete($id);
    }

    public function export($date)
    {
        $data = $this->getAll($date);
        $mpdf = new \Mpdf\Mpdf();
        $html = view('pdf/pengeluaran', ['data' => $data]);
        $mpdf->WriteHTML($html);
        return $mpdf->OutputHttpDownload('pengeluaran.pdf');
    }
}
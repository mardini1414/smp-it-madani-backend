<?php

namespace Modules\Student\Services;

use CodeIgniter\Database\Exceptions\DatabaseException;
use League\Csv\Reader;
use Modules\Student\Models\Student;
use Modules\User\Models\User;

class StudentService
{
    private $db;
    private $userModel;
    private $studentModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->userModel = new User();
        $this->studentModel = new Student();
    }

    public function importFromCSV($file)
    {
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(';');
        $records = $csv->getRecords();
        try {
            $this->db->transException(true)->transStart();
            foreach ($records as $record) {
                $password = str_replace('/', '-', $record['tanggal lahir']);
                $timeStamp = strtotime($password);
                $date = date('Y-m-d', $timeStamp);
                $userId = $this->userModel->insert([
                    'username' => $record['nisn'],
                    'email' => $record['email'],
                    'password' => $password,
                    'role' => 'STUDENT'
                ]);
                $this->studentModel->insert([
                    'nama' => $record['nama'],
                    'nisn' => $record['nisn'],
                    'tanggal_lahir' => $date,
                    'tempat_lahir' => $record['tempat lahir'],
                    'nama_wali_murid' => $record['nama wali murid'],
                    'nik_wali_murid' => $record['nik wali murid'],
                    'alamat' => $record['alamat'],
                    'status' => $record['status'],
                    'user_id' => $userId,
                    'kelas' => $record['kelas'],
                    'agama' => $record['agama']
                ]);
            }
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function getAll()
    {
        $data = $this->studentModel->orderBy('created_at', 'DESC')->findAll();
        return $data;
    }

    public function export()
    {
        $data = $this->getAll();
        $mpdf = new \Mpdf\Mpdf();
        $html = view('pdf/siswa', ['data' => $data]);
        $mpdf->WriteHTML($html);
        return $mpdf->OutputHttpDownload('siswa.pdf');
    }

    public function deleteAll()
    {
        $isDeleted = $this->userModel
            ->where('role', 'STUDENT')
            ->delete();
        return $isDeleted;
    }
}
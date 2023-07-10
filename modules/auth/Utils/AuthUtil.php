<?php

namespace Modules\Auth\Utils;

use Modules\User\Models\User;

class AuthUtil
{
    private static $user;

    public static function getUser()
    {
        return self::$user;
    }

    public static function setUserByUsername($username)
    {
        $userModel = new User();
        if ($username === 'madaniadmin') {
            $user = $userModel->select(
                'users.id AS id, admin.nama AS nama,users.username AS username, users.email AS email,
                 admin.tanggal_lahir AS tanggal_lahir, admin.jenis_kelamin AS jenis_kelamin,
                 admin.agama AS agama, admin.tempat_lahir AS tempat_lahir,
                 admin.alamat AS alamat, admin.created_at AS created_at, admin.updated_at AS updated_at'
            )
                ->join('admin', 'admin.user_id = users.id')
                ->where('username', $username)->first();
            self::$user = $user;
        } else {
            $user = $userModel->select(
                'users.id AS id, students.nama AS nama, students.nisn AS nisn, users.email AS email,
                 students.tanggal_lahir AS tanggal_lahir, students.jenis_kelamin AS jenis_kelamin,
                 students.agama AS agama, students.tempat_lahir AS tempat_lahir, students.nama_wali_murid AS nama_wali_murid,
                 students.nik_wali_murid AS nik_wali_murid, students.alamat AS alamat, students.status AS status,
                 students.created_at AS created_at, students.updated_at AS updated_at'
            )
                ->join('students', 'students.user_id = users.id')
                ->where('username', $username)->first();
            self::$user = $user;
        }
    }

}
<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

interface UserRepositoryInterface
{
    public function save(array $data);
    public function list();
    public function findByUsername(string $username);
    public function userDetail(int $userId);
    public function changeRoleId(int $from, int $to);
}

class UserRepository implements UserRepositoryInterface
{
    private $table = 'users';

    public function save(array $data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function list()
    {
        return DB::table($this->table)
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('pegawai', 'pegawai.id', '=', 'users.pegawai_id')
            ->get([
                'users.id',
                'users.username',
                'users.password',
                'pegawai.nip',
                'pegawai.nrp',
                'roles.name',
                'users.status'
            ]);
    }

    public function findByUsername(string $username)
    {
        return DB::table($this->table)->where('username', $username)->first();
    }

    public function userDetail(int $userId)
    {
        return DB::table($this->table)
            ->where('users.id', $userId)
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('pegawai', 'pegawai.id', '=', 'users.pegawai_id')
            ->get([
                'users.id',
                'users.username',
                'users.password',
                'users.email',
                'pegawai.nip',
                'pegawai.nrp',
                'roles.name AS role_name',
            ])
            ->first();
    }

    public function changeRoleId(int $from, int $to)
    {
        return DB::table($this->table)
            ->where('role_id', $from)
            ->update(['role_id' => $to]);
    }
}

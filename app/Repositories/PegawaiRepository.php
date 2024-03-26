<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface PegawaiRepositoryInterface
{
    public function changeRoleId(int $from, int $to);
    public function update(int $id, array $data);
    public function userList();
    public function updateUser(int $id, array $data);
    public function userDetail(int $userId);
    public function findWithConditions(array $conditions);
    public function findByNip(string $nip);
    public function findById(int $pegawaiId);
    public function findUserById($userId);
    public function findUserWithConditions(array $conditions);
}

class PegawaiRepository implements PegawaiRepositoryInterface
{
    private $table = "pegawai";

    public function findByNip(string $nip)
    {
        return DB::table($this->table)->where('nip', $nip)->first();
    }

    public function findById(int $pegawaiId)
    {
        return DB::table($this->table)->find($pegawaiId);
    }

    public function update(int $id, array $data)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->update($data);
    }

    public function changeRoleId(int $from, int $to)
    {
        return DB::table($this->table)
            ->where('role_id', $from)
            ->update(['role_id' => $to]);
    }

    public function findWithConditions(array $conditions)
    {
        return DB::table($this->table)
            ->where($conditions)
            ->first();
    }

    public function userList()
    {
        return DB::table($this->table)
            ->join('roles', 'roles.id', '=', 'pegawai.role_id')
            ->whereNotNull('pegawai.role_id')
            ->get([
                'pegawai.id',
                'pegawai.username',
                'pegawai.password',
                'pegawai.nip',
                'pegawai.nrp',
                'roles.name',
                'pegawai.role_status'
            ]);
    }

    public function findUserById($userId)
    {
        return DB::table($this->table)
            ->where('id', $userId)
            ->whereNotNull('role_id')
            ->first();
    }

    public function updateUser(int $id, array $data)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->whereNotNull('role_id')
            ->update($data);
    }

    public function userDetail(int $userId)
    {
        return DB::table($this->table)
            ->where('pegawai.id', $userId)
            ->whereNotNull('role_id')
            ->join('roles', 'roles.id', '=', 'pegawai.role_id')
            ->get([
                'pegawai.id',
                'pegawai.username',
                'pegawai.password',
                'pegawai.email',
                'pegawai.nip',
                'pegawai.nrp',
                'roles.name AS role_name',
            ])
            ->first();
    }

    public function findUserWithConditions(array $conditions)
    {
        return DB::table($this->table)
            ->where($conditions)
            ->whereNotNull('role_id')
            ->first();
    }
}

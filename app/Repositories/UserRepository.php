<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

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
    public function save(array $data)
    {
        return User::create($data);
    }

    public function list()
    {
        return User::join('roles', 'roles.id', '=', 'users.role_id')
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
        return User::where('username', $username)->first();
    }

    public function userDetail(int $userId)
    {
        return User::where('users.id', $userId)
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
        return User::where('role_id', $from)->update(['role_id' => $to]);
    }
}

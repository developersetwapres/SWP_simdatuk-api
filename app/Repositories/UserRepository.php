<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

interface UserRepositoryInterface
{
    public function save(array $data);
    public function list();
    public function findByUsername(string $username);
}

class UserRepository implements UserRepositoryInterface
{
    public function save(array $data)
    {
        return User::create($data);
    }

    public function findByUsername(string $username)
    {
        return User::where('username', $username)->first();
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
}

<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface UserRegistrationRepositoryInterface
{
    public function save(array $data);
    public function findByUsername(string $username);
    public function findByKey(string $key);
    public function deleteByKey(string $key);
}

class UserRegistrationRepository implements UserRegistrationRepositoryInterface
{
    private $table = 'user_registrations';

    public function save(array $data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function findByUsername(string $username)
    {
        return DB::table($this->table)->where('username', $username)->first();
    }

    public function findByKey(string $key)
    {
        return DB::table($this->table)
            ->where('verification_key', $key)
            ->first();
    }

    public function deleteByKey(string $key)
    {
        return DB::table($this->table)
            ->where('verification_key', $key)
            ->delete();
    }
}

<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface UserRegistrationRepositoryInterface
{
    public function save(array $data);
    public function findByUsername(string $username);
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
}

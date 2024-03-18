<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

interface UserRepositoryInterface
{
    public function save(array $data);
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
}

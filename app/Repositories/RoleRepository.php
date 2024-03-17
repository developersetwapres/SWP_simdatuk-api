<?php

namespace App\Repositories;

use App\Models\Role;

interface RoleRepositoryInterface
{
    public function findByName(string $name);
}

class RoleRepository
{
    public function findByName(string $name)
    {
        return Role::where('name', $name)->first();
    }
}
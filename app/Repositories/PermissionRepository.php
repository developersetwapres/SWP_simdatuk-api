<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface PermissionRepositoryInterface
{
    public function list();
    public function listGroup();
}

class PermissionRepository implements PermissionRepositoryInterface
{
    private $table = 'permissions';

    public function list()
    {
        return DB::table($this->table)->get([
            'id',
            'group',
            'name',
            'permitted_actions'
        ]);
    }

    public function listGroup()
    {
        return DB::table($this->table)->get('group')->groupBy('group');
    }
}

<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface SubbagianRepositoryInterface
{
    public function findById(int $id);
}

class SubbagianRepository implements SubbagianRepositoryInterface
{
    private $table = 'subbagian';

    public function findById(int $id)
    {
        return DB::table($this->table)->find($id);
    }
}

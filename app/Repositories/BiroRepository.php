<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface BiroRepositoryInterface
{
    public function findById(int $id);
}

class BiroRepository implements BiroRepositoryInterface
{
    private $table = 'biro';

    public function findById(int $id)
    {
        return DB::table($this->table)->find($id);
    }
}
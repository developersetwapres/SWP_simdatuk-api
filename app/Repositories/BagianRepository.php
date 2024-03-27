<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface BagianRepositoryInterface
{
    public function findById(int $id);
}

class BagianRepository implements BagianRepositoryInterface
{
    private $table = 'bagian';

    public function findById(int $id)
    {
        return DB::table($this->table)->find($id);
    }
}

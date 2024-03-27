<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface EselonRepositoryInterface
{
    public function findById(int $id);
}

class EselonRepository implements EselonRepositoryInterface
{
    private $table = 'eselon';

    public function findById(int $id)
    {
        return DB::table($this->table)->find($id);
    }
}
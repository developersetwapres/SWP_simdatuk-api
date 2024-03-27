<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface DeputiRepositoryInterface
{
    public function findById(int $id);
}

class DeputiRepository implements DeputiRepositoryInterface
{
    private $table = 'deputi';

    public function findById(int $id)
    {
        return DB::table($this->table)->find($id);
    }
}
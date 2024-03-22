<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface PegawaiRepositoryInterface
{
    public function findByNip(string $nip);
}

class PegawaiRepository implements PegawaiRepositoryInterface
{
    private $table = "pegawai";

    public function findByNip(string $nip)
    {
        return DB::table($this->table)->where('nip', $nip)->first();
    }
}
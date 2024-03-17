<?php

namespace App\Repositories;

use App\Models\Pegawai;

interface PegawaiRepositoryInterface
{
    public function findByNip(string $nip);
}

class PegawaiRepository implements PegawaiRepositoryInterface
{
    public function findByNip(string $nip)
    {
        return Pegawai::where('nip', $nip)->first();
    }
}
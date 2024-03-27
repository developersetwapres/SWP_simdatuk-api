<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

interface JabatanRepositoryInterface
{
    public function list();
    public function save(array $data);
}

class JabatanRepository implements JabatanRepositoryInterface
{
    private $table = 'master_jabatan';

    public function list()
    {
        return DB::table($this->table . ' AS mj')
            ->leftJoin('eselon AS e', 'e.id', '=', 'mj.eselon_id')
            ->leftJoin('deputi AS d', 'd.id', '=', 'mj.deputi_id')
            ->leftJoin('biro AS b', 'b.id', '=', 'mj.biro_id')
            ->select([
                'mj.id',
                'mj.nama AS jabatan',
                'e.nama AS eselon',
                'd.nama AS deputi',
                'b.nama AS biro'
            ])
            ->get();
    }

    public function save(array $data)
    {
        return DB::table($this->table)->insert($data);
    }
}

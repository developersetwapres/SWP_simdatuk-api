<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class EmployeesImport implements ToArray, WithChunkReading
{
    public $data;

    public function array($rows)
    {
        $this->data[] = $rows;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

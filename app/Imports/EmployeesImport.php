<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class EmployeesImport implements ToArray
{
    public $data;

    public function array($rows)
    {
        $this->data[] = $rows;
    }
}

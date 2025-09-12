<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements SkipsEmptyRows, ToModel, WithBatchInserts, WithHeadingRow
{
    public function model(array $row)
    {
        dd($row);

    }

    public function batchSize(): int
    {
        return 1000;
    }
}

<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmailsImport implements SkipsEmptyRows, ToModel, WithBatchInserts, WithHeadingRow
{
    // WithValidation
    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        dd($row);

        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
        ]);
    }

    //    public function rules(): array
    //    {
    //        return [
    //            '*.Name' => 'required',
    //            '*.email' => [
    //                'required',
    //                'email',
    //                Rule::unique(User::class),
    //                new UnauthorizedEmailProviders(),
    //            ],
    //        ];
    //    }

    public function batchSize(): int
    {
        return 1000;
    }
}

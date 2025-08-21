<?php

namespace App\Repositories;

use App\Models\Email\BlackListEmail;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;

class BlackListEmailRepository
{
    public function getBlackListEmailPaginate(): LengthAwarePaginator
    {
        return BlackListEmail::with('user')->orderByDesc('id')->paginate(10);
    }

    public function deleteBlackListEmail(BlackListEmail $blackListEmail): ?bool
    {
        $path = config('disposable-email.blacklist_file');

        $fullFilePath = $path.'/'.$blackListEmail->file;

        if (File::exists($fullFilePath)) {
            File::delete($fullFilePath);
        }

        return $blackListEmail->delete();
    }
}

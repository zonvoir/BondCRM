<?php

namespace App\Repositories\Black;

use App\Models\Black\BlackEmail;
use App\Models\Black\BlackEmailText;
use App\Models\Black\BlackListKeyword;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;

class BlackRepository
{
    public function getBlackEmailTextPaginate(): LengthAwarePaginator
    {
        return BlackEmailText::with('user')->orderByDesc('id')->paginate(10);
    }

    public function deleteBlackListEmail(BlackEmailText $blackListEmail): ?bool
    {
        $path = config('disposable-email.blacklist_file');

        $fullFilePath = $path.'/'.$blackListEmail->file;

        if (File::exists($fullFilePath)) {
            File::delete($fullFilePath);
        }

        return $blackListEmail->delete();
    }

    public function getBlackEmailPaginate(): LengthAwarePaginator
    {
        return BlackEmail::query()->where('user_id', auth()->id())->orderByDesc('id')->paginate(10);
    }

    public function getBlackListKeywordPaginate(): LengthAwarePaginator
    {
        return BlackListKeyword::query()->where('user_id', auth()->id())->orderByDesc('id')->paginate(10);
    }
}

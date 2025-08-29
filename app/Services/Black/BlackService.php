<?php

namespace App\Services\Black;

use App\Models\Black\BlackEmail;
use App\Models\Black\BlackEmailText;
use App\Models\Black\BlackListKeyword;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class BlackService
{
    use FileUploadTrait;

    public function saveBlackEmailTextUpload(array $data)
    {
        if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
            $uploadPath = config('disposable-email.blacklist_file');

            $data['file'] = $this->uploadFile(
                $data['file'],
                $uploadPath,
            );
        }

        BlackEmailText::query()->create([
            'file' => basename($data['file']),
        ]);
    }

    public function saveBlackEmail(array $data): Model|BlackEmail
    {
        $user_id = auth()->id();

        return BlackEmail::query()->updateOrCreate(
            [
                'email' => $data['email'],
                'user_id' => $user_id,
            ],
            array_merge($data, ['user_id' => $user_id])
        );
    }

    public function saveBlackListKeywordEmail(array $data): Model|BlackListKeyword
    {
        $user_id = auth()->id();

        return BlackListKeyword::query()->updateOrCreate(
            [
                'keyword' => $data['keyword'],
                'user_id' => $user_id,
            ],
            array_merge($data, ['user_id' => $user_id])
        );
    }
}

<?php

namespace App\Services\Email;

use App\Models\Email\BlackListEmail;
use App\Traits\FileUploadTrait;
use Illuminate\Http\UploadedFile;

class BlackListEmailService
{
    use FileUploadTrait;

    public function saveBlackListEmail(array $data)
    {
        if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
            $uploadPath = config('disposable-email.blacklist_file');

            $data['file'] = $this->uploadFile(
                $data['file'],
                $uploadPath,
            );
        }

        BlackListEmail::create([
            'file' => basename($data['file']),
            'user_id' => auth()->user()->id,
        ]);
    }
}

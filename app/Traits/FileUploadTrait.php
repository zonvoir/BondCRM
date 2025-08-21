<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    /**
     * Upload a file with optional old file removal
     *
     * @param  UploadedFile|null  $file  The file to upload (null for optional uploads)
     * @param  string  $path  Full path where to store the file (storage_path() or public_path())
     * @param  string|null  $oldFilePath  Path of old file to remove (optional)
     * @param  string|null  $customFilename  Custom filename (optional)
     * @return string|null The filename of the uploaded file or null if no file
     */
    public function uploadFile(?UploadedFile $file, string $path, ?string $oldFilePath = null, ?string $customFilename = null): ?string
    {
        // If no file is uploaded, return null (making upload optional)
        if (! $file) {
            return null;
        }

        // Create directory if it doesn't exist
        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Remove old file if exists
        if (! empty($oldFilePath)) {
            $this->removeFile($path.'/'.$oldFilePath);
        }

        // Generate filename
        $filename = $customFilename ?? Str::uuid().'.'.$file->getClientOriginalExtension();

        // Store the file
        $file->move($path, $filename);

        return $filename;
    }

    /**
     * Remove a file
     *
     * @param  string|null  $fullFilePath  Full path of file to remove
     */
    public function removeFile(?string $fullFilePath): bool
    {
        if (! $fullFilePath) {
            return false;
        }

        if (File::exists($fullFilePath)) {
            return File::delete($fullFilePath);
        }

        return false;
    }

    /**
     * Get full URL for a file
     *
     * @param  string  $path  Directory path
     * @param  string|null  $filename  Filename
     * @param  bool  $isStorage  Whether path is in storage
     */
    public function getFileUrl(string $path, ?string $filename, bool $isStorage = false): ?string
    {
        if (! $filename) {
            return null;
        }

        if ($isStorage) {
            // Convert storage path to relative path for Storage URL
            $relativePath = str_replace(storage_path('app/public'), '', $path);

            return Storage::url(mb_trim($relativePath, '/').'/'.$filename);
        }
        // Convert public path to relative path for asset URL
        $relativePath = str_replace(public_path(), '', $path);

        return asset(mb_trim($relativePath, '/').'/'.$filename);

    }
}

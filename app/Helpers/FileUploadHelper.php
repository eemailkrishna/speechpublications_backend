<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileUploadHelper
{
    /**
     * Upload file to given folder (auto-create folder if not exists)
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string $disk (s3 | public | local)
     * @return array
     */
    public static function upload(
        UploadedFile $file,
        string $folder,
        string $disk = 's3'
    ): array {
        // clean folder name
        $folder = trim($folder, '/');

        // unique file name
        $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

        // ensure folder exists (Laravel auto-creates, but keeping explicit)
        if (!Storage::disk($disk)->exists($folder)) {
            Storage::disk($disk)->makeDirectory($folder);
        }

        // upload file
        $path = Storage::disk($disk)->putFileAs(
            $folder,
            $file,
            $fileName
        );

        return [
            'path' => $path,
            'url'  => Storage::disk($disk)->url($path),
            'name' => $fileName
        ];
    }
}

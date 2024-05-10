<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait Document
{
    /**
     * upload document file
     *
     * @param string $file
     * @param string $directory
     * @return void
     */
    public function uploadDocument($file, $directory)
    {
        $directory = '/' . $directory . '/';
        $fileExtension = '.' . $file->getClientOriginalExtension();
        $fileName = Str::random(32) . $fileExtension;
        Storage::disk('s3')->putFileAs($directory, $file, $fileName);
        return $directory . $fileName;
    }

    /**
     * get file path
     *
     * @param string $path
     * @return void
     */
    public function getDocument($path, $status = false)
    {
        if ($status) {
            return (is_null($path)) ? asset('img/avatar.jpeg') : Storage::disk('s3')->url($path);
        } else {
            return (is_null($path)) ? null : Storage::disk('s3')->url($path);
        }
    }
}

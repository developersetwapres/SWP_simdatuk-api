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
            return (is_null($path)) ? asset('img/profile.jpg') : Storage::disk('s3')->url($path);
        } else {
            return (is_null($path)) ? null : Storage::disk('s3')->url($path);
        }
    }

    /**
     * get status document available or not
     *
     * @param string $path
     * @param boolean $status
     * @return void
     */
    public function getDocumentExist($path)
    {
        if (Storage::disk('s3')->exists($path)) {
            return $path;
        } else {
            return null;
        }
    }
}

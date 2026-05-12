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
    public function uploadDocument($file, $directory, $filename = null)
    {
        $directory = '/' . $directory . '/';
        $fileExtension = '.' . $file->getClientOriginalExtension();
        $filename = (is_null($filename)) ? Str::random(32) . $fileExtension : $filename . $fileExtension;
        Storage::disk('s3')->putFileAs($directory, $file, $filename);
        return $directory . $filename;
    }

    /**
     * get file path
     *
     * @param string $path
     * @return void
     */
    public function getDocumentX($path, $status = false, $export = false) //nonaktifkan oleh KMZ, karena pakai enpoint di bawah
    {
        if ($status) {
            if ($export ==  true) {
                return (is_null($path)) ? asset('img/profile.jpg') : env('AWS_URL_BY_IP') . '/' . $path;
            } else {
                return (is_null($path)) ? asset('img/profile.jpg') : Storage::disk('s3')->get($path);
            }
        } else {
            return (is_null($path)) ? null : Storage::disk('s3')->url($path);
        }
    }

    public function getDocument($path = null, $status = false, $export = false)
    {
        if (is_null($path)) {
            return asset('img/profile.jpg');
        }

        // arahkan ke endpoint BE sendiri
        return url('/api/image/' . ltrim($path, '/'));
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

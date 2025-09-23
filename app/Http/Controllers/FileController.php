<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function file(Request $request)
    {
        $path = $request->get('path');

        $file = Storage::disk('s3')->get('apps-simdatuk/' . $path);

        return response($file);
    }
}

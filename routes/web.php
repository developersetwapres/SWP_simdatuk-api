<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // dd(Hash::make('Password9!k'));
    // Storage::disk('s3')->put('test.txt', 'Hello MinIO');

    // dd(Storage::disk('s3')->url('test.txt'));
    return view('welcome');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\petaInteraktif;

Route::get('/', function () {
    return view('index'); 
})->name('beranda');

Route::get('/peta-interaktif', [petaInteraktif::class, 'petaInteraktif'])->name('peta');

Route::get('/berita-artikel', function () {
    return view('artikel'); 
})->name('artikel');

Route::get('/kontak-kami', function () {
    return view('kontak'); 
})->name('kontak');


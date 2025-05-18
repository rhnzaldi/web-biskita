<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index'); 
})->name('beranda');

Route::get('/peta-interaktif', function () {
    return view('peta'); 
})->name('peta');

Route::get('/berita-artikel', function () {
    return view('artikel'); 
})->name('artikel');

Route::get('/kontak-kami', function () {
    return view('kontak'); 
})->name('kontak');


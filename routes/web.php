<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});
Route::get('/coba', function () {
    return view('coba');
});

Route::get('/produk', function () {
    return view('staff_ppic.produk');
});

Route::get('/detail', function () {
    return view('staff_ppic.detail-rencana');
});

Route::get('/riwayat', function () {
    return view('staff_ppic.riwayat');
});


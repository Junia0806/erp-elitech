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
    return view('home');
});
Route::get('/coba', function () {
    return view('staff_produksi.app');
});

//Staff PPIC
Route::get('/produk', function () {
    return view('staff_ppic.produk');
});

Route::get('/detail', function () {
    return view('staff_ppic.detail-rencana');
});

Route::get('/riwayat', function () {
    return view('staff_ppic.riwayat');
});

////Manajer
Route::get('/riwayat', function () {
    return view('staff_ppic.riwayat');
});

Route::get('/verifikasi', function () {
    return view('manajer.verifikasi');
});

Route::get('/riwayat-manajer', function () {
    return view('manajer.riwayat');
});

//Staff Produksi
Route::get('/produksi', function () {
    return view('staff_produksi.produksi');
});
Route::get('/laporan', function () {
    return view('staff_produksi.laporan');
});
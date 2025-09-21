<?php

use Illuminate\Support\Facades\Route;

// Staff PPIC
use App\Http\Controllers\Staff_ppic\ChooseItemController;
use App\Http\Controllers\Staff_ppic\CheckoutController;
use App\Http\Controllers\Staff_ppic\HistoryController;

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

// ==================================================

Route::prefix('ppic')->name('ppic.')->group(function () {
    Route::resource('choose-item', ChooseItemController::class); // Pick-up Feature
    /*
        Controller
        - INDEX : Menampilkan data barang
        - STORE : Menyimpan data pilihan menggunakan session
    */

    Route::resource('checkout', CheckoutController::class);  // Checkout Feature
    /*
        Controller
        - INDEX : Menampilkan data barang yang dipilih (dengan session)
        - STORE : Menyimpan dan membuat data production_plan
    */

    Route::resource('history', HistoryController::class);  // History Feature
    /*
        Controller
        - INDEX : Menampilkan data history
    */
});

Route::prefix('produksi')->name('produksi.')->group(function () {
    // PLAN : Prefix between Manager and Staff
        // Route::resource('nama_routes', "nama_controller"); // Manager Feature
        // Route::resource('nama_routes', "nama_controller"); // Order List Feature
        // Route::resource('nama_routes', "nama_controller"); // History Feature
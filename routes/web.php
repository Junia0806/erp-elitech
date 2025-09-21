<?php

use Illuminate\Support\Facades\Route;

// Staff PPIC
use App\Http\Controllers\Staff_ppic\ChooseItemController;
use App\Http\Controllers\Staff_ppic\CheckoutController;
use App\Http\Controllers\Staff_ppic\HistoryController;
use App\Http\Controllers\Manager_produksi\VerificationController;
use App\Http\Controllers\Manager_produksi\ManagerHistoryController;
use App\Http\Controllers\Staff_produksi\StaffHistoryController;
use App\Http\Controllers\Staff_produksi\ProductionTaskController;

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

//Staff PPIC (DONE)
Route::get('/produk', function () {
    return view('staff_ppic.produk');
});

Route::get('/detail', function () {
    return view('staff_ppic.detail-rencana');
});

Route::get('/riwayat', function () {
    return view('staff_ppic.riwayat');
});

////Manajer (DONE)
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
    /*
        Controller : ChooseItemController::class
        - INDEX : Menampilkan data barang
        - STORE : Menyimpan data pilihan menggunakan session
    */
    Route::resource('choose-item', ChooseItemController::class); // Pick-up Feature

    /*
        Controller: CheckoutController::class
        - INDEX : Menampilkan data barang yang dipilih (dengan session)
        - STORE : Menyimpan dan membuat data production_plan
    */
    Route::resource('checkout', CheckoutController::class);  // Checkout Feature

    /*
        Controller: HistoryController::class
        - INDEX : Menampilkan data history
    */
    Route::resource('history', HistoryController::class);  // History Feature
});

Route::prefix('produksi')->name('produksi.')->group(function () {
    // PREFIX: Manager Production Platform
    Route::prefix('manager')->name('manager.')->group(function () {
        /*
            Controller: VerificationController::class
            - INDEX : Menampilkan data yang harus diverifikasi.
            - DECIDE: Membuat Work Order ketika di Approve, Menampilkan komentar dari Manager Produksi.
        */
        Route::resource('verification', VerificationController::class); // Manager Feature
        Route::post('verification/{plan}/decide', [VerificationController::class, 'decide'])->name('verification.decide');

        /*
            Controller: ManagerHistoryController::class
            - INDEX : Menampilkan data history.
        */
        Route::resource('history', ManagerHistoryController::class); // Order List Feature
    });

    // PREFIX: Staff Production Platform
    Route::prefix('staff')->name('staff.')->group(function () {
        /*
            Controller: VerificationController::class
            - INDEX : Menampilkan data yang harus diverifikasi.
            - DECIDE: Membuat Work Order ketika di Approve, Menampilkan komentar dari Manager Produksi.
        */
        Route::get('reports', [StaffHistoryController::class, 'index'])->name('report.index');
        Route::post('reports/generate', [ProductionReportController::class, 'generate'])->name('report.generate');
    });
});
<?php

use App\Http\Controllers as Controller;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/penjualan/print', [Controller\PenjualanController::class, 'print']);

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::post('/login', [Controller\UserController::class, 'login']);
    Route::resource('/user', Controller\UserController::class);
    Route::resource('/barang', Controller\BarangController::class);
    Route::resource('/pelanggan', Controller\PelangganController::class);
    Route::resource('/jabatan', Controller\JabatanController::class);
    Route::resource('/kategori', Controller\KategoriController::class);
    Route::resource('/penjualan/item', Controller\PenjualanItemController::class);
    Route::get('/penjualan/laporan', [Controller\PenjualanController::class, 'laporan']);
    Route::resource('/penjualan', Controller\PenjualanController::class);
});
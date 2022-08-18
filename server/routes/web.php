<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/invoice/{id}', function ($id) {
    $penjualan = \App\Models\Penjualan::find($id);
    $items = \App\Models\PenjualanItem::where('penjualan_id', $id)->get();

    return view('invoice', compact('penjualan', 'items'));
});
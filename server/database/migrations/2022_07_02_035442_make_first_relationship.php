<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->foreign('pelanggan_id', 'penjualan_pelanggan_id')->on('pelanggan')->references('id')->onDelete('no action');
        });

        Schema::table('penjualan_item', function (Blueprint $table) {
            $table->foreign('penjualan_id', 'penjualan_item_penjualan_id')->on('penjualan')->references('id')->onDelete('cascade');
            $table->foreign('barang_id', 'penjualan_item_barang_id')->on('barang')->references('id')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropForeign('penjualan_pelanggan_id');
        });

        Schema::table('penjualan_item', function (Blueprint $table) {
            $table->dropForeign('penjualan_item_penjualan_id');
            $table->dropForeign('penjualan_item_barang_id');
        });
    }
};

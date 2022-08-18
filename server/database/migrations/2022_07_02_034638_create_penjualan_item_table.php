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
        Schema::create('penjualan_item', function (Blueprint $table) {
            $table->id();
            $table->string('penjualan_id');
            $table->bigInteger('barang_id')->unsigned();
            $table->integer('harga_beli');
            $table->integer('harga_jual_awal');
            $table->double('diskon')->default(0);
            $table->integer('jumlah');
            $table->integer('harga_jual_akhir');
            $table->integer('sub_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan_item');
    }
};

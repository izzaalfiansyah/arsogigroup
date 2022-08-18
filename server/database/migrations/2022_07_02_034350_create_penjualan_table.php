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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->bigInteger('pelanggan_id')->unsigned();
            $table->string('nama_sales');
            $table->string('nama_delivery')->nullable();
            $table->enum('status', ['1', '2', '3'])->comment('1: purchase-order, 2: take-order, 3: penjualan');
            $table->string('status_penjualan');
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
        Schema::dropIfExists('penjualan');
    }
};

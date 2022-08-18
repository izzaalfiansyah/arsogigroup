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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('nama_pemilik');
            $table->text('alamat');
            $table->text('keterangan_alamat')->nullable();
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('hp1');
            $table->string('hp2')->nullable();
            $table->bigInteger('sales_id')->unsigned();
            $table->string('foto_toko')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->double('diskon_produk')->default('0');
            $table->integer('total_pinjaman_botol')->nullable();
            $table->integer('total_pinjaman_krat')->nullable();
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
        Schema::dropIfExists('pelanggan');
    }
};

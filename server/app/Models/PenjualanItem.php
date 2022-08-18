<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanItem extends Model
{
    use HasFactory;

    public $table = 'penjualan_item';

    public $fillable = [
        'penjualan_id',
        'barang_id',
        'harga_beli',
        'harga_jual_awal',
        'harga_jual_akhir',
        'diskon',
        'jumlah',
        'sub_total',
    ];

    public $with = [
        'barang',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public static function rules()
    {
        return [
            'penjualan_id' => 'required|integer',
            'barang_id' => 'required|integer',
            'harga_beli' => 'required|integer',
            'harga_jual_awal' => 'required|integer',
            'harga_jual_akhir' => 'required|integer',
            'diskon' => 'nullable',
            'jumlah' => 'required|integer',
            'sub_total' => 'required|integer',
        ];
    }
}

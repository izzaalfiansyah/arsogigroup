<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    use HasFactory;

    public $table = 'penjualan';
    public $incrementing = false;
    public $keyType = 'string';

    public $fillable = [
        'id',
        'pelanggan_id',
        'nama_sales',
        'nama_delivery',
        'status',
        'status_awal',
        'status_penjualan',
    ];

    public $appends = [
        'print_url',
    ];

    public function getPrintUrlAttribute()
    {
        return url('invoice/' . $this->id);
    }

    public $with = [
        'pelanggan',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public static function booted()
    {
        static::addGlobalScope('total_penjualan', function (Builder $builder) {
            $builder->select('penjualan.*', DB::raw("(select cast(ifnull(sum(sub_total), 0) as unsigned) from penjualan_item where penjualan_id = penjualan.id) as total_penjualan"));
        });
    }

    public static function rules()
    {
        return [
            'pelanggan_id' => 'required|integer',
            'nama_sales' => 'required',
            'nama_delivery' => 'nullable',
            'status' => 'nullable|in:1,2,3',
            'status_awal' => 'nullable|in:1,2,3',
            'status_penjualan' => 'required',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    public $table = 'pelanggan';

    public $fillable = [
        'nama_toko',
        'nama_pemilik',
        'alamat',
        'keterangan_alamat',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'hp1',
        'hp2',
        'sales_id',
        'foto_toko',
        'latitude',
        'longitude',
        'diskon_produk',
        'total_pinjaman_botol',
        'total_pinjaman_krat',
    ];

    public $casts = [
        'kelurahan' => 'object',
        'kecamatan' => 'object',
        'kabupaten' => 'object',
        'provinsi' => 'object',
        'diskon_produk' => 'int',
    ];

    public $appends = [
        'foto_toko_url',
    ];

    public function getLatitudeAttribute($val)
    {
        return $val ?: '';
    }

    public function getLongitudeAttribute($val)
    {
        return $val ?: '';
    }

    public function getFotoTokoUrlAttribute()
    {
        return asset($this::fotoToko . ($this->foto_toko ?: 'default.jpg'));
    }

    public $with = [
        'sales',
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    const fotoToko = 'pelanggan/toko/';

    public static function rules()
    {
        return [
            'nama_toko' => 'required',
            'nama_pemilik' => 'required',
            'alamat' => 'required',
            'keterangan_alamat' => 'nullable',
            'kelurahan' => 'required',
            'kecamatan' => 'required',
            'kabupaten' => 'required',
            'provinsi' => 'required',
            'hp1' => 'required',
            'hp2' => 'nullable',
            'sales_id' => 'required|integer',
            'foto_toko' => ['nullable', function ($attr, $val, $fail) {
                if (!str_contains($val, ';base64,')) {
                    $fail("The $attr extension is invalid.");
                }
            }],
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'diskon_produk' => 'nullable',
            'total_pinjaman_botol' => 'nullable|integer',
            'total_pinjaman_krat' => 'nullable|integer',
        ];
    }
}

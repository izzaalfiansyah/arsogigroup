<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    public $table = 'barang';

    public $fillable = [
        'nama',
        'kategori',
        'stok',
        'harga_jual',
        'harga_beli',
        'foto',
    ];

    public $appends = [
        'harga_jual_detail',
        'harga_beli_detail',
        'foto_url',
    ];

    public function getHargaJualDetailAttribute()
    {
        $harga = 'Rp.' . number_format($this->harga_jual, 0, ',', '.');
        return $harga;
    }

    public function getHargaBeliDetailAttribute()
    {
        $harga = 'Rp.' . number_format($this->harga_beli, 0, ',', '.');
        return $harga;
    }

    public function getFotoUrlAttribute()
    {
        return asset($this::foto . ($this->foto ?: 'default.png'));
    }

    const foto = 'barang/';

    public static function rules()
    {
        return [
            'nama' => 'required',
            'kategori' => 'required',
            'stok' => 'required|integer',
            'harga_jual' => 'required|integer',
            'harga_beli' => 'required|integer',
            'foto' => ['nullable', function ($attr, $val, $fail) {
                if (!str_contains($val, ';base64,')) {
                    $fail("The $attr extension is invalid.");
                }
            }],
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class User extends Model
{
    use HasFactory;

    public $table = 'user';

    public $fillable = [
        'username',
        'password',
        'nama',
        'alamat_ktp',
        'alamat_domisili',
        'hp1',
        'hp2',
        'jabatan',
        'foto_ktp',
    ];

    public $appends = [
        'foto_ktp_url',
    ];

    public function getFotoKtpUrlAttribute()
    {
        return asset($this::fotoKtp . $this->foto_ktp);
    }

    const fotoKtp = 'user/ktp/';

    public static function rules($id = null)
    {
        return [
            'username' => ['required', $id ? Rule::unique('user')->ignore($id) : Rule::unique('user')],
            'password' => [$id ? 'nullable' : 'required', Password::min(8)->letters()->numbers()],
            'nama' => 'required',
            'alamat_ktp' => 'nullable',
            'alamat_domisili' => 'nullable',
            'hp1' => 'required',
            'hp2' => 'nullable',
            'jabatan' => 'required',
            'foto_ktp' => [$id ? 'nullable' : 'required', function ($attr, $val, $fail) {
                if (!str_contains($val, ';base64,')) {
                    $fail("The $attr extension is invalid.");
                }
            }],
        ];
    }
}

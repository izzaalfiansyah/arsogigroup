<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function randomString($length = 20): string
    {
        $random = random_bytes(64);
        $code = bin2hex($random);
        $string = substr($code, 0, $length);

        return $string;
    }

    public function base64Upload($base64, $path, $ext = '.txt'): string
    {
        $data = explode(';base64,', $base64)[1];
        $name = $this->randomString() . $ext;

        file_put_contents($path . $name, base64_decode($data));
        return $name;
    }
}

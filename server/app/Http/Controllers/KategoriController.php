<?php

namespace App\Http\Controllers;

use App\Models\Kategori as Model;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $data = Model::all();

        return response($data);
    }
}

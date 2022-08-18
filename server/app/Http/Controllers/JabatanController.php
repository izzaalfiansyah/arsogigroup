<?php

namespace App\Http\Controllers;

use App\Models\Jabatan as Model;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $data = Model::all();

        return response($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        @mkdir(public_path('user'));
        @mkdir(public_path(Model::fotoKtp));

        $this->fotoKtpPath = public_path(Model::fotoKtp);
    }

    public function index(Request $req)
    {
        $builder = new Model();

        if ($jabatan = $req->jabatan) {
            $builder = $builder->where('jabatan', $jabatan);
        }

        if ($search = $req->q) {
            $builder = $builder->where(function ($query) use ($search) {
                $query->where('username', 'LIKE', "%$search%")
                ->orWhere('nama', 'LIKE', "%$search%")
                ->orWhere('jabatan', 'LIKE', "%$search%")
                ->orWhere('hp1', 'LIKE', "%$search%")
                ->orWhere('hp2', 'LIKE', "%$search%");
            });
        }

        $recordsTotal = $builder->count();

        if ($req->_limit) {
            $limit = $req->_limit ?: 20;
            $page = $req->_page ?: 1;
    
            $builder = $builder->limit($limit)->skip(($page - 1) * $limit);
        }

        $data = $builder->orderBy('nama', 'ASC')->get();

        return response($data)->header('X-Total-Count', $recordsTotal);
    }

    public function show($id)
    {
        $item = Model::find($id);

        return response($item);
    }

    public function store(Request $req)
    {
        $schema = Validator::make($req->all(), Model::rules());

        if ($schema->fails()) {
            return response($schema->errors()->all(), 400);
        }

        $data = $schema->validated();

        $data['password'] = Hash::make($req->password);
        
        if ($req->foto_ktp) {
            $data['foto_ktp'] = $this->base64Upload($req->foto_ktp, $this->fotoKtpPath, '.png');
        }

        $item = Model::create($data);

        return response($item);
    }

    public function update(Request $req, $id)
    {
        $item = Model::find($id);
        $schema = Validator::make($req->all(), Model::rules($item->id));

        if ($schema->fails()) {
            return response($schema->errors()->all(), 400);
        }

        $data = $schema->validated();
        
        if ($req->password) {
            $data['password'] = Hash::make($req->password);
        } else {
            unset($data['password']);
        }

        if ($req->foto_ktp) {
            $data['foto_ktp'] = $this->base64Upload($req->foto_ktp, $this->fotoKtpPath, '.png');
            @unlink($this->fotoKtpPath . $this->foto_ktp);
        } else {
            unset($data['foto_ktp']);
        }

        $item->update($data);

        return response($item);
    }

    public function destroy($id)
    {
        $item = Model::find($id);

        @unlink($this->fotoKtpPath . $this->foto_ktp);

        $item->delete();
        
        return response($item);
    }

    public function login(Request $req)
    {
        $schema = Validator::make($req->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($schema->fails()) {
            return response($schema->errors()->all(), 400);
        }

        $user = Model::where('username', $req->username)->get();

        if ($user) {
            foreach ($user as $key => $item) {
                if (Hash::check($req->password, $item->password)) {
                    return response($item);
                }
            }
            return response(['password salah'], 400);
        } else {
            return response(['username tidak ditemukan'], 400);
        }
    }
}

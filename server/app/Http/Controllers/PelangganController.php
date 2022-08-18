<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function __construct()
    {
        @mkdir(public_path('pelanggan'));
        @mkdir(public_path(Model::fotoToko));

        $this->fotoTokoPath = public_path(Model::fotoToko);
    }

    public function index(Request $req)
    {
        $builder = new Model();

        if ($sales_id = $req->sales_id) {
            $builder = $builder->where('sales_id', $sales_id);
        }

        if ($search = $req->q) {
            $builder = $builder->where(function ($query) use ($search) {
                $query->where('nama_toko', 'LIKE', "%$search%")
                ->orWhere('nama_pemilik', 'LIKE', "%$search%")
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

        $data = $builder->orderBy('nama_pemilik', 'asc')->get();

        return response($data, headers: [
            'X-Total-Count' => $recordsTotal,
        ]);
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
        
        if ($req->foto_toko) {
            $data['foto_toko'] = $this->base64Upload($req->foto_toko, $this->fotoTokoPath, '.jpg');
        }

        $item = Model::create($data);

        return response($item);
    }

    public function update(Request $req, $id)
    {
        $item = Model::find($id);
        $schema = Validator::make($req->all(), Model::rules());

        if ($schema->fails()) {
            return response($schema->errors()->all(), 400);
        }

        $data = $schema->validated();
        
        if ($req->foto_toko) {
            $data['foto_toko'] = $this->base64Upload($req->foto_toko, $this->fotoTokoPath, '.jpg');
            @unlink($this->fotoTokoPath . $this->foto_toko);
        } else {
            unset($data['foto_toko']);
        }

        $item->update($data);

        return response($item);
    }

    public function destroy($id)
    {
        $item = Model::find($id);

        @unlink($this->fotoTokoPath . $this->foto_toko);

        $item->delete();
        
        return response($item);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Barang as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function __construct()
    {
        @mkdir(public_path('barang'));
        @mkdir(public_path(Model::foto));

        $this->fotoPath = public_path(Model::foto);
    }

    public function index(Request $req)
    {
        $builder = new Model();

        if ($search = $req->q) {
            $builder = $builder->where(function ($query) use ($search) {
                $query->where('nama', 'LIKE', "%$search%")
                ->orWhere('kategori', 'LIKE', "%$search%")
                ->orWhere('harga_jual', 'LIKE', "%$search%");
            });
        }

        $recordsTotal = $builder->count();

        if ($req->_limit) {
            $limit = $req->_limit ?: 20;
            $page = $req->_page ?: 1;
    
            $builder = $builder->limit($limit)->skip(($page - 1) * $limit);
        }

        $data = $builder->get();

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
        
        if ($req->foto) {
            $data['foto'] = $this->base64Upload($req->foto, $this->fotoPath, '.png');
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
        
        if ($req->foto) {
            $data['foto'] = $this->base64Upload($req->foto, $this->fotoPath, '.png');
            @unlink($this->fotoPath . $this->foto);
        } else {
            unset($data['foto']);
        }

        $item->update($data);

        return response($item);
    }

    public function destroy($id)
    {
        $item = Model::find($id);

        @unlink($this->fotoPath . $this->foto);

        $item->delete();
        
        return response($item);
    }
}

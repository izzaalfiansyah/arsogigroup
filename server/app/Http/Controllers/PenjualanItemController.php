<?php

namespace App\Http\Controllers;

use App\Models\PenjualanItem as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenjualanItemController extends Controller
{

    public function index(Request $req)
    {
        $builder = new Model();

        $builder = $builder->select('penjualan_item.*');
        $builder = $builder->leftJoin('barang', 'barang.id', '=', 'penjualan_item.barang_id');

        if ($penjualan_id = $req->penjualan_id) {
            $builder = $builder->where('penjualan_id', $penjualan_id);
        }

        if ($search = $req->q) {
            $builder = $builder->where(function ($query) use ($search) {
                $query->where('barang.nama', 'LIKE', "%$search%")
                ->orWhere('barang.harga_beli', 'LIKE', "%$search%")
                ->orWhere('harga_jual_awal', 'LIKE', "%$search%")
                ->orWhere('diskon', 'LIKE', "%$search%")
                ->orWhere('harga_jual_akhir', 'LIKE', "%$search%")
                ->orWhere('jumlah', 'LIKE', "%$search%")
                ->orWhere('sub_total', 'LIKE', "%$search%");
            });
        }

        $recordsTotal = $builder->count();

        $limit = $req->_limit ?: 20;
        $page = $req->_page ?: 1;

        $builder = $builder->limit($limit)->skip(($page - 1) * $limit);

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

        $item->update($data);

        return response($item);
    }

    public function destroy($id)
    {
        $item = Model::find($id);

        $item->delete();
        
        return response($item);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Penjualan as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{

    public function index(Request $req)
    {
        $builder = new Model();

        $builder = $builder->select('penjualan.*');
        $builder = $builder->leftJoin('pelanggan', 'pelanggan.id', '=', 'penjualan.pelanggan_id');

        if ($pelanggan_id = $req->pelanggan_id) {
            $builder = $builder->where('penjualan.pelanggan_id', $pelanggan_id);
        }

        if ($status_awal = $req->status_awal) {
            $builder = $builder->where('penjualan.status_awal', $status_awal);
            
            if ($status_filter = $req->status_filter) {
                if ($status_filter == '-1') {
                    $builder = $builder->where('penjualan.status', $status_awal);
                } else if ($status_filter == '1') {
                    $status = $req->status ?: '3';
                    $builder = $builder->where('penjualan.status', $status);
                }
            }
        } else if ($status = $req->status) {
            $builder = $builder->where('penjualan.status', $status);
        }

        if ($tanggal = $req->tanggal) {
            $builder = $builder->whereDate('penjualan.created_at', $tanggal);
        }

        if ($search = $req->q) {
            $builder = $builder->where(function ($query) use ($search) {
                $query->where('penjualan.id', 'LIKE', "%$search%")
                ->orWhere('pelanggan.nama_pemilik', 'LIKE', "%$search%")
                ->orWhere('pelanggan.nama_toko', 'LIKE', "%$search%")
                ->orWhere('penjualan.nama_sales', 'LIKE', "%$search%")
                ->orWhere('penjualan.nama_delivery', 'LIKE', "%$search%")
                ->orWhere('penjualan.status_penjualan', 'LIKE', "%$search%")
                ->orWhere('penjualan.total_penjualan', 'LIKE', "%$search%");
            });
        }

        $recordsTotal = $builder->count();

        $limit = $req->_limit ?: 20;
        $page = $req->_page ?: 1;

        $builder = $builder->limit($limit)->skip(($page - 1) * $limit);

        $data = $builder->orderBy('penjualan.id', 'desc')->get();

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
        $data['id'] = date('YmdHis');

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

    public function laporan(Request $req)
    {
        $data = [];

        if ($tanggal = $req->_tanggal) {
            $builder = DB::table('penjualan');
            $builder = $builder->where(function ($query) {
                $query->where('status_awal', '2')->orWhere('status_awal', '3');
            });
            $builder = $builder->whereDate('created_at', $tanggal);

            $data = $builder->get();

            foreach ($data as $key => $item) {
                $data[$key]->pelanggan = DB::table('pelanggan')->where('id', $item->pelanggan_id)->first();
                $data[$key]->item = DB::table('penjualan_item')->where('penjualan_id', $item->id)->get();
                $data[$key]->subtotal = 0;
                foreach ($data[$key]->item as $itemPenjualan) {
                    $data[$key]->subtotal = $itemPenjualan->sub_total;
                }
            }
        }
        
        return response($data)
            ->header('X-Print-Url', url('/api/penjualan/print?' . http_build_query($req->all())))
            ->header('X-Excel-Url', url('/api/penjualan/excel?' . http_build_query($req->all())));
    }

    public function print(Request $req)
    {
        $data = $this->laporan($req)->original;
        $produk = DB::table('barang')->get();
        
        return view('penjualan', compact('data', 'produk'));
    }
}

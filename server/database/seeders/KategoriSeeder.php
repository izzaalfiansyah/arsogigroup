<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori = [
            'Coffe Bear',
            'Temulawak',
        ];

        for ($i=0; $i < count($kategori); $i++) { 
            \App\Models\Kategori::create([
                'nama' => $kategori[$i],
            ]);
        }
    }
}

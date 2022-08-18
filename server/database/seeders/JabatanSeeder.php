<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jabatan = [
            'Delivery',
            'Manager',
            'Sales',
            'Sales Supervisor',
        ];

        for ($i=0; $i < count($jabatan); $i++) { 
            \App\Models\Jabatan::create([
                'nama' => $jabatan[$i],
            ]);
        }
    }
}

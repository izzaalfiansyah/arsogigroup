<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        
        for ($i=0; $i < 100; $i++) { 
            \App\Models\Pelanggan::create([
                'nama_toko' => $faker->company(),
                'nama_pemilik' => $faker->name(),
                'alamat' => $faker->address(),
                'kelurahan' => '{"id":"3509020008","district_id":"3509020","name":"KARANGREJO"}',
                'kecamatan' => '{"id":"3509020","regency_id":"3509","name":"GUMUK MAS"}',
                'kabupaten' => '{"id":"3509","province_id":"35","name":"KABUPATEN JEMBER"}',
                'provinsi' => '{"id":"35","name":"JAWA TIMUR"}',
                'hp1' => $faker->phoneNumber(),
                'sales_id' => '2',
                'latitude' => '-8.2969924741894',
                'longitude' => '113.4146976455',
            ]);
        }
    }
}

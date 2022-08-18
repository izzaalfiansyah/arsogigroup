<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'username' => 'superadmin',
            'password' => Hash::make('superadmin'),
            'nama' => 'Muhammad Izza Alfiansyah',
            'alamat_ktp' => 'Dusun Karanganyar RT 2 RW 8',
            'hp1' => '081231921351',
            'jabatan' => 'Manager',
            'foto_ktp' => '8978ac74a1034da2a06d.png'
        ]);

        $faker = \Faker\Factory::create('id_ID');

        for ($i=0; $i < 5; $i++) { 
            \App\Models\User::create([
                'username' => strtolower($faker->firstName()) . $faker->randomNumber(4, true),
                'password' => Hash::make('12345678'),
                'nama' => $faker->name(),
                'alamat_ktp' => $faker->address(),
                'hp1' => $faker->phoneNumber(),
                'jabatan' => /*['Delivery', 'Sales', 'Sales Supervisor'][random_int(0, 2)]*/ 'Sales',
            ]);
        }
    }
}

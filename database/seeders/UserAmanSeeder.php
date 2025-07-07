<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UserAman;

class UserAmanSeeder extends Seeder
{
    public function run(): void
    {
        UserAman::create([
            'name' => 'Admin Satu',
            'username' => 'admin',
            'password' => Hash::make('password123'),
        ]);
        
        UserAman::create([
            'name' => 'Kasir Toko',
            'username' => 'kasir',
            'password' => Hash::make('kasir123'),
        ]);

        UserAman::create([
            'name' => 'Gudang',
            'username' => 'gudang',
            'password' => Hash::make('gudang123'),
        ]);
    }
}

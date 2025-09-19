<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            // Akun Manager
            [
                'name' => 'Manager Produksi',
                'email' => 'manager@elitech.com',
                'password' => Hash::make('password'),
                'role' => 'Manager Produksi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Akun Staff PPIC
            [
                'name' => 'Staff PPIC',
                'email' => 'ppic@elitech.com',
                'password' => Hash::make('password'),
                'role' => 'Staff PPIC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Akun Staff Produksi
            [
                'name' => 'Staff Produksi',
                'email' => 'produksi@elitech.com',
                'password' => Hash::make('password'),
                'role' => 'Staff Produksi',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
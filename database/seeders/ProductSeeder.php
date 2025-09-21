<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengosongkan tabel sebelum diisi
        DB::table('products')->delete();

        DB::table('products')->insert([
            [
                'name' => 'CNC Vertical Machining Center',
                'sku' => 'VMC-850B',
                'description' => 'Mesin frais CNC vertikal untuk pengerjaan logam presisi.',
                'created_at' => now(),
                'updated_at' => now(),
                'image'      => null,
            ],
            [
                'name' => 'CNC Lathe Machine',
                'sku' => 'TC-20',
                'description' => 'Mesin bubut CNC untuk memproduksi komponen silindris.',
                'created_at' => now(),
                'updated_at' => now(),
                'image'      => null,
            ],
            [
                'name' => 'Manual Lathe Machine',
                'sku' => 'CY-S1740',
                'description' => 'Mesin bubut manual konvensional serbaguna.',
                'created_at' => now(),
                'updated_at' => now(),
                'image'      => null,
            ],
            [
                'name' => 'Hydraulic Press Brake',
                'sku' => 'HPB-40/2200',
                'description' => 'Mesin tekuk plat hidrolik untuk membentuk lembaran logam.',
                'created_at' => now(),
                'updated_at' => now(),
                'image'      => null,
            ]
        ]);
    }
}
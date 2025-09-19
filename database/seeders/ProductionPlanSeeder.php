<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;

class ProductionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user dan produk yang relevan
        $ppicStaff = User::where('email', 'ppic@elitech.com')->first();
        $manager = User::where('email', 'manager@elitech.com')->first();
        $product1 = Product::where('sku', 'VMC-850B')->first();
        $product2 = Product::where('sku', 'TC-20')->first();
        
        // Pastikan tabel kosong sebelum seeding
        DB::table('production_plans')->delete();

        DB::table('production_plans')->insert([
            // Rencana 1: Sudah disetujui oleh manajer -> akan jadi order
            [
                'product_id' => $product1->id,
                'created_by' => $ppicStaff->id,
                'quantity' => 50,
                'status' => 'disetujui',
                'deadline' => now()->addDays(7),
                'approved_by' => $manager->id,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Rencana 2: Masih menunggu persetujuan
            [
                'product_id' => $product2->id,
                'created_by' => $ppicStaff->id,
                'quantity' => 100,
                'status' => 'menunggu_persetujuan',
                'deadline' => null,
                'approved_by' => null,
                'approved_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
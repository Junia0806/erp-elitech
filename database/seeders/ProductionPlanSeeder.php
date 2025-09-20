<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductionPlan;

class ProductionPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data master yang dibutuhkan
        $ppicStaff = User::where('email', 'ppic@elitech.com')->first();
        $manager = User::where('email', 'manager@elitech.com')->first();
        $product1 = Product::where('sku', 'VMC-850B')->first();
        $product2 = Product::where('sku', 'TC-20')->first();
        $product3 = Product::where('sku', 'CY-S1740')->first();

        // Kosongkan tabel untuk menghindari duplikasi saat seeding ulang
        ProductionPlan::query()->delete();

        // Rencana 1: Dibuat dan langsung disetujui
        $approvedPlan = ProductionPlan::create([
            'created_by' => $ppicStaff->id,
            'status' => 'disetujui',
            'deadline' => now()->addDays(7),
            'approved_by' => $manager->id,
            'approved_at' => now(),
        ]);

        // Tempelkan 2 produk ke dalam rencana yang disetujui
        $approvedPlan->products()->attach($product1->id, ['quantity' => 50]);
        $approvedPlan->products()->attach($product2->id, ['quantity' => 75]);


        // Rencana 2: Dibuat dan masih menunggu persetujuan
        $pendingPlan = ProductionPlan::create([
            'created_by' => $ppicStaff->id,
            'status' => 'menunggu_persetujuan',
        ]);

        // Tempelkan 1 produk ke dalam rencana yang masih pending
        $pendingPlan->products()->attach($product3->id, ['quantity' => 100]);
    }
}
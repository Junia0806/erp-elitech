<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionPlan;
use App\Models\ProductionOrder;

class ProductionOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari production plan yang statusnya 'disetujui'
        $approvedPlan = ProductionPlan::where('status', 'disetujui')->first();
        
        // Kosongkan tabel untuk menghindari data duplikat
        ProductionOrder::query()->delete();

        // Buat order hanya jika ada plan yang disetujui
        if ($approvedPlan) {
            ProductionOrder::create([
                'production_plan_id' => $approvedPlan->id,
                'status' => 'menunggu', // Status awal untuk order baru
            ]);
        }
    }
}
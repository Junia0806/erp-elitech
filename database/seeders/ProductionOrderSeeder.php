<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionPlan;

class ProductionOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari production plan yang sudah disetujui
        $approvedPlan = ProductionPlan::where('status', 'disetujui')->first();
        
        // Pastikan tabel kosong sebelum seeding
        DB::table('production_orders')->delete();

        // Buat order hanya jika ada plan yang disetujui
        if ($approvedPlan) {
            DB::table('production_orders')->insert([
                [
                    'production_plan_id' => $approvedPlan->id,
                    'status' => 'menunggu', // Status awal order
                    'quantity_actual' => null,
                    'quantity_reject' => null,
                    'completed_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
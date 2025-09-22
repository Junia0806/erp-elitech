<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionOrder;
use App\Models\User;

class ProductionLogSeeder extends Seeder
{
    /**
         * Run the database seeds.
         */
    public function run(): void
    {
        $productionOrder = ProductionOrder::first();
        $productionStaff = User::where('email', 'produksi@elitech.com')->first();
        
        // Pastikan tabel kosong sebelum seeding
        DB::table('production_logs')->delete();

        if ($productionOrder) {
            DB::table('production_logs')->insert([
                [
                    'production_order_id' => $productionOrder->id,
                    'user_id' => $productionStaff->id,
                    'description' => 'Order dibuat',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
<?php

namespace App\Http\Controllers\Staff_ppic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionPlan;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Buat query dasar untuk mengambil SEMUA ProductionPlan
        // Eager load relasi untuk optimasi:
        // - 'items.product': Mengambil item dalam rencana, dan detail produk untuk setiap item.
        // - 'productionOrder': Mengambil data order yang terkait.
                $query = ProductionPlan::with(['products', 'productionOrder', 'creator'])
                               ->latest(); // Urutkan dari yang terbaru

        // 2. Eksekusi query untuk mendapatkan keseluruhan data
        $plans = $query->get();

        // 3. Proses setiap plan untuk menambahkan kalkulasi progress
        $plans->each(function ($plan) {
            $plan->progress = $this->calculateProductionProgress($plan);
        });

        // 4. Kirim data yang sudah diproses ke view
        return response()->json($plans, 200, [], JSON_PRETTY_PRINT);
    }

    private function calculateProductionProgress(ProductionPlan $plan): int
    {
        // Cek status dari production_orders terlebih dahulu, karena itu tahap paling akhir
        if ($plan->productionOrder) {
            switch ($plan->productionOrder->status) {
                case 'selesai':
                    return 100;
                case 'dikerjakan':
                    return 75;
            }
        }

        // Jika belum ada di production_orders, cek status dari production_plans
        switch ($plan->status) {
            case 'disetujui':
                return 50;
            case 'menunggu persetujuan':
                return 25;
            case 'dibuat':
                return 10;
            case 'ditolak':
                return 0;
            default:
                return 0;
        }
    }
}

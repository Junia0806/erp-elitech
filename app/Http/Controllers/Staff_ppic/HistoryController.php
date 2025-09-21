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
        // 1. Buat query dasar
        $query = ProductionPlan::with(['products', 'productionOrder', 'creator'])
                               ->latest(); // Urutkan dari yang terbaru

        // 2. Eksekusi query
        $plans = $query->get();

        // 3. Proses setiap plan untuk menambahkan kalkulasi progress
        $plans->each(function ($plan) {
            $plan->progress = $this->calculateProductionProgress($plan);
        });

        // 4. (MODIFIKASI) Kirim data ke view Blade, bukan JSON.
        // Ganti 'staff_ppic.history' dengan path view Anda jika berbeda.
        return view('staff_ppic.riwayat', compact('plans'));
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
            case 'menunggu_persetujuan': // Sesuaikan dengan data Anda
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

<?php

namespace App\Http\Controllers\Manager_produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionPlan;
use Carbon\Carbon;

class ManagerHistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat rencana produksi yang sudah diverifikasi.
     */
    public function index(Request $request)
    {
        // 1. Ambil filter status dari URL (?status=disetujui atau ?status=ditolak)
        $filterStatus = $request->input('status');

        // 2. Buat query dasar untuk mengambil ProductionPlan yang sudah diproses
        //    Eager load semua relasi yang dibutuhkan untuk menghindari N+1 query.
        $query = ProductionPlan::with(['products', 'creator', 'productionOrder'])
                               ->whereIn('status', ['disetujui', 'ditolak']) // Hanya ambil yang sudah diproses
                               ->latest(); // Urutkan dari yang terbaru

        // 3. Terapkan filter jika ada
        if ($filterStatus && in_array($filterStatus, ['disetujui', 'ditolak'])) {
            $query->where('status', $filterStatus);
        }

        // 4. Eksekusi query
        $plans = $query->get();

        // 5. Proses setiap plan untuk menambahkan data kalkulasi yang diperlukan di view
        $plans->each(function ($plan) {
            // Kalkulasi total jenis produk
            $plan->total_product_types = $plan->products->count();

            // Kalkulasi total kuantitas produk
            $plan->total_quantity = $plan->products->sum('pivot.quantity');

            // Kalkulasi estimasi tanggal selesai (Tanggal disetujui + deadline hari)
            if ($plan->approved_at && $plan->deadline) {
                $plan->estimated_completion = Carbon::parse($plan->approved_at)
                                                    ->addDays($plan->deadline)
                                                    ->locale('id')
                                                    ->isoFormat('D MMMM YYYY');
            } else {
                $plan->estimated_completion = '-';
            }

            // Kalkulasi progress bar
            $plan->progress = $this->calculateProductionProgress($plan);
        });

        // Back-end Mode: Keluarkan bentuk json
        // return response()->json($plans, 200, [], JSON_PRETTY_PRINT);

        // 6. Kirim data yang sudah diproses ke view
        return view('manajer.riwayat', [
            'plans' => $plans,
            'filterStatus' => $filterStatus // Kirim status filter untuk menandai tombol aktif di view
        ]);
    }

    /**
     * Menghitung persentase progres produksi berdasarkan status.
     *
     * @param ProductionPlan $plan
     * @return int
     */
    private function calculateProductionProgress(ProductionPlan $plan): int
    {
        if ($plan->productionOrder) {
            switch ($plan->productionOrder->status) {
                case 'selesai':
                    return 100;
                case 'dikerjakan':
                    return 75;
            }
        }

        switch ($plan->status) {
            case 'disetujui':
                return 50;
            case 'ditolak':
                return 0; // Progress 0 jika ditolak
            default:
                return 25; // Status lain sebelum disetujui
        }
    }
}

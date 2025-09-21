<?php

namespace App\Http\Controllers\Manager_produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionPlan;
use App\Models\ProductionOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ambil semua rencana produksi yang statusnya 'dibuat'
        //    dan belum diproses (disetujui/ditolak).
        $plans = ProductionPlan::with(['products', 'creator'])
            ->where('status', 'dibuat')
            ->orWhere('status', 'menunggu_persetujuan')
            ->latest()
            ->get();

        $plans->each(function ($plan) {
            // Menghitung total jenis produk (misal: Kemeja, Kaos -> 2 jenis)
            $plan->total_product_types = $plan->products->count();

            // Menghitung total kuantitas (misal: 100 pcs + 250 pcs -> 350 pcs)
            // Mengambil 'quantity' dari tabel pivot
            $plan->total_quantity = $plan->products->sum('pivot.quantity');
            $plan->date     = $plan->formatted_created_at;
        });

        // Back-end Mode: Keluarkan bentuk json
        // return response()->json($plans, 200, [], JSON_PRETTY_PRINT);

        // 3. Kirim data KOLEKSI (bukan JSON string) langsung ke view.
        //    Ini adalah praktik terbaik di Laravel.
        return view('manajer.verifikasi', compact('plans'));
    }

    /**
     * Memproses keputusan (Setuju/Tolak) dari manajer untuk sebuah rencana produksi.
     *
     * @param Request $request
     * @param ProductionPlan $plan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function decide(Request $request, ProductionPlan $plan)
    {
        // Gunakan transaksi untuk memastikan semua operasi berhasil atau tidak sama sekali
        try {
            DB::transaction(function () use ($request, $plan) {
                if ($request->input('decision') == 'approve') {
                    // --- JIKA DISETUJUI ---
                    $plan->status = 'disetujui';
                    $plan->approved_by = 1; // auth()->id();ID Manajer yang login
                    $plan->approved_at = now();
                    $plan->save();

                    // Buat entri baru di 'production_orders'
                    ProductionOrder::create([
                        'production_plan_id' => $plan->id,
                        'status' => 'menunggu', // Status awal untuk order
                        'notes' => $request->input('notes'),
                    ]);

                } else {
                    // --- JIKA DITOLAK ---
                    $plan->status = 'ditolak';
                    $plan->notes  = $request->input('notes') ?: '-'; // Simpan alasan penolakan
                    $plan->approved_by = auth()->id();
                    $plan->approved_at = now();
                    $plan->save();
                }
            });
        } catch (\Exception $e) {
            Log::error('Gagal memverifikasi rencana produksi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan. Gagal memproses keputusan.');
        }
        
        $decisionMessage = $request->input('decision') === 'approve' ? 'disetujui' : 'ditolak';

        // Redirect kembali ke halaman verifikasi dengan pesan sukses
        return redirect()->route('produksi.manager.history.index')
                         ->with('success', "Rencana produksi #{$plan->id} berhasil {$decisionMessage}.");
    }
}

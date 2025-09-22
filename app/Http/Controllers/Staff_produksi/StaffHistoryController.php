<?php

namespace App\Http\Controllers\Staff_produksi;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffHistoryController extends Controller
{
    /**
     * Menampilkan halaman laporan produksi.
     */
    public function index()
    {
        // 1. Ambil semua order yang relevan, termasuk yang belum selesai untuk notifikasi
        // dan yang sudah selesai untuk data laporan.
        $allOrders = ProductionOrder::with([
                'productionPlan.products', 
                'productionPlan.creator', // Relasi ke user PPIC
                'productionPlan.approver', // Relasi ke user Manajer
                'items'
            ])
            ->get();

        // 2. Transformasi data menjadi array bersih yang dibutuhkan oleh frontend
        $formattedOrders = $allOrders->map(function ($order) {
            
            $itemsLookup = $order->items->keyBy('product_id');

            $estimasiSelesai = 'N/A';
            if ($order->productionPlan->approved_at && is_numeric($order->productionPlan->deadline)) {
                $estimasiSelesai = Carbon::parse($order->productionPlan->approved_at)
                                          ->addDays((int)$order->productionPlan->deadline)
                                          ->isoFormat('DD MMM YYYY');
            }

            return [
                'id' => 'RP' . str_pad($order->production_plan_id, 3, '0', STR_PAD_LEFT),
                'estimasi_selesai' => $estimasiSelesai,
                'tanggal_selesai' => $order->completed_at ? Carbon::parse($order->completed_at)->toDateString() : null, // Format Y-m-d
                'status_produksi' => ucfirst(str_replace('_', ' ', $order->status)),
                'ppic_staff' => $order->productionPlan->creator->name ?? 'N/A',
                'manajer' => $order->productionPlan->approver->name ?? 'N/A',
                'info_ppic' => $order->productionPlan->ppic_note,
                'catatan_manajer' => $order->productionPlan->prod_note,
                'products' => $order->productionPlan->products->map(function($product) use ($itemsLookup) {
                    $item = $itemsLookup->get($product->id);
                    return [
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'target' => $product->pivot->quantity,
                        'hasil_produksi' => $item ? $item->quantity_actual : 0,
                        'reject_produksi' => $item ? $item->quantity_reject : 0,
                    ];
                }),
            ];
        });

        // 3. Kirim data yang sudah diformat ke view
        return view('staff_produksi.laporan', ['orders' => $formattedOrders]);
    }
}

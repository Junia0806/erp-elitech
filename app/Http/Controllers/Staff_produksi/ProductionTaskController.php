<?php

// Koreksi 1: Gunakan backslash (\) untuk namespace
namespace App\Http\Controllers\Staff_produksi;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use App\Models\ProductionLog; 
use App\Models\ProductionItem; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;   
use Carbon\Carbon;

class ProductionTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = ProductionOrder::with('productionPlan.products', 'logs.user', 'items')
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedTasks = $tasks->map(function ($task) {
            
            $itemsLookup = $task->items->keyBy('product_id');

            $estimasiSelesai = 'N/A';
            if ($task->productionPlan->approved_at && is_numeric($task->productionPlan->deadline)) {
                $estimasiSelesai = Carbon::parse($task->productionPlan->approved_at)
                                          ->addDays((int)$task->productionPlan->deadline)
                                          ->isoFormat('DD MMM YYYY');
            }

            // [MODIFIKASI] Memastikan history tidak pernah kosong
            $history = $task->logs->map(function($log) {
                preg_match("/menjadi '([^']*)'/", $log->description, $matches);
                // Menggunakan status order saat ini sebagai fallback jika log tidak mengandung status
                $statusName = $matches[1] ?? ucfirst(str_replace('_', ' ', $log->productionOrder->status));

                return [
                    'status' => $statusName,
                    'timestamp' => $log->created_at->format('d M Y, H:i')
                ];
            });
            // Jika tidak ada log sama sekali, buat history awal
            if ($history->isEmpty()) {
                $history->push([
                    'status' => ucfirst(str_replace('_', ' ', $task->status)),
                    'timestamp' => $task->created_at->format('d M Y, H:i')
                ]);
            }

            return [
                'id' => $task->id,
                'display_id' => 'RP' . str_pad($task->production_plan_id, 3, '0', STR_PAD_LEFT),
                'estimasi_selesai' => $estimasiSelesai,
                'status_produksi' => ucfirst(str_replace('_', ' ', $task->status)),
                'info_ppic' => $task->productionPlan->ppic_note,
                'catatan_manajer' => $task->productionPlan->prod_note,
                'history' => $history,
                'products' => $task->productionPlan->products->map(function($product) use ($itemsLookup) {
                    $item = $itemsLookup->get($product->id);
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'target' => $product->pivot->quantity,
                        'hasil_produksi' => $item ? $item->quantity_actual : 0,
                        'reject_produksi' => $item ? $item->quantity_reject : 0,
                    ];
                }),
            ];
        });

        return view('staff_produksi.produksi', ['tasks' => $formattedTasks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ProductionOrder $task)
    {
        DB::transaction(function () use ($request, $task) {
            $totalActual = 0;
            $totalReject = 0;

            // Ambil daftar produk yang direncanakan untuk mendapatkan targetnya
            $plannedProducts = $task->productionPlan->products;

                foreach ($request->products as $productData) {
                    $productId = $productData['id'];
                    $plannedProduct = $plannedProducts->get($productId);

                    if (!$plannedProduct) continue; 

                    $actual = $productData['hasil_produksi'];
                    $reject = $productData['reject_produksi'];

                    // Gunakan model ProductionItem secara langsung untuk updateOrCreate
                    // karena relasi items() dari ProductionOrder adalah hasManyThrough (read-only)
                    ProductionItem::updateOrCreate(
                        [
                            'production_plan_id' => $task->production_plan_id,
                            'product_id'         => $productId,
                        ],
                        [
                            // Sesuaikan nama kolom dengan tabel Anda ('quantity' bukan 'quantity_target')
                            'quantity'          => $plannedProduct->pivot->quantity,
                            'quantity_actual'   => $actual,
                            'quantity_reject'   => $reject,
                        ]
                    );

                    $totalActual += $actual;
                    $totalReject += $reject;
                }

            // Update total di tabel production_orders (tetap sama)
            $task->update([
                'quantity_actual' => $totalActual,
                'quantity_reject' => $totalReject,
            ]);

            // Buat log (tetap sama)
            ProductionLog::create([
                'production_order_id' => $task->id,
                'user_id' => Auth::id(),
                'description' => "Laporan aktual disimpan. Total Berhasil: {$totalActual}, Total Reject: {$totalReject}.",
            ]);
        });

        // 4. Redirect: Arahkan kembali pengguna ke halaman daftar tugas dengan pesan sukses!
        return response()->json(['message' => 'Laporan #' . $task->id .' berhasil ditambahkan!']);
        // return redirect()->route('produksi.staff.tasks.index')->with('success', 'Laporan #' . $task->id . ' berhasil disimpan.');
    }

    /**
     * Update the specified resource in storage.
     */
    // Koreksi 4: Gunakan Route Model Binding (ProductionOrder $task)
    public function update(Request $request, ProductionOrder $task)
    {
        $oldStatus = $task->status;
        $newStatusFromRequest = $request->status;

        // [INI SOLUSINYA] Terjemahkan status dari format frontend ke format database
        // Contoh: "Dikerjakan" -> "dikerjakan"
        $newStatusForDb = strtolower($newStatusFromRequest);

        DB::transaction(function () use ($task, $oldStatus, $newStatusForDb, $newStatusFromRequest) {
            // 2. Update status di ProductionOrder menggunakan format database
            $task->status = $newStatusForDb;
            
            // 3. Gunakan format frontend untuk perbandingan dan logging
            if ($newStatusFromRequest == 'Selesai') {
                $task->completed_at = now();
            }
            $task->save();

            // 4. Buat log perubahan menggunakan format yang mudah dibaca
            ProductionLog::create([
                'production_order_id' => $task->id,
                'user_id' => Auth::id(),
                'description' => "Status diubah dari '{$oldStatus}' menjadi '{$newStatusFromRequest}'.",
            ]);
        });

        return response()->json(['message' => 'Status #' . $task->id .' berhasil diperbarui!']);
    }
}
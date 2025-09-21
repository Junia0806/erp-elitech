<?php

// Koreksi 1: Gunakan backslash (\) untuk namespace
namespace App\Http\Controllers\Staff_produksi;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use App\Models\ProductionLog; // Koreksi 2: Tambahkan model ProductionLog
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Koreksi 2: Tambahkan facade Auth
use Illuminate\Support\Facades\DB;   // Koreksi 2: Tambahkan facade DB

class ProductionTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = ProductionOrder::with('productionPlan.products', 'logs.user')
            ->orderBy('created_at', 'asc')
            ->get();

        // [LANGKAH UTAMA] Transformasi data menjadi array yang bersih untuk frontend
        $formattedTasks = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'status' => $task->status,
                'production_plan' => [
                    'id' => $task->productionPlan->id,
                    'deadline' => $task->productionPlan->deadline,
                    'products' => $task->productionPlan->products->map(fn($p) => [
                        'id' => $p->id,
                        'name' => $p->name,
                        'pivot' => [
                            'quantity' => $p->pivot->quantity,
                            'quantity_actual' => $p->pivot->quantity_actual,
                            'quantity_reject' => $p->pivot->quantity_reject,
                        ]
                    ]),
                ],
                'logs' => $task->logs->map(fn($log) => [
                    'description' => $log->description,
                    'created_at' => $log->created_at->format('d M Y, H:i'),
                    'user' => $log->user->name ?? 'Sistem',
                ]),
            ];
        });

        // Kirim data yang sudah diformat ke view
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

            // KOREKSI UTAMA: Loop dan buat data di `production_items`
            foreach ($request->products as $productId => $quantities) {
                $plannedProduct = $plannedProducts->find($productId);
                if (!$plannedProduct) continue; // Lewati jika produk tidak ada dalam rencana

                $actual = $quantities['berhasil'] ?? 0;
                $reject = $quantities['reject'] ?? 0;

                // Buat baris baru di tabel production_items
                $task->items()->create([
                    'product_id' => $productId,
                    'quantity_target' => $plannedProduct->pivot->quantity, // Ambil target dari pivot plan
                    'quantity_actual' => $actual,
                    'quantity_reject' => $reject,
                ]);

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
                'user_id' => 3, // Auth::id(),
                'description' => "Laporan aktual disimpan. Total Berhasil: {$totalActual}, Total Reject: {$totalReject}.",
            ]);
        });

        // 4. Redirect: Arahkan kembali pengguna ke halaman daftar tugas dengan pesan sukses!
        return redirect()->route('task.index')->with('success', 'Laporan #' . $task->id . ' berhasil disimpan.');
    }

    /**
     * Update the specified resource in storage.
     */
    // Koreksi 4: Gunakan Route Model Binding (ProductionOrder $task)
    public function update(Request $request, ProductionOrder $task)
    {
        $oldStatus = $task->status;
        $newStatus = $request->status;

            DB::transaction(function () use ($task, $oldStatus, $newStatus) {
                // 1. Update status di ProductionOrder
                $task->status = $newStatus;
                if ($newStatus === 'selesai') {
                    $task->completed_at = now();
                }
                $task->save();

                // 2. Buat log perubahan
                ProductionLog::create([
                    'production_order_id' => $task->id,
                    'user_id' => 3, //Auth::id(),
                    'description' => "Status diubah dari '{$oldStatus}' menjadi '{$newStatus}'.",
                ]);
            }); // Koreksi 5: Tambahkan penutup transaksi

        // Koreksi 6: Method harus mengembalikan response Status #RP006 berhasil diperbarui!
        return redirect()->back()->with('success', 'Status #' . $task->id .' berhasil diperbarui!');
    }
}
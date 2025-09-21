<?php

namespace App\Http\Controllers\Staff_ppic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductionPlan;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Keluarkan data dari session
        // Ambil data dari session. Jika tidak ada, kembalikan array kosong.
        $selectedItems = session()->get('checkout_items', []);

        // Hapus session setelah diambil agar tidak muncul lagi nanti (opsional)
        session()->forget('checkout_items');

        // Keluarkan data barang yang dipilih dari $selectedItems (Cari barangnya)
        // Data yang diperlukan : Nama Barang, SKU dan id_barang
        $products = Product::whereIn('id', $selectedItems)
                            ->select('id', 'name', 'sku')
                            ->get();
        
        // Back-end Mode: Keluarkan bentuk json
        // return response()->json($product, 200, [], JSON_PRETTY_PRINT);

        // Keluarkan data bersamaan dengan viewnya
        return view('staff_ppic.detail-rencana', compact('products'));
    }

    public function store(Request $request)
    {
        // Menggunakan transaksi database untuk memastikan integritas data.
        // Jika salah satu query gagal, semua perubahan akan dibatalkan (rollback).
        try {
            DB::transaction(function () use ($request) {
                // 1. Buat entri utama di tabel 'production_plans'
                $plan = ProductionPlan::create([
                    'status'    => 'Dibuat', // Status awal sesuai ENUM di database Anda
                    'deadline' => $request->input('deadline'),
                    'notes'     => $request->input('notes') ?? '-',
                    'created_by' => 1, // $auth()->id Mengambil ID user yang sedang login
                ]);

                // 2. Siapkan data untuk method attach()
                // Strukturnya harus: [product_id => ['kolom_pivot' => nilai]]
                $itemsToAttach = [];
                foreach ($request->input('products') as $product) {
                    $itemsToAttach[$product['id']] = ['quantity' => $product['quantity']];
                }

                // 3. Gunakan relasi 'products()' dari model Anda dan method 'attach()'
                // Ini akan menyimpan data ke tabel pivot 'production_item'
                if (!empty($itemsToAttach)) {
                    $plan->products()->attach($itemsToAttach);
                }

                // Jika ada langkah lain yang perlu dilakukan, letakkan di sini. (Nanti dicek lagi)
                // Contoh: membuat entri di production_orders atau production_logs
            });
        } catch (\Exception $e) {
            // Jika terjadi error selama transaksi, catat error dan kembalikan pengguna.
            Log::error('Gagal menyimpan rencana produksi: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                ->withInput(); // Mengembalikan input agar form tidak kosong
        }


        // 4. Jika transaksi berhasil, redirect ke halaman history dengan pesan sukses
        return redirect()->route('ppic.history.index')->with('success', 'Rencana produksi baru berhasil dibuat!');
    }
}

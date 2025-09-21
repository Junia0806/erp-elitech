<?php

namespace App\Http\Controllers\Staff_ppic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ChooseItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil seluruh data barang (Data hanya ditarik : Foto, nama, sku dan id_barang)
        $product    = Product::all()
                    ->select('id', 'name', 'sku', 'image');

        // Back-end Mode: Keluarkan bentuk json
        return response()->json($product, 200, [], JSON_PRETTY_PRINT);

        // Keluarkan data bersamaan dengan viewnya
        // return view('view.name', compact('data'));
    }

    public function store(Request $request) // Penyimpanan Barang menggunakan session
    {
        $selectedItems = $request->input('products'); // Ambil data dari form POST - Bagian checklist"nya

        // Simpan data ke session dengan nama 'checkout_items'
        session()->put('checkout_items', $selectedItems);

        // Arahkan ke halaman checkout
        return redirect()->route('ppic.checkout.index');
    }
}

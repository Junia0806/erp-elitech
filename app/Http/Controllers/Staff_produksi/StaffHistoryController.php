<?php

namespace App\Http\Controllers\Staff_produksi;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;

class StaffHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua order yang statusnya 'selesai'
        $completedOrders = ProductionOrder::where('status', 'selesai')
            ->latest('updated_at')
            ->get();

        // Back-end Mode: Keluarkan bentuk json
        return response()->json($completedOrders, 200, [], JSON_PRETTY_PRINT);

        // Mengirim data ke view seperti biasa
        // return view('production.report.index', compact('completedOrders'));
    }
}

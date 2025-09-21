<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Produksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- AlpineJS for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Header Utama -->
    <header class="bg-slate-800 text-white shadow-md">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <span class="font-bold text-xl">Manajer Produksi</span>
                </div>
                <div class="flex items-center gap-4 text-sm font-medium">
                    <a href="#" class="bg-slate-700 px-3 py-2 rounded-md">Verifikasi</a>
                    <a href="#" class="hover:bg-slate-700 px-3 py-2 rounded-md">Riwayat</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="container mx-auto max-w-7xl py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Verifikasi Produksi</h1>
                <p class="text-gray-600">Tinjau dan berikan persetujuan untuk rencana produksi yang diajukan.</p>
            </div>
            <div class="bg-white px-4 py-2 rounded-lg shadow-sm border text-sm">
                <span class="font-medium text-gray-700">Minggu, 21 September 2025</span>
            </div>
        </div>

        <!-- Kartu Rencana Produksi (Statis) -->
        <div x-data="{ open: true }" class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden mb-6">
            <!-- Header Kartu -->
            <div @click="open = !open" class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50">
                <div>
                    <h2 class="text-lg font-bold text-blue-600">#RP001</h2>
                    <p class="text-sm text-gray-500">Oleh: Andi Wijaya</p>
                </div>
                <div class="flex items-center gap-6 text-center text-sm">
                    <div>
                        <p class="font-semibold text-gray-800">20 Sep 2025</p>
                        <p class="text-gray-500">Tanggal</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">2</p>
                        <p class="text-gray-500">Produk</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">350</p>
                        <p class="text-gray-500">Pcs</p>
                    </div>
                    <div class="bg-orange-100 text-orange-700 font-semibold px-3 py-1 rounded-full">
                        14 Hari
                    </div>
                </div>
                <svg :class="{'rotate-180': open}" class="w-5 h-5 text-gray-500 transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>                  
            </div>

            <!-- Konten Detail (Expandable) -->
            <div x-show="open" x-transition class="p-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Kolom Kiri: Rincian -->
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-3">Rincian Produk:</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                                    <span>Kemeja Lengan Panjang <br><span class="text-xs text-gray-500">SKU: KMLP-001</span></span>
                                    <span class="font-semibold">100 Pcs</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                                    <span>Kaos Polos Cotton 30s <br><span class="text-xs text-gray-500">SKU: KPC-003</span></span>
                                    <span class="font-semibold">250 Pcs</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-3 mt-3 border-t font-bold">
                                <span>Total Target Produksi</span>
                                <span>350 Pcs</span>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-700 mb-3">Catatan dari PPIC:</h3>
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 text-sm text-blue-800 rounded-r-lg">
                                Prioritas tinggi untuk klien A. Mohon segera diproses untuk menjaga hubungan baik.
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Formulir -->
                    <div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 h-full">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Formulir Keputusan</h3>
                            
                            {{-- Form ini akan mengirim data ke method 'decide' dengan ID = 1 --}}
                            <form action="{{ route('produksi.manager.verification.decide', ['plan' => 1]) }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-900">Alasan Penolakan <span class="text-gray-500">(Wajib jika menolak)</span></label>
                                    <textarea id="notes" name="notes" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Stok bahan baku tidak mencukupi..."></textarea>
                                </div>

                                <div class="flex flex-col gap-3 mt-6">
                                    <!-- Tombol SETUJUI -->
                                    <button type="submit" name="decision" value="approve" class="w-full text-white bg-green-600 hover:bg-green-700 rounded-lg py-2.5 font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Setujui
                                    </button>
                                    <!-- Tombol TOLAK -->
                                    <button type="submit" name="decision" value="reject" class="w-full text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg py-2.5 font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        Tolak
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 text-center mt-3">Keputusan ini final dan akan tercatat di sistem.</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

</body>
</html>

@extends('staff_ppic.app')

@section('title', 'Pilihan Produk')

@section('content')

    {{-- Kustomisasi scrollbar bisa disimpan atau dihapus sesuai selera --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>

    <div x-data="{
        products: [
            { id: 1, sku: 'KMLP-001', name: 'Kemeja Lengan Panjang', image: 'https://placehold.co/400x400/0ea5e9/FFFFFF?text=Kemeja' },
            { id: 2, sku: 'CCSF-002', name: 'Celana Chino Slim Fit', image: 'https://placehold.co/400x400/10b981/FFFFFF?text=Celana' },
            { id: 3, sku: 'KPC-003', name: 'Kaos Polos Cotton 30s', image: 'https://placehold.co/400x400/f59e0b/FFFFFF?text=Kaos' },
            { id: 4, sku: 'JBP-004', name: 'Jaket Bomber Parasut', image: 'https://placehold.co/400x400/ef4444/FFFFFF?text=Jaket' },
            { id: 5, sku: 'TBB-005', name: 'Topi Baseball Bordir', image: 'https://placehold.co/400x400/6366f1/FFFFFF?text=Topi' },
            { id: 6, sku: 'SSK-006', name: 'Sepatu Sneaker Kanvas', image: 'https://placehold.co/400x400/8b5cf6/FFFFFF?text=Sepatu' },
            { id: 7, sku: 'TRL-007', name: 'Tas Ransel Laptop', image: 'https://placehold.co/400x400/d946ef/FFFFFF?text=Tas' },
            { id: 8, sku: 'GMW-008', name: 'Gamis Modern Wanita', image: 'https://placehold.co/400x400/ec4899/FFFFFF?text=Gamis' }
        ],
        selectedProducts: [],
        isSelected(productId) { return this.selectedProducts.some(p => p.id === productId); },
        toggleProduct(product) {
            if (this.isSelected(product.id)) {
                this.selectedProducts = this.selectedProducts.filter(p => p.id !== product.id);
            } else {
                this.selectedProducts.push(product);
            }
        }
    }">

        <header class="mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header Baru yang Lebih Ringkas dan Jelas --}}
            <div class="py-2 border-b border-slate-200">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Rencana Produksi Baru
                </h1>
                <p class="mt-1 text-base text-slate-500">
                    Pilih produk untuk dimasukkan ke dalam antrean produksi.
                </p>
            </div>
        </header>

        <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">



                <div class="lg:col-span-2">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <template x-for="product in products" :key="product.id">
                            <div @click="toggleProduct(product)"
                                :class="isSelected(product.id) ? 'border-sky-500 shadow-sky-200' :
                                    'border-transparent shadow-slate-100'"
                                class="relative bg-white rounded-xl border-2 cursor-pointer transition-all duration-300 hover:shadow-xl hover:-translate-y-1 shadow-md">

                                <div x-show="isSelected(product.id)" x-transition
                                    class="absolute top-3 right-3 bg-sky-500 text-white rounded-full h-6 w-6 flex items-center justify-center z-10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>

                                <div class="flex flex-col h-full">
                                    <img :src="product.image" :alt="product.name"
                                        class="w-full h-48 object-cover rounded-t-lg">
                                    <div class="p-4 flex flex-col flex-grow">
                                        <p class="text-xs text-slate-500" x-text="product.sku"></p>
                                        <h3 class="font-semibold text-slate-900 mt-1" x-text="product.name"></h3>
                                        <div class="mt-auto pt-4">
                                            <div :class="isSelected(product.id) ? 'bg-sky-100 text-sky-700' :
                                                'bg-slate-100 text-slate-600'"
                                                class="w-full text-center font-semibold py-2 px-4 rounded-md transition duration-300 text-sm">
                                                <span x-text="isSelected(product.id) ? 'Terpilih' : 'Pilih Produk'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="lg:col-span-1 lg:sticky top-8">
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b pb-3">Ringkasan Pilihan</h2>

                        <div x-show="selectedProducts.length > 0" x-cloak x-transition>
                            <div class="space-y-3 mb-4 max-h-80 overflow-y-auto pr-2">
                                <template x-for="product in selectedProducts" :key="product.id">
                                    <div class="flex justify-between items-center bg-slate-50 p-2 rounded-lg">
                                        <span class="text-sm font-medium text-slate-700" x-text="product.name"></span>
                                        <button @click="toggleProduct(product)"
                                            class="text-red-500 hover:text-red-700 font-bold ml-2 text-lg">&times;</button>
                                    </div>
                                </template>
                            </div>
                            <hr class="my-4">
                            <div class="flex justify-between items-center font-semibold text-slate-800">
                                <span>Total Produk:</span>
                                <span x-text="selectedProducts.length"></span>
                            </div>
                            <a href="/detail"
                                class="mt-6 block w-full text-center bg-sky-600 text-white font-bold py-3 px-5 rounded-lg 
          hover:bg-sky-700 transition duration-300 shadow-sm">
                                Lanjutkan
                            </a>

                        </div>

                        <div x-show="selectedProducts.length === 0" x-cloak class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900">Belum ada produk dipilih</h3>
                            <p class="mt-1 text-sm text-slate-500">Pilih produk dari daftar di sebelah kiri.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

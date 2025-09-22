@extends('staff_ppic.app')

@section('title', 'Pilihan Produk Untuk Produksi')

@section('content')
    <style>
        /* Menyembunyikan elemen saat Alpine.js sedang inisialisasi */
        [x-cloak] { display: none !important; }

        /* Kustomisasi scrollbar untuk area ringkasan */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>

    {{-- Inisialisasi data Alpine.js dari controller --}}
    <div x-data="productPicker({{ json_encode($products) }})">

        <header class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-2 border-b border-slate-200">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Rencana Produksi Baru
                </h1>
                <p class="mt-1 text-base text-slate-500">
                    Pilih satu atau lebih produk untuk dimasukkan ke dalam antrean produksi.
                </p>
            </div>
        </header>

        <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- KOLOM KIRI: DAFTAR SEMUA PRODUK --}}
                <div class="lg:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($products as $product)
                            <div @click="toggleProduct({{ $product['id'] }})"
                                :class="isSelected({{ $product['id'] }}) ? 'border-sky-500 ring-2 ring-sky-300' : 'border-slate-200'"
                                class="relative bg-white rounded-xl border cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                                
                                {{-- Ikon centang saat produk dipilih --}}
                                <div x-show="isSelected({{ $product['id'] }})" x-cloak x-transition
                                    class="absolute top-2 right-2 bg-sky-500 text-white rounded-full h-6 w-6 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                
                                @if (!empty($product['image']))
                                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-40 object-cover rounded-t-lg">
                                @else
                                    <div class="w-full h-40 flex items-center justify-center bg-slate-100 rounded-t-lg">
                                        <span class="text-slate-500 text-center px-2 font-semibold">{{ $product['name'] }}</span>
                                    </div>
                                @endif

                                <div class="p-4">
                                    <p class="text-xs text-slate-500">{{ $product['sku'] ?? 'No SKU' }}</p>
                                    <h3 class="font-semibold text-slate-800 mt-1 truncate" title="{{ $product['name'] }}">{{ $product['name'] }}</h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- KOLOM KANAN: RINGKASAN PILIHAN --}}
                <div class="lg:col-span-1 lg:sticky top-8">
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b pb-3">Ringkasan Pilihan</h2>

                        {{-- Tampilan saat ada produk yang dipilih --}}
                        <div x-show="selectedProducts.length > 0" x-cloak x-transition>
                            <div class="space-y-3 mb-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                                <template x-for="product in selectedProducts" :key="product.id">
                                    <div class="flex justify-between items-center bg-slate-50 p-2 rounded-lg">
                                        <div class="flex items-center space-x-3 overflow-hidden">
                                            <img :src="product.image" :alt="product.name" class="w-10 h-10 object-cover rounded-md flex-shrink-0">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800 truncate" x-text="product.name"></p>
                                                <p class="text-xs text-slate-500" x-text="product.sku"></p>
                                            </div>
                                        </div>
                                        <button @click="toggleProduct(product.id)" class="text-red-500 hover:text-red-700 font-bold ml-2 text-xl transition-colors flex-shrink-0">&times;</button>
                                    </div>
                                </template>
                            </div>
                            <hr class="my-4">
                            <div class="flex justify-between items-center font-semibold text-slate-800">
                                <span>Total Produk:</span>
                                <span class="text-lg font-bold" x-text="selectedProducts.length"></span>
                            </div>

                            <form method="POST" action="#" class="mt-6">
                                @csrf
                                <template x-for="item in selectedProducts" :key="item.id">
                                    <input type="hidden" name="products[]" :value="item.id">
                                </template>
                                <button type="submit"
                                    :disabled="selectedProducts.length === 0"
                                    class="block w-full text-center bg-sky-600 text-white font-bold py-3 px-5 rounded-lg 
                                    hover:bg-sky-700 transition duration-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500
                                    disabled:bg-slate-300 disabled:cursor-not-allowed">
                                    Lanjutkan
                                </button>
                            </form>
                        </div>

                        {{-- Tampilan saat belum ada produk dipilih --}}
                        <div x-show="selectedProducts.length === 0" x-cloak class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7l8 5 8-5"/></svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-800">Belum Ada Pilihan</h3>
                            <p class="mt-1 text-sm text-slate-500">Klik kartu produk di sebelah kiri untuk menambahkannya.</p>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
@endsection

@push('scripts')
<script>
    function productPicker(allProducts) {
        return {
            allProducts: allProducts,
            selectedProducts: [],

            toggleProduct(id) {
                const index = this.selectedProducts.findIndex(p => p.id === id);
                if (index > -1) {
                    this.selectedProducts.splice(index, 1);
                } else {
                    const productToAdd = this.allProducts.find(p => p.id === id);
                    if (productToAdd) {
                        this.selectedProducts.push(productToAdd);
                    }
                }
            },

            isSelected(id) {
                return this.selectedProducts.some(p => p.id === id);
            }
        }
    }
</script>
@endpush
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Produk untuk Produksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        /* Kustomisasi scrollbar untuk tampilan yang lebih bersih */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

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

        <header class="container mx-auto px-4 sm:px-6 lg:px-8">
             <div class="text-center py-12 md:py-16">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">
                    Rencana Produksi Baru
                </h1>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">
                    Pilih satu atau lebih produk untuk dimasukkan ke dalam antrean produksi.
                </p>
            </div>
        </header>

        <div x-show="selectedProducts.length > 0" x-cloak x-transition
             class="sticky top-0 z-20 bg-white/80 backdrop-blur-sm shadow-lg">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center gap-4 overflow-x-auto py-2">
                        <h2 class="font-semibold text-slate-900 flex-shrink-0">
                           Terpilih (<span x-text="selectedProducts.length"></span>):
                        </h2>
                        <ul class="flex items-center gap-2">
                            <template x-for="product in selectedProducts" :key="product.id">
                                <li class="bg-sky-100 text-sky-800 text-sm font-medium px-3 py-1.5 rounded-full flex items-center flex-shrink-0">
                                    <span x-text="product.name"></span>
                                    <button @click="toggleProduct(product)" class="ml-2 text-sky-600 hover:text-sky-900">&times;</button>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div class="flex-shrink-0 pl-4">
                         <button class="bg-sky-600 text-white font-bold py-2.5 px-5 rounded-lg hover:bg-sky-700 transition duration-300 shadow-sm whitespace-nowrap">
                            Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <template x-for="product in products" :key="product.id">
                    
                    <div @click="toggleProduct(product)"
                         :class="isSelected(product.id) ? 'border-sky-500 shadow-sky-100' : 'border-transparent shadow-slate-100'"
                         class="relative bg-white rounded-xl border-2 cursor-pointer transition-all duration-300 hover:shadow-xl hover:-translate-y-1 shadow-md">

                        <div x-show="isSelected(product.id)" x-transition class="absolute top-3 right-3 bg-sky-500 text-white rounded-full h-6 w-6 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>

                        <div class="flex flex-col h-full">
                            <img :src="product.image" :alt="product.name" class="w-full h-48 object-cover rounded-t-lg">
                            <div class="p-4 flex flex-col flex-grow">
                                <p class="text-xs text-slate-500" x-text="product.sku"></p>
                                <h3 class="font-semibold text-slate-900 mt-1" x-text="product.name"></h3>
                                <div class="mt-auto pt-4">
                                     <div :class="isSelected(product.id) ? 'bg-sky-50 text-sky-600' : 'bg-slate-100 text-slate-600'" 
                                        class="w-full text-center font-semibold py-2 px-4 rounded-md transition duration-300 text-sm">
                                        <span x-text="isSelected(product.id) ? 'Hapus' : 'Pilih Produk'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </main>

    </div>
</body>
</html>
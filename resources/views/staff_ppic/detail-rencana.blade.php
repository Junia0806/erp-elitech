<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Rencana Produksi Baru</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11'; } /* Fitur opsional font Inter */
        input[type='number']::-webkit-inner-spin-button,
        input[type='number']::-webkit-outer-spin-button {
            -webkit-appearance: none; margin: 0;
        }
        input[type='number'] { -moz-appearance: textfield; }
    </style>
</head>
<body class="bg-slate-200 text-slate-800 antialiased">

    <div x-data="{
        items: [
            { id: 1, sku: 'KMLP-001', name: 'Kemeja Lengan Panjang', image: 'https://placehold.co/400x400/0ea5e9/FFFFFF?text=Kemeja', quantity: 100 },
            { id: 3, sku: 'KPC-003', name: 'Kaos Polos Cotton 30s', image: 'https://placehold.co/400x400/f59e0b/FFFFFF?text=Kaos', quantity: 250 },
            { id: 4, sku: 'JBP-004', name: 'Jaket Bomber Parasut', image: 'https://placehold.co/400x400/ef4444/FFFFFF?text=Jaket', quantity: 75 }
        ],
        deadline: 14,
        notes: '',
        get totalTarget() {
            return this.items.reduce((total, item) => total + (parseInt(item.quantity) || 0), 0);
        },
        confirmSubmit() {
            Swal.fire({
                title: 'Kirim Rencana Produksi?',
                html: 'Rencana yang sudah dikirim <strong>tidak dapat diubah</strong>. Pastikan semua detail sudah benar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Kirim Sekarang!',
                cancelButtonText: 'Periksa Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Terkirim!', 'Rencana produksi Anda telah berhasil dikirim.', 'success');
                }
            })
        }
    }">

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-6">
            
            <header class="mb-10 text-center">
               
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tighter text-slate-900 mt-3">
                   Detail Rencana Produksi Baru
                </h1>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-8">
                
                <div class="lg:col-span-2 space-y-8">
                    <form id="production-plan-form" @submit.prevent="confirmSubmit" class="space-y-8">
                        
                        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg shadow-slate-200/60">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <span>Langkah 1: Tentukan Target Produksi</span>
                            </h2>
                            <div class="mt-6 space-y-6">
                                <template x-for="item in items" :key="item.id">
                                    <div class="flex items-center gap-4">
                                        <img :src="item.image" :alt="item.name" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover flex-shrink-0 border-2 border-white shadow-md">
                                        <div class="flex-grow">
                                            <p class="font-semibold text-slate-800" x-text="item.name"></p>
                                            <p class="text-sm text-slate-500">SKU: <span x-text="item.sku"></span></p>
                                        </div>
                                        <div class="flex-shrink-0 w-28">
                                            <input type="number" :id="'quantity-' + item.id" x-model.number="item.quantity" min="1" class="w-full rounded-lg border-slate-300 text-center text-lg font-bold bg-slate-200 py-2.5 focus:border-sky-500 focus:ring-2 focus:ring-sky-300 focus:ring-offset-2 transition">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg shadow-slate-200/60">
                             <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span>Langkah 2: Atur Jadwal & Informasi</span>
                            </h2>
                            <div class="mt-6 grid grid-cols-1 gap-6">
                                <div>
                                    <label for="deadline" class="block text-base font-semibold text-slate-800 mb-2 flex items-center gap-2">
                                        Deadline Produksi
                                    </label>
                                    <div class="relative">
                                        <input type="number" id="deadline" x-model.number="deadline" min="1" class="w-full rounded-lg border-slate-300 pl-4 pr-16 text-xl font-bold bg-slate-200 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-300 focus:ring-offset-2 transition">
                                        <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                            <span class="text-slate-500 font-semibold">Hari</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="notes" class="block text-base font-semibold text-slate-800 mb-2 flex items-center gap-2">
                                        Informasi Tambahan <span class="font-normal text-slate-500">(Opsional)</span>
                                    </label>
                                    <textarea id="notes" x-model="notes" rows="5" class="w-full rounded-lg border-slate-300 bg-slate-200 p-4 focus:border-sky-500 focus:ring-2 focus:ring-sky-300 focus:ring-offset-2 transition" placeholder="Contoh: Prioritaskan produksi kemeja untuk pengiriman minggu depan..."></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-1 mt-8 lg:mt-0">
                    <div class="sticky top-8 space-y-6">
                        <div class="bg-white p-6 rounded-2xl shadow-lg shadow-slate-200/60">
                             <h2 class="text-lg font-bold text-slate-900 flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Ringkasan Rencana
                            </h2>
                            <div class="mt-4 space-y-4 text-sm">
                                <div class="flex justify-between items-center bg-slate-200/70 p-3 rounded-lg">
                                    <span class="text-slate-600 font-medium">Total Jenis Produk</span>
                                    <span class="font-bold text-slate-900 text-base" x-text="items.length"></span>
                                </div>
                                <div class="flex justify-between items-center bg-slate-200/70 p-3 rounded-lg">
                                    <span class="text-slate-600 font-medium">Total Target Produksi</span>
                                    <span class="font-bold text-slate-900 text-base" x-text="totalTarget.toLocaleString('id-ID')"></span>
                                </div>
                                <div class="flex justify-between items-center bg-sky-100/60 p-3 rounded-lg mt-3 border-t-2 border-sky-200">
                                    <span class="text-sky-800 font-semibold">Estimasi Selesai</span>
                                    <span class="font-extrabold text-sky-600 text-base"><span x-text="deadline"></span> Hari</span>
                                </div>
                            </div>
                            <button @click="confirmSubmit" class="w-full mt-6 bg-sky-500 text-white font-bold py-3.5 px-5 rounded-lg hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-300/50 transition duration-300 shadow-lg shadow-sky-500/30 hover:shadow-xl hover:shadow-sky-500/40 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.428A1 1 0 009.894 15V4A1 1 0 008.894 3l-7 2z" /></svg>
                                <span>Kirim Rencana Produksi</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
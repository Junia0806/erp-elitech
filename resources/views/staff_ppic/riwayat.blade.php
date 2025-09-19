<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengajuan Produksi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
</head>

<body class="bg-slate-50 text-slate-700 antialiased">

    <div x-data="{
        submissions: [{
                id: 'RP001',
                tanggal: '18 Sep 2025',
                deadline: 14, // Data Deadline ditambahkan
                info: 'Prioritas tinggi untuk klien A.',
                status_pengajuan: 'Disetujui',
                status_progress: 'Proses Produksi',
                progress_percentage: 75, // Data Persentase Progress ditambahkan
                products: [
                    { name: 'Kemeja Lengan Panjang', sku: 'KMLP-001', target: 100 },
                    { name: 'Kaos Polos Cotton 30s', sku: 'KPC-003', target: 250 },
                ],
            },
            {
                id: 'RP002',
                tanggal: '15 Sep 2025',
                deadline: 7,
                info: '-',
                status_pengajuan: 'Disetujui',
                status_progress: 'Selesai',
                progress_percentage: 100,
                products: [
                    { name: 'Celana Chino Slim Fit', sku: 'CCSF-001', target: 150 },
                ],
            },
            {
                id: 'RP003',
                tanggal: '12 Sep 2025',
                deadline: 21,
                info: 'Ditolak karena bahan baku tidak tersedia.',
                status_pengajuan: 'Ditolak',
                status_progress: 'Dibatalkan',
                progress_percentage: 0,
                products: [
                    { name: 'Topi Baseball', sku: 'TB-001', target: 300 },
                ],
            }
        ],
        getStatusColor(status) {
            // ... (Fungsi helper tetap sama)
        }
    }">

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-">

            <header class="mb-6">
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-slate-900">
                    Riwayat Produksi
                </h1>

            </header>

            <div class="bg-white p-5 rounded-2xl shadow-xl shadow-slate-200/100 border border-slate-200 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-4">
                        <label for="start_date" class="text-sm font-semibold text-slate-600">Dari Tanggal</label>
                        <input type="date" id="start_date" x-model="startDate"
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 transition">
                    </div>
                    <div class="md:col-span-4">
                        <label for="end_date" class="text-sm font-semibold text-slate-600">Sampai Tanggal</label>
                        <input type="date" id="end_date" x-model="endDate"
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 transition">
                    </div>
                    <div class="md:col-span-4 flex items-center gap-2">
                        <button
                            class="w-1/2 bg-green-600 text-white font-semibold py-2.5 px-3 rounded-lg hover:bg-green-700 transition shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Excel</span>
                        </button>
                        <button
                            class="w-1/2 bg-red-600 text-white font-semibold py-2.5 px-3 rounded-lg hover:bg-red-700 transition shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <span>PDF</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                <template x-for="submission in submissions" :key="submission.id">

                    <div
                        class="bg-white rounded-xl shadow-lg shadow-slate-200/40 flex flex-col h-full border border-slate-100 overflow-hidden">

                        <div class="p-5 bg-slate-50/50 border-b border-slate-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-bold text-slate-800" x-text="`Pengajuan #${submission.id}`">
                                    </p>
                                    <p class="text-xs text-slate-500 flex items-center gap-1.5 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="submission.tanggal"></span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">DEADLINE
                                    </p>
                                    <p class="font-bold text-slate-800"><span x-text="submission.deadline"></span> Hari
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 flex-grow">
                            <h4 class="font-semibold text-slate-800 mb-2">Rencana Produksi:</h4>
                            <ul class="space-y-3">
                                <template x-for="product in submission.products" :key="product.sku">
                                    <li class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-slate-700" x-text="product.name"></p>
                                            <p class="text-sm text-slate-500" x-text="`SKU: ${product.sku}`"></p>
                                        </div>
                                        <p class="font-bold text-sky-600 text-right flex-shrink-0 ml-4"><span
                                                x-text="product.target"></span> Pcs</p>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <div class="p-5 bg-slate-50/50 space-y-2 border-t border-slate-200">
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-semibold text-slate-600">Status</p>
                                <span
                                    class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-green-100 text-green-800': submission.status_pengajuan === 'Disetujui',
                                        'bg-red-100 text-red-800': submission.status_pengajuan === 'Ditolak'
                                    }">
                                    <svg x-show="submission.status_pengajuan === 'Disetujui'" class="w-3.5 h-3.5"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <svg x-show="submission.status_pengajuan === 'Ditolak'" class="w-3.5 h-3.5"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span x-text="submission.status_pengajuan"></span>
                                </span>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <p class="text-sm font-semibold text-slate-600">Progres</p>
                                    <p class="text-sm font-bold text-sky-600"
                                        x-text="`${submission.progress_percentage}%`"></p>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-sky-500 h-2 rounded-full"
                                        :style="`width: ${submission.progress_percentage}%`"></div>

                                </div>
                            </div>
                            <div>

                                <p class="text-sm font-semibold text-slate-600">Informasi
                                    Tambahan:
                                <p class="text-sm text-slate-700 mb-4" x-text="submission.info"></p>
                                </p>
                            </div>

                            <div class="p-5">
                                <button
                                    class="w-full bg-sky-600 text-white font-semibold py-2.5 px-4 rounded-lg 
           hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-300 
           transition duration-300 flex items-center justify-center gap-2">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v3a2 2 0 002 2h6a2 2 0 002-2v-3h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Cetak Laporan</span>

                                </button>
                            </div>
                        </div>
                </template>
            </div>
        </div>
    </div>

</body>

</html>

@extends('staff_ppic.app')

@section('title', 'Riwayat Produksi')

@section('content')

    <div x-data="{
        // Data riwayat (statis untuk slicing)
        submissions: [{
                id: 'RP001',
                tanggal: '18 Sep 2025',
                deadline: 14, // Data deadline
                status_pengajuan: 'Disetujui',
                progress_percentage: 75,
                products: [
                    { name: 'Kemeja Lengan Panjang', sku: 'KMLP-001', target: 100 },
                    { name: 'Kaos Polos Cotton 30s', sku: 'KPC-003', target: 250 },
                ],
                info: 'Prioritas tinggi untuk klien A.'
            },
            {
                id: 'RP002',
                tanggal: '15 Sep 2025',
                deadline: 7,
                status_pengajuan: 'Disetujui',
                progress_percentage: 100,
                products: [
                    { name: 'Celana Chino Slim Fit', sku: 'CCSF-001', target: 150 },
                ],
                info: '-'
            },
            {
                id: 'RP003',
                tanggal: '12 Sep 2025',
                deadline: 21,
                status_pengajuan: 'Ditolak',
                progress_percentage: 0,
                products: [
                    { name: 'Topi Baseball', sku: 'TB-001', target: 300 },
                ],
                info: 'Ditolak karena bahan baku tidak tersedia.'
            }
        ],
        openSubmissionId: null,
    getStatusColor(status) {
        switch (status) {
            case 'Disetujui':
                return 'bg-green-100 text-green-800';
            case 'Ditolak':
                return 'bg-red-100 text-red-800';
            case 'Menunggu Persetujuan': // Contoh status tambahan
                return 'bg-yellow-100 text-yellow-800';
            default:
                return 'bg-slate-100 text-slate-800';
        }
    }
    }">

        <main class=" mx-auto px-4 sm:px-6 lg:px-4 py-2">

            <header class="mb-2 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                        Riwayat Produksi
                    </h1>
                    <p class="mt-1 text-base text-slate-500">
                        Lacak semua pengajuan rencana produksi yang telah dibuat.
                    </p>
                </div>
            </header>

            {{-- Panel Filter & Ekspor --}} <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="start_date" class="block text-xs font-medium text-slate-600 mb-1">Dari Tanggal</label>
                        <input type="date" id="start_date"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 text-sm">
                    </div>
                    <div>
                        <label for="end_date" class="block text-xs font-medium text-slate-600 mb-1">Sampai Tanggal</label>
                        <input type="date" id="end_date"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 text-sm">
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            class="w-full bg-slate-800 text-white font-semibold py-2 px-3 rounded-md hover:bg-slate-900 transition shadow-sm flex items-center justify-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd"
                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Filter</span>
                        </button>
                        <button
                            class="w-full bg-green-600 text-white font-semibold py-2 px-3 rounded-md hover:bg-green-700 transition shadow-sm flex items-center justify-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path
                                    d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" />
                                <path
                                    d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" />
                            </svg>
                            <span>Ekspor</span>
                        </button>
                    </div>
                </div>
            </div>
            {{-- Daftar Riwayat --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                {{-- Header Tabel untuk Desktop --}}
                <div
                    class="hidden md:grid grid-cols-12 gap-4 p-4 bg-slate-50 border-b font-semibold text-slate-600 text-sm">
                    <div class="col-span-2">ID Rencana</div>
                    <div class="col-span-3">Tanggal Dibuat</div>
                    <div class="col-span-2">Deadline</div>
                    <div class="col-span-2">Persetujuan Manajer</div>
                    <div class="col-span-2">Progress Produksi</div>
                    <div class="col-span-1 text-center">Aksi</div>
                </div>

                <div x-show="submissions.length > 0" class="divide-y divide-slate-200">
                    <template x-for="submission in submissions" :key="submission.id">
                        <div>

                            <div class="grid grid-cols-12 gap-4 p-4 hover:bg-slate-50 cursor-pointer"
                                @click="openSubmissionId = (openSubmissionId === submission.id) ? null : submission.id">
                                <div class="col-span-12 md:col-span-2 font-semibold text-slate-800">
                                    <span class="md:hidden text-slate-500 font-medium">ID: </span>
                                    <span x-text="submission.id"></span>
                                </div>
                                <div class="col-span-6 md:col-span-3 text-slate-600">
                                    <span class="md:hidden text-slate-500 font-medium">Tanggal: </span>
                                    <span x-text="submission.tanggal"></span>
                                </div>

                                <div class="col-span-6 md:col-span-2 font-medium text-slate-600">
                                    <span class="md:hidden text-slate-500">Deadline: </span>
                                    <span x-text="`${submission.deadline} hari`"></span>
                                </div>
                              <div class="col-span-6 md:col-span-2">
                                <span :class="getStatusColor(submission.status_pengajuan)" class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-medium" x-text="submission.status_pengajuan"></span>
                            </div>
                                <div class="col-span-11 md:col-span-2 flex items-center gap-2">
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-sky-500 h-2 rounded-full"
                                            :style="`width: ${submission.progress_percentage}%`"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-sky-600"
                                        x-text="`${submission.progress_percentage}%`"></span>
                                </div>
                                <div class="col-span-1 flex items-center justify-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="w-5 h-5 transition-transform"
                                        :class="{ 'rotate-180': openSubmissionId === submission.id }">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div x-show="openSubmissionId === submission.id" x-transition
                                class="bg-slate-50/70 p-4 border-t border-slate-200">
                                <h4 class="font-semibold text-slate-800 mb-2">Detail Produk:</h4>
                                <ul class="space-y-2 mb-4">
                                    <template x-for="product in submission.products" :key="product.sku">
                                        <li class="flex justify-between items-center text-sm">
                                            <div>
                                                <p class="font-medium text-slate-700" x-text="product.name"></p>
                                                <p class="text-xs text-slate-500" x-text="`SKU: ${product.sku}`"></p>
                                            </div>
                                            <p class="font-bold text-slate-800"><span
                                                    x-text="product.target.toLocaleString('id-ID')"></span> Pcs</p>
                                        </li>
                                    </template>
                                </ul>
                                <div class="border-t pt-3">
                                    <h5 class="text-sm font-semibold text-slate-800">Informasi Tambahan:</h5>
                                    <p class="text-sm text-slate-600 italic" x-text="submission.info"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </main>
    </div>
@endsection

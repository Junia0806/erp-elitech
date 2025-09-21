@extends('manajer.app')

@section('title', 'Riwayat Produksi')

@section('content')
<div 
    x-data="productionHistory()"
    class="bg-slate-50 min-h-screen font-sans"
>
    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Halaman Baru dengan Filter -->
        <header class="mb-8 p-6 bg-white rounded-xl shadow-md border border-slate-200">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800">Riwayat Produksi</h1>
                    <p class="mt-1 text-slate-500">
                        <span x-text="`Total ${history.length} pengajuan telah diproses.`"></span>
                    </p>
                </div>
                <div class="flex items-center bg-slate-100 p-1 rounded-full space-x-1">
                    <button @click="setFilter('semua')" :class="filterStatus === 'semua' ? 'bg-white text-slate-800 shadow' : 'text-slate-600 hover:bg-slate-200'" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors">
                        Semua
                    </button>
                    <button @click="setFilter('Disetujui')" :class="filterStatus === 'Disetujui' ? 'bg-white text-green-600 shadow' : 'text-slate-600 hover:bg-slate-200'" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors">
                        Disetujui
                    </button>
                    <button @click="setFilter('Ditolak')" :class="filterStatus === 'Ditolak' ? 'bg-white text-red-600 shadow' : 'text-slate-600 hover:bg-slate-200'" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors">
                        Ditolak
                    </button>
                </div>
            </div>
        </header>

        <!-- Daftar Riwayat -->
        <div class="space-y-3">
            <template x-for="item in filteredHistory" :key="item.id">
                <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 ease-in-out hover:shadow-lg relative">
                    <!-- Garis Status Kiri -->
                    <div class="absolute left-0 top-0 h-full w-1.5 rounded-l-xl" :class="item.status_pengajuan === 'Disetujui' ? 'bg-green-500' : 'bg-red-500'"></div>
                    
                    <!-- Ringkasan Riwayat (Area Klik) -->
                    <div 
                        class="p-4 pl-6 cursor-pointer hover:bg-slate-50/70"
                        @click="toggleAccordion(item.id)"
                    >
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <p class="font-bold text-lg text-slate-800" x-text="`#${item.id}`"></p>
                                <p class="text-sm text-slate-500" x-text="`Pengajuan: ${item.tanggal_pengajuan}`"></p>
                            </div>
                            <div class="flex items-center gap-4 sm:gap-6 text-sm text-center">
                                <div>
                                    <p class="font-bold text-slate-700" x-text="item.products.length"></p>
                                    <p class="text-xs text-slate-500">Produk</p>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700" x-text="item.products.reduce((acc, p) => acc + p.target, 0).toLocaleString('id-ID')"></p>
                                    <p class="text-xs text-slate-500">Pcs</p>
                                </div>
                                <div class="text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform" :class="{ 'rotate-180': openHistoryId === item.id }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar atau Status Ditolak -->
                        <template x-if="item.status_pengajuan === 'Disetujui'">
                            <div>
                                <div class="flex justify-between text-xs font-medium text-slate-500 mb-1">
                                    <span class="font-semibold text-green-700">Disetujui</span>
                                    <span x-text="`Estimasi Selesai: ${calculateEstimatedFinishDate(item.tanggal_disetujui, item.deadline)}`"></span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-sky-600 h-2 rounded-full" :style="`width: ${item.progress_percentage}%`"></div>
                                </div>
                            </div>
                        </template>
                         <template x-if="item.status_pengajuan === 'Ditolak'">
                            <div class="bg-red-50 text-red-700 p-2 rounded-md border border-red-200">
                                <p class="text-sm font-semibold">Ditolak pada <span class="font-normal" x-text="item.tanggal_disetujui"></span></p>
                            </div>
                        </template>
                    </div>

                    <!-- Detail Riwayat (Accordion Content) -->
                    <div x-show="openHistoryId === item.id" x-transition.origin.top class="border-t border-slate-200">
                        <div class="bg-slate-50 p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <!-- Rincian Produk & Progress Detail -->
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-semibold text-slate-700 mb-2">Rincian Produk</h4>
                                    <ul class="divide-y divide-slate-200 bg-white border rounded-lg p-2 shadow-inner">
                                        <template x-for="product in item.products" :key="product.sku">
                                            <li class="flex justify-between items-center py-2 px-1 text-sm">
                                                <span>
                                                    <span class="font-medium text-slate-700" x-text="product.name"></span>
                                                    <span class="text-slate-400" x-text="` (SKU: ${product.sku})`"></span>
                                                </span>
                                                <span class="font-bold text-slate-800" x-text="`${product.target.toLocaleString('id-ID')} Pcs`"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                            <!-- Catatan PPIC & Manajer -->
                            <div class="space-y-4">
                                 <div>
                                    <h5 class="font-semibold text-slate-700 mb-2">Catatan dari PPIC</h5>
                                    <blockquote class="text-sm text-slate-700 italic border-l-4 border-slate-400 bg-slate-100 p-3 rounded-r-lg">
                                        <p x-text="item.info_ppic || 'Tidak ada catatan.'"></p>
                                    </blockquote>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-slate-700 mb-2">Catatan Manajer</h5>
                                    <blockquote x-show="item.status_pengajuan === 'Disetujui'" class="text-sm text-green-800 italic border-l-4 border-green-500 bg-green-50 p-3 rounded-r-lg">
                                        <p x-text="item.catatan_manajer || 'Disetujui tanpa catatan tambahan.'"></p>
                                    </blockquote>
                                     <blockquote x-show="item.status_pengajuan === 'Ditolak'" class="text-sm text-red-800 italic border-l-4 border-red-500 bg-red-50 p-3 rounded-r-lg">
                                        <p x-text="item.catatan_manajer || 'Tidak ada alasan penolakan.'"></p>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Kondisi Filter Kosong -->
            <div x-show="filteredHistory.length === 0 && history.length > 0" class="text-center py-20">
                 <div class="inline-block bg-yellow-100 text-yellow-700 p-4 rounded-full">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                 </div>
                <h3 class="mt-4 text-xl font-semibold text-slate-700">Tidak Ada Hasil</h3>
                <p class="text-slate-500 mt-1" x-text="`Tidak ada riwayat yang cocok dengan filter '${filterStatus}'.`"></p>
            </div>
            
            <!-- Kondisi Awal Kosong -->
            <div x-show="history.length === 0" class="text-center py-20">
                 <div class="inline-block bg-sky-100 text-sky-700 p-4 rounded-full">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                 </div>
                <h3 class="mt-4 text-xl font-semibold text-slate-700">Belum Ada Riwayat</h3>
                <p class="text-slate-500 mt-1">Riwayat pengajuan yang telah diproses akan muncul di sini.</p>
            </div>
        </div>
    </main>
</div>

<script>
    function productionHistory() {
        return {
            history: [
                {
                    id: 'RP001',
                    tanggal_pengajuan: '18 Sep 2025',
                    tanggal_disetujui: '2025-09-20',
                    deadline: 14,
                    status_pengajuan: 'Disetujui',
                    progress_percentage: 75,
                    products: [
                        { name: 'Kemeja Lengan Panjang', sku: 'KMLP-001', target: 100 },
                        { name: 'Kaos Polos Cotton 30s', sku: 'KPC-003', target: 250 },
                    ],
                    info_ppic: 'Prioritas tinggi untuk klien A.',
                    catatan_manajer: 'Segera proses, pastikan kualitas jahitan rapi.'
                },
                {
                    id: 'RP002',
                    tanggal_pengajuan: '15 Sep 2025',
                    tanggal_disetujui: '2025-09-16',
                    deadline: 7,
                    status_pengajuan: 'Disetujui',
                    progress_percentage: 100,
                    products: [
                        { name: 'Celana Chino Slim Fit', sku: 'CCSF-001', target: 150 },
                    ],
                    info_ppic: 'Kebutuhan mendesak untuk event tanggal 25.',
                    catatan_manajer: null
                },
                {
                    id: 'RP003',
                    tanggal_pengajuan: '12 Sep 2025',
                    tanggal_disetujui: '2025-09-13',
                    deadline: 21,
                    status_pengajuan: 'Ditolak',
                    progress_percentage: 0,
                    products: [
                        { name: 'Topi Baseball', sku: 'TB-001', target: 300 },
                    ],
                    info_ppic: 'Produksi untuk stok gudang.',
                    catatan_manajer: 'Ditolak karena bahan baku utama tidak tersedia dari supplier. Harap ajukan kembali jika sudah ada.'
                },
                {
                    id: 'RP004',
                    tanggal_pengajuan: '11 Sep 2025',
                    tanggal_disetujui: '2025-09-12',
                    deadline: 5,
                    status_pengajuan: 'Disetujui',
                    progress_percentage: 100,
                    products: [ { name: 'Polo Shirt', sku: 'PS-002', target: 200 } ],
                    info_ppic: 'Klien butuh cepat untuk sampel.',
                    catatan_manajer: 'OK.'
                },
                {
                    id: 'RP005',
                    tanggal_pengajuan: '10 Sep 2025',
                    tanggal_disetujui: '2025-09-11',
                    deadline: 10,
                    status_pengajuan: 'Ditolak',
                    progress_percentage: 0,
                    products: [ { name: 'Hoodie Jumper', sku: 'HJ-010', target: 80 } ],
                    info_ppic: 'Stok menipis.',
                    catatan_manajer: 'Desain bordir terlalu rumit untuk deadline yang diajukan. Mohon revisi desain atau perpanjang deadline.'
                }
            ],
            openHistoryId: null,
            filterStatus: 'semua',

            toggleAccordion(id) {
                this.openHistoryId = this.openHistoryId === id ? null : id;
            },

            setFilter(status) {
                this.filterStatus = status;
            },

            get filteredHistory() {
                if (this.filterStatus === 'semua') {
                    return this.history;
                }
                return this.history.filter(item => item.status_pengajuan === this.filterStatus);
            },

            calculateEstimatedFinishDate(approvalDate, deadline) {
                if (!approvalDate || !deadline) return 'N/A';
                const date = new Date(approvalDate);
                date.setDate(date.getDate() + deadline);
                return date.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
        }
    }
</script>
@endsection


@extends('staff_produksi.app')

@section('title', 'Daftar Rencana Produksi')

@section('content')
<div 
    x-data="{
        productionPlans: {{ json_encode($tasks) }},
        isModalOpen: false,
        isReportModalOpen: false,
        selectedPlanId: null,
        actionUrl: '',
        originalStatus: '',
        newStatus: '',
        reportInputs: [],
        availableStatuses: ['Antri', 'Dikerjakan', 'Selesai'],
        
        get selectedPlan() {
            if (!this.selectedPlanId) return null;
            return this.productionPlans.find(p => p.id === this.selectedPlanId);
        },

        getStatusInfo(status) {
            const statuses = {
                'Antri': { percentage: 10, badgeClass: 'bg-slate-200 text-slate-700', progressClass: 'bg-slate-400' },
                'Dikerjakan': { percentage: 50, badgeClass: 'bg-blue-200 text-blue-700', progressClass: 'bg-blue-500' },
                'Selesai': { percentage: 100, badgeClass: 'bg-green-200 text-green-700', progressClass: 'bg-green-500' },
                'Menunggu': { percentage: 5, badgeClass: 'bg-yellow-100 text-yellow-800', progressClass: 'bg-yellow-400' }
            };
            return statuses[status] || statuses['Antri'];
        },

        openUpdateModal(planId, url) {
            this.selectedPlanId = planId;
            this.actionUrl = url;
            this.originalStatus = this.selectedPlan.status_produksi;
            this.newStatus = this.selectedPlan.status_produksi;
            this.isModalOpen = true;
        },
        
        async updateStatus() {
            if (!this.selectedPlanId || this.newStatus === this.originalStatus) { this.isModalOpen = false; return; };
            const csrfToken = document.querySelector('meta[name=\'csrf-token\']').getAttribute('content');
            try {
                const response = await fetch(this.actionUrl, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ status: this.newStatus })
                });
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Gagal memperbarui status.');
                }
                const result = await response.json();
                const planIndex = this.productionPlans.findIndex(p => p.id === this.selectedPlanId);
                if (planIndex > -1) this.productionPlans[planIndex].status_produksi = this.newStatus;
                
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: result.message, showConfirmButton: false, timer: 3000 });
                
                setTimeout(() => window.location.reload(), 1500);

            } catch (error) {
                console.error(error);
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: error.message || 'Terjadi kesalahan!', showConfirmButton: false, timer: 4000 });
            } finally {
                this.isModalOpen = false;
            }
        },
        
        openReportModal(planId, url) {
            this.selectedPlanId = planId;
            this.actionUrl = url;
            this.reportInputs = JSON.parse(JSON.stringify(this.selectedPlan.products)).map(p => ({
                ...p,
                hasil_produksi: p.hasil_produksi || 0,
                reject_produksi: p.reject_produksi || 0,
            }));
            this.isReportModalOpen = true;
        },

        async saveReport() {
             if (!this.selectedPlanId) return;
            const csrfToken = document.querySelector('meta[name=\'csrf-token\']').getAttribute('content');
            const payload = { products: this.reportInputs.map(p => ({ id: p.id, hasil_produksi: p.hasil_produksi, reject_produksi: p.reject_produksi })) };
            try {
                const response = await fetch(this.actionUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Gagal menyimpan laporan.');
                }
                const result = await response.json();
                
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: result.message, showConfirmButton: false, timer: 3000 });
                
                setTimeout(() => window.location.reload(), 1500);

            } catch (error) {
                console.error(error);
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: error.message || 'Terjadi kesalahan!', showConfirmButton: false, timer: 4000 });
            } finally {
                this.isReportModalOpen = false;
            }
        },
        
        isStatusDone(statusToCheck) {
            if (!this.selectedPlan) return false;
            const statusOrder = ['Menunggu', 'Antri', 'Dikerjakan', 'Selesai'];
            const currentStatusIndex = statusOrder.indexOf(this.originalStatus);
            const checkStatusIndex = statusOrder.indexOf(statusToCheck);
            return checkStatusIndex < currentStatusIndex;
        }
    }"
    class="bg-slate-50 min-h-screen font-sans"
>
    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Halaman -->
        <header class="mb-8 p-6 bg-white rounded-xl shadow-md border border-slate-200">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Tugas Produksi</h1>
                <p class="mt-1 text-slate-500" x-text="`Terdapat ${productionPlans.length} rencana produksi yang perlu dikerjakan.`"></p>
            </div>
        </header>

        <!-- Daftar Rencana Produksi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="plan in productionPlans" :key="plan.id">
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 flex flex-col overflow-hidden transition-transform hover:-translate-y-1">
                    <div class="p-5 flex-grow">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                {{-- [FIX] Gunakan display_id untuk tampilan --}}
                                <p class="font-bold text-xl text-slate-800" x-text="plan.display_id"></p>
                                {{-- [FIX] Gunakan status_produksi --}}
                                <span :class="getStatusInfo(plan.status_produksi).badgeClass" class="text-xs font-semibold px-2.5 py-0.5 rounded-full" x-text="plan.status_produksi"></span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-slate-600">Estimasi Selesai</p>
                                {{-- [FIX] Gunakan estimasi_selesai --}}
                                <p class="text-sm text-slate-500" x-text="plan.estimasi_selesai"></p>
                            </div>
                        </div>
                        
                        <p class="text-slate-600 font-semibold mb-2">Produk:</p>
                        <ul class="text-sm space-y-1 mb-4">
                            <template x-for="product in plan.products" :key="product.id">
                                <li class="flex justify-between">
                                    <span class="text-slate-700" x-text="product.name"></span>
                                    <span class="font-semibold text-slate-500" x-text="`${product.target.toLocaleString('id-ID')} Pcs`"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="px-5 pb-5">
                        <div class="w-full bg-slate-200 rounded-full h-2.5">
                            <div :class="getStatusInfo(plan.status_produksi).progressClass" class="h-2.5 rounded-full transition-all duration-500" :style="`width: ${getStatusInfo(plan.status_produksi).percentage}%`"></div>
                        </div>
                    </div>

                    <!-- Aksi -->
                    <div class="bg-slate-50 border-t p-4 flex gap-3">
                        <button x-show="plan.status_produksi !== 'Selesai'" @click="openUpdateModal(plan.id, '{{ route('produksi.staff.tasks.update', '') }}/' + plan.id)" class="w-full bg-sky-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-sky-700 transition-colors text-sm flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                            Update Status
                        </button>
                        
                        <button x-show="plan.status_produksi === 'Selesai'" @click="openUpdateModal(plan.id, '{{ route('produksi.staff.tasks.update', '') }}/' + plan.id)" class="w-full bg-slate-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-slate-700 transition-colors text-sm flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                            Lihat Laporan
                        </button>

                        <button @click="openReportModal(plan.id, '{{ route('produksi.staff.tasks.store', '') }}/' + plan.id)" x-show="plan.status_produksi === 'Selesai'" class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition-colors text-sm flex items-center justify-center gap-2">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                            Kelola Laporan
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </main>

    <!-- Modal Update Status -->
    <div x-show="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" x-transition.opacity style="display: none;">
        <template x-if="selectedPlan">
            <div @click.away="isModalOpen = false" class="bg-white rounded-xl shadow-2xl w-full max-w-4xl transform" x-transition.scale>
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold text-slate-800" x-text="`Update Status Produksi ${selectedPlan.display_id}`"></h3>
                </div>
                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <h5 class="font-semibold text-slate-700 mb-2">Catatan dari PPIC</h5>
                                <blockquote class="text-sm text-slate-700 italic border-l-4 border-slate-400 bg-slate-100 p-3 rounded-r-lg">
                                    <p x-text="selectedPlan.info_ppic || 'Tidak ada catatan.'"></p>
                                </blockquote>
                            </div>
                            <div>
                                <h5 class="font-semibold text-slate-700 mb-2">Catatan Manajer</h5>
                                <blockquote class="text-sm text-green-800 italic border-l-4 border-green-500 bg-green-50 p-3 rounded-r-lg">
                                    <p x-text="selectedPlan.catatan_manajer || 'Disetujui tanpa catatan.'"></p>
                                </blockquote>
                            </div>
                            <div>
                                <h5 class="font-semibold text-slate-700 mb-2">Riwayat Status</h5>
                                <ul class="space-y-3 border-l-2 border-slate-200 ml-2">
                                    <template x-for="event in selectedPlan.history" :key="event.timestamp">
                                        <li class="relative pl-6">
                                            <div class="absolute -left-[7px] top-1.5 w-3 h-3 rounded-full" :class="getStatusInfo(event.status).progressClass"></div>
                                            <p class="font-semibold text-slate-600" x-text="event.status"></p>
                                            <p class="text-xs text-slate-400" x-text="event.timestamp"></p>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <h4 class="font-semibold text-slate-700 mb-2">Rincian Produk</h4>
                                <ul class="divide-y divide-slate-200 bg-slate-50 border rounded-lg p-2 shadow-inner max-h-48 overflow-y-auto">
                                     <template x-for="product in selectedPlan.products" :key="product.id">
                                        <li class="flex justify-between items-center py-2 px-1 text-sm">
                                            <span class="font-medium text-slate-700" x-text="product.name"></span>
                                            <span class="font-bold text-slate-800" x-text="`${product.target.toLocaleString('id-ID')} Pcs`"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <div x-show="selectedPlan.status_produksi !== 'Selesai'">
                                <label class="font-semibold text-slate-700 mb-2 block">Pilih Status Baru:</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <template x-for="status in availableStatuses" :key="status">
                                         <label :class="{'bg-sky-600 text-white ring-2 ring-sky-500': newStatus == status, 'bg-slate-100 hover:bg-slate-200': newStatus !== status, 'cursor-not-allowed bg-slate-100 text-slate-400': isStatusDone(status)}" class="cursor-pointer p-4 rounded-lg text-center font-semibold transition-all">
                                            <input type="radio" name="status_produksi" :value="status" x-model="newStatus" class="sr-only" :disabled="isStatusDone(status)">
                                            <span x-text="status"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-slate-50 border-t flex justify-end gap-3 rounded-b-xl">
                    <button @click="isModalOpen = false" class="bg-slate-200 text-slate-700 font-semibold py-2 px-6 rounded-lg hover:bg-slate-300 transition-colors">Tutup</button>
                    <button 
                        x-show="originalStatus !== 'Selesai'" 
                        @click="updateStatus" 
                        :disabled="newStatus === originalStatus"
                        class="bg-sky-600 text-white font-bold py-2 px-6 rounded-lg transition-colors"
                        :class="{ 'hover:bg-sky-700': newStatus !== originalStatus, 'opacity-50 cursor-not-allowed': newStatus === originalStatus }"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Modal Laporan Aktual -->
    <div x-show="isReportModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" x-transition.opacity style="display: none;">
        <template x-if="selectedPlan">
            <div @click.away="isReportModalOpen = false" class="bg-white rounded-xl shadow-2xl w-full max-w-3xl transform" x-transition.scale>
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold text-slate-800" x-text="`Laporan Aktual Produksi ${selectedPlan.display_id}`"></h3>
                     <p class="text-sm text-slate-500">Input jumlah produk yang berhasil diproduksi dan yang ditolak.</p>
                </div>
                <div class="p-6 max-h-[60vh] overflow-y-auto">
                    <div class="space-y-4">
                        <template x-for="(product, index) in reportInputs" :key="product.id">
                             <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center bg-slate-50 p-3 rounded-lg border">
                                <div class="md:col-span-2">
                                    <p class="font-semibold text-slate-800" x-text="product.name"></p>
                                    <p class="text-xs text-slate-500" x-text="`Target: ${product.target.toLocaleString('id-ID')} Pcs`"></p>
                                </div>
                                <div class="md:col-span-1">
                                    <label :for="'hasil-' + product.id" class="block text-xs font-medium text-slate-600 mb-1">Berhasil</label>
                                    <input :id="'hasil-' + product.id" type="number" min="0" x-model.number="reportInputs[index].hasil_produksi" class="w-full rounded-md border-slate-300 shadow-sm text-sm">
                                </div>
                                <div class="md:col-span-1">
                                     <label :for="'reject-' + product.id" class="block text-xs font-medium text-slate-600 mb-1">Reject</label>
                                     <input :id="'reject-' + product.id" type="number" min="0" x-model.number="reportInputs[index].reject_produksi" class="w-full rounded-md border-slate-300 shadow-sm text-sm">
                                </div>
                                <div class="md:col-span-1 text-center md:text-right">
                                     <p class="text-xs text-slate-500 font-medium">Total Input</p>
                                     <p class="font-bold text-slate-800" x-text="((reportInputs[index].hasil_produksi || 0) + (reportInputs[index].reject_produksi || 0)).toLocaleString('id-ID')"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="p-6 bg-slate-50 border-t flex justify-end gap-3 rounded-b-xl">
                    <button @click="isReportModalOpen = false" class="bg-slate-200 text-slate-700 font-semibold py-2 px-6 rounded-lg hover:bg-slate-300">Batal</button>
                    <button @click="saveReport" class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-green-700">Simpan Laporan</button>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
    function productionStaff() {
        return {
            productionPlans: [
                {
                    id: 'RP001',
                    estimasi_selesai: '04 Okt 2025',
                    status: 'Dikerjakan',
                    info_ppic: 'Prioritas tinggi untuk klien A.',
                    catatan_manajer: 'Segera proses, pastikan kualitas jahitan rapi.',
                    history: [
                        { status: 'Antri', timestamp: '20 Sep 2025, 10:05' },
                        { status: 'Dikerjakan', timestamp: '20 Sep 2025, 14:30' }
                    ],
                    products: [
                        { name: 'Kemeja Lengan Panjang', sku: 'KMLP-001', target: 100, hasil_produksi: 0, reject_produksi: 0 },
                        { name: 'Kaos Polos Cotton 30s', sku: 'KPC-003', target: 250, hasil_produksi: 0, reject_produksi: 0 },
                    ]
                },
                {
                    id: 'RP002',
                    estimasi_selesai: '23 Sep 2025',
                    status: 'Selesai',
                    info_ppic: 'Kebutuhan mendesak untuk event tanggal 25.',
                    catatan_manajer: null,
                     history: [
                        { status: 'Antri', timestamp: '16 Sep 2025, 09:00' },
                        { status: 'Dikerjakan', timestamp: '17 Sep 2025, 08:15' },
                        { status: 'Selesai', timestamp: '20 Sep 2025, 16:00' }
                    ],
                    products: [ { name: 'Celana Chino Slim Fit', sku: 'CCSF-001', target: 150, hasil_produksi: 148, reject_produksi: 2 } ]
                },
                {
                    id: 'RP004',
                    estimasi_selesai: '17 Sep 2025',
                    status: 'Selesai',
                    info_ppic: 'Klien butuh cepat untuk sampel.',
                    catatan_manajer: 'OK.',
                    history: [
                        { status: 'Antri', timestamp: '12 Sep 2025, 13:00' },
                        { status: 'Dikerjakan', timestamp: '13 Sep 2025, 08:00' },
                        { status: 'Selesai', timestamp: '17 Sep 2025, 11:30' }
                    ],
                    products: [ { name: 'Polo Shirt', sku: 'PS-002', target: 200, hasil_produksi: 200, reject_produksi: 0 } ]
                },
                {
                    id: 'RP006',
                    estimasi_selesai: '30 Sep 2025',
                    status: 'Antri',
                    info_ppic: 'Produksi reguler untuk stok gudang.',
                    catatan_manajer: 'Pastikan warna sesuai dengan master sampel.',
                     history: [
                        { status: 'Antri', timestamp: '20 Sep 2025, 11:00' }
                    ],
                    products: [ 
                        { name: 'Jaket Denim', sku: 'JD-001', target: 75, hasil_produksi: 0, reject_produksi: 0 },
                        { name: 'Sweater Rajut', sku: 'SR-005', target: 120, hasil_produksi: 0, reject_produksi: 0 },
                        { name: 'Topi Kupluk', sku: 'TK-002', target: 300, hasil_produksi: 0, reject_produksi: 0 },
                    ]
                }
            ],
            isModalOpen: false,
            isReportModalOpen: false,
            selectedPlanId: null,
            newStatus: '',
            reportInputs: [],
            availableStatuses: ['Antri', 'Dikerjakan', 'Selesai'],
            
            get selectedPlan() {
                return this.productionPlans.find(p => p.id == this.selectedPlanId);
            },

            getStatusInfo(status) {
                const statuses = {
                    'Antri': { percentage: 10, badgeClass: 'bg-slate-200 text-slate-700', progressClass: 'bg-slate-400' },
                    'Dikerjakan': { percentage: 50, badgeClass: 'bg-blue-200 text-blue-700', progressClass: 'bg-blue-500' },
                    'Selesai': { percentage: 100, badgeClass: 'bg-green-200 text-green-700', progressClass: 'bg-green-500' }
                };
                return statuses[status] || statuses['Antri'];
            },
            
            isStatusDone(status) {
                if (!this.selectedPlan) return false;
                return this.selectedPlan.history.some(event => event.status == status);
            },

            openUpdateModal(planId) {
                this.selectedPlanId = planId;
                this.newStatus = this.selectedPlan.status;
                this.isModalOpen = true;
            },

            updateStatus() {
                if (!this.selectedPlanId || this.newStatus == this.selectedPlan.status) {
                    this.isModalOpen = false;
                    return;
                };

                const planIndex = this.productionPlans.findIndex(p => p.id == this.selectedPlanId);
                if (planIndex > -1) {
                    // Update status
                    this.productionPlans[planIndex].status = this.newStatus;

                    // Tambahkan ke history
                    const now = new Date();
                    const timestamp = now.toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                    this.productionPlans[planIndex].history.push({
                        status: this.newStatus,
                        timestamp: timestamp
                    });
                    
                    console.log(`Status for #${this.selectedPlanId} updated to ${this.newStatus}`);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: `Status #${this.selectedPlanId} berhasil diperbarui!`,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
                this.isModalOpen = false;
                this.selectedPlanId = null;
            },
            
            openReportModal(planId) {
                this.selectedPlanId = planId;
                this.reportInputs = JSON.parse(JSON.stringify(this.selectedPlan.products));
                this.isReportModalOpen = true;
            },

            saveReport() {
                if (!this.selectedPlanId) return;
                const planIndex = this.productionPlans.findIndex(p => p.id == this.selectedPlanId);
                if (planIndex > -1) {
                    this.productionPlans[planIndex].products = this.reportInputs;
                    console.log(`Laporan untuk #${this.selectedPlanId} berhasil disimpan.`, this.reportInputs);
                     Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: `Laporan #${this.selectedPlanId} berhasil disimpan!`,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
                this.isReportModalOpen = false;
                this.selectedPlanId = null;
                this.reportInputs = [];
            }
        }
    }
</script>
@endsection


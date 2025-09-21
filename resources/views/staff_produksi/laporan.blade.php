@extends('staff_produksi.app')

@section('title', 'Laporan Produksi')

@section('content')
<div 
    x-data="productionReport()"
    class="bg-slate-50 min-h-screen font-sans"
>
    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Panel Kontrol Pembuatan Laporan -->
        <div class="mb-8 p-6 bg-white rounded-xl shadow-md border border-slate-200" id="report-generator">
            <h1 class="text-3xl font-bold text-slate-800 mb-1">Buat Laporan Produksi</h1>
            <p class="text-slate-500 mb-6">
                Pilih jenis laporan yang ingin Anda buat dan cetak.
                <span class="block text-sm mt-2 font-medium bg-sky-50 text-sky-700 border border-sky-200 rounded-lg p-3">
                    Saat ini ada <strong x-text="completedPlans.length"></strong> rencana produksi yang telah selesai dan siap dilaporkan.
                </span>
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <!-- Pilihan Jenis Laporan -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Laporan</label>
                    <div class="flex items-center bg-slate-100 p-1 rounded-lg space-x-1">
                        <button @click="reportType = 'per_order'" :class="reportType === 'per_order' ? 'bg-white text-slate-800 shadow' : 'text-slate-600 hover:bg-slate-200'" class="w-full px-4 py-2 text-sm font-semibold rounded-md transition-colors">
                            Per Order
                        </button>
                        <button @click="reportType = 'periodik'" :class="reportType === 'periodik' ? 'bg-white text-slate-800 shadow' : 'text-slate-600 hover:bg-slate-200'" class="w-full px-4 py-2 text-sm font-semibold rounded-md transition-colors">
                            Periodik
                        </button>
                    </div>
                </div>

                <!-- Opsi Filter -->
                <div class="md:col-span-2">
                    <!-- Filter Per Order -->
                    <div x-show="reportType === 'per_order'" x-transition>
                        <label for="order_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih ID Produksi (Selesai)</label>
                        <select id="order_id" x-model="selectedPlanId" class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                            <option value="">-- Pilih ID Produksi --</option>
                            <template x-for="plan in completedPlans" :key="plan.id">
                                <option :value="plan.id" x-text="`#${plan.id} - Selesai pada ${plan.tanggal_selesai}`"></option>
                            </template>
                        </select>
                    </div>
                    <!-- Filter Periodik -->
                    <div x-show="reportType === 'periodik'" x-transition class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-slate-700 mb-2">Dari Tanggal</label>
                            <input type="date" id="start_date" x-model="startDate" class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-slate-700 mb-2">Sampai Tanggal</label>
                            <input type="date" id="end_date" x-model="endDate" class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button @click="generateReport" class="bg-sky-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-sky-700 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                    Buat Laporan
                </button>
            </div>
        </div>

        <!-- Area Pratinjau Laporan -->
        <div x-show="reportData" class="bg-white rounded-xl shadow-lg border p-2" id="report-preview" x-transition>
            <div class="p-4 flex justify-end">
                 <button @click="printReport" class="bg-slate-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-slate-800 transition-colors text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v3a2 2 0 002 2h6a2 2 0 002-2v-3h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" /></svg>
                    Cetak Laporan
                </button>
            </div>
            <div id="printableArea" class="p-8">
                <!-- Konten Laporan Per Order -->
                <template x-if="reportData && reportData.type === 'per_order'">
                    <div class="font-sans">
                        <header class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-slate-800">Laporan Hasil Produksi</h2>
                        </header>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6 border-y py-3">
                            <div class="space-y-1">
                                <p><span class="font-semibold text-slate-600 w-32 inline-block">ID Produksi:</span> <span class="font-mono" x-text="`#${reportData.plan.id}`"></span></p>
                                <p><span class="font-semibold text-slate-600 w-32 inline-block">Diinisiasi oleh:</span> <span x-text="reportData.plan.ppic_staff"></span> (PPIC)</p>
                                <p><span class="font-semibold text-slate-600 w-32 inline-block">Disetujui oleh:</span> <span x-text="reportData.plan.manajer"></span> (Manajer)</p>
                            </div>
                            <div class="space-y-1 sm:text-right">
                                <p><span class="font-semibold text-slate-600">Tanggal Selesai:</span> <span x-text="reportData.plan.tanggal_selesai"></span></p>
                                <p><span class="font-semibold text-slate-600">Estimasi Awal:</span> <span x-text="reportData.plan.estimasi_selesai"></span></p>
                                <p><span class="font-semibold text-slate-600">Tanggal Cetak:</span> <span x-text="new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })"></span></p>
                            </div>
                        </div>

                        <div class="space-y-4 mb-6">
                            <blockquote class="text-xs text-slate-700 italic border-l-4 border-slate-300 bg-slate-50 p-2 rounded-r-lg">
                                <b class="not-italic">Catatan PPIC:</b> <span x-text="reportData.plan.info_ppic || 'Tidak ada'"></span>
                            </blockquote>
                            <blockquote class="text-xs text-green-800 italic border-l-4 border-green-400 bg-green-50 p-2 rounded-r-lg">
                                <b class="not-italic">Catatan Manajer:</b> <span x-text="reportData.plan.catatan_manajer || 'Tidak ada'"></span>
                            </blockquote>
                        </div>
                        
                        <h3 class="font-semibold text-slate-700 mb-2">Rincian Hasil Produksi</h3>
                        <table class="w-full text-sm text-left border-collapse">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="p-2 border">Nama Produk</th>
                                    <th class="p-2 border text-center">Target</th>
                                    <th class="p-2 border text-center">Berhasil</th>
                                    <th class="p-2 border text-center">Reject</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="product in reportData.plan.products" :key="product.sku">
                                    <tr>
                                        <td class="p-2 border" x-text="product.name"></td>
                                        <td class="p-2 border text-center" x-text="product.target.toLocaleString('id-ID')"></td>
                                        <td class="p-2 border text-center" x-text="product.hasil_produksi.toLocaleString('id-ID')"></td>
                                        <td class="p-2 border text-center" x-text="product.reject_produksi.toLocaleString('id-ID')"></td>
                                    </tr>
                                </template>
                            </tbody>
                             <tfoot class="font-bold bg-slate-50">
                                <tr>
                                    <td class="p-2 border text-right">Total</td>
                                    <td class="p-2 border text-center" x-text="reportData.totals.target.toLocaleString('id-ID')"></td>
                                    <td class="p-2 border text-center" x-text="reportData.totals.hasil.toLocaleString('id-ID')"></td>
                                    <td class="p-2 border text-center" x-text="reportData.totals.reject.toLocaleString('id-ID')"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </template>
                <!-- Konten Laporan Periodik -->
                 <template x-if="reportData && reportData.type === 'periodik'">
                    <div class="font-sans">
                        <header class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-slate-800">Laporan Periodik Hasil Produksi</h2>
                            <p class="text-slate-600" x-text="`Periode: ${startDate} s/d ${endDate}`"></p>
                        </header>
                         <table class="w-full text-sm text-left border-collapse">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="p-2 border" colspan="2">Produk</th>
                                    <th class="p-2 border text-center">Target</th>
                                    <th class="p-2 border text-center">Berhasil</th>
                                    <th class="p-2 border text-center">Reject</th>
                                </tr>
                            </thead>
                            <template x-for="plan in reportData.plans" :key="plan.id">
                                <tbody>
                                    <tr class="bg-slate-50 font-semibold">
                                        <td class="p-2 border" colspan="5">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <span class="font-bold" x-text="`#${plan.id}`"></span>
                                                    <span class="text-slate-500 font-normal ml-2 text-xs" x-text="`(PPIC: ${plan.ppic_staff} / Manajer: ${plan.manajer})`"></span>
                                                </div>
                                                <span class="text-xs" x-text="`Selesai: ${plan.tanggal_selesai}`"></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <template x-for="product in plan.products" :key="product.sku">
                                        <tr>
                                            <td class="p-2 border pl-4" colspan="2" x-text="product.name"></td>
                                            <td class="p-2 border text-center" x-text="product.target.toLocaleString('id-ID')"></td>
                                            <td class="p-2 border text-center" x-text="product.hasil_produksi.toLocaleString('id-ID')"></td>
                                            <td class="p-2 border text-center" x-text="product.reject_produksi.toLocaleString('id-ID')"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </template>
                             <tfoot class="font-bold bg-slate-200 text-base">
                                <tr>
                                    <td colspan="2" class="p-3 border text-right">Grand Total</td>
                                    <td class="p-3 border text-center" x-text="reportData.grandTotals.target.toLocaleString('id-ID')"></td>
                                    <td class="p-3 border text-center" x-text="reportData.grandTotals.hasil.toLocaleString('id-ID')"></td>
                                    <td class="p-3 border text-center" x-text="reportData.grandTotals.reject.toLocaleString('id-ID')"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </template>
            </div>
        </div>
        
        <div x-show="errorMessage" class="mt-8 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md" x-transition>
            <p x-text="errorMessage"></p>
        </div>
    </main>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printableArea, #printableArea * {
            visibility: visible;
        }
        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>

<script>
    function productionReport() {
        return {
            // Data mentah, asumsikan ini didapat dari database
            allPlans: [
                { id: 'RP002', estimasi_selesai: '23 Sep 2025', tanggal_selesai: '2025-09-20', status_produksi: 'Selesai', ppic_staff: 'Rina Putri', manajer: 'Budi Santoso', info_ppic: 'Kebutuhan mendesak.', catatan_manajer: null, products: [ { name: 'Celana Chino Slim Fit', sku: 'CCSF-001', target: 150, hasil_produksi: 148, reject_produksi: 2 } ] },
                { id: 'RP004', estimasi_selesai: '17 Sep 2025', tanggal_selesai: '2025-09-17', status_produksi: 'Selesai', ppic_staff: 'Andi Wijaya', manajer: 'Budi Santoso', info_ppic: 'Klien butuh cepat.', catatan_manajer: 'OK.', products: [ { name: 'Polo Shirt', sku: 'PS-002', target: 200, hasil_produksi: 200, reject_produksi: 0 } ] },
                 { id: 'RP001', estimasi_selesai: '04 Okt 2025', tanggal_selesai: null, status_produksi: 'Dikerjakan', ppic_staff: 'Andi Wijaya', manajer: 'Budi Santoso', info_ppic: 'Prioritas tinggi.', catatan_manajer: 'Segera proses.', products: [ { name: 'Kemeja Lengan Panjang', sku: 'KMLP-001', target: 100, hasil_produksi: 0, reject_produksi: 0 } ] },
            ],
            reportType: 'per_order',
            selectedPlanId: '',
            startDate: '',
            endDate: '',
            reportData: null,
            errorMessage: '',

            get completedPlans() {
                return this.allPlans.filter(p => p.status_produksi === 'Selesai');
            },

            generateReport() {
                this.reportData = null;
                this.errorMessage = '';

                if (this.reportType === 'per_order') {
                    if (!this.selectedPlanId) {
                        this.errorMessage = 'Silakan pilih ID Produksi terlebih dahulu.';
                        return;
                    }
                    const plan = this.completedPlans.find(p => p.id === this.selectedPlanId);
                    const totals = plan.products.reduce((acc, p) => {
                        acc.target += p.target;
                        acc.hasil += p.hasil_produksi;
                        acc.reject += p.reject_produksi;
                        return acc;
                    }, { target: 0, hasil: 0, reject: 0 });

                    this.reportData = { type: 'per_order', plan, totals };

                } else if (this.reportType === 'periodik') {
                    if (!this.startDate || !this.endDate) {
                        this.errorMessage = 'Silakan isi kedua tanggal untuk laporan periodik.';
                        return;
                    }
                    const start = new Date(this.startDate);
                    const end = new Date(this.endDate);
                    
                    const plans = this.completedPlans.filter(p => {
                        const tglSelesai = new Date(p.tanggal_selesai);
                        return tglSelesai >= start && tglSelesai <= end;
                    });

                    if (plans.length === 0) {
                        this.errorMessage = 'Tidak ada data produksi yang selesai pada periode yang dipilih.';
                        return;
                    }

                    const grandTotals = plans.reduce((totalAcc, plan) => {
                        plan.products.forEach(p => {
                            totalAcc.target += p.target;
                            totalAcc.hasil += p.hasil_produksi;
                            totalAcc.reject += p.reject_produksi;
                        });
                        return totalAcc;
                    }, { target: 0, hasil: 0, reject: 0 });

                    this.reportData = { type: 'periodik', plans, grandTotals };
                }
            },

            printReport() {
                window.print();
            }
        }
    }
</script>
@endsection


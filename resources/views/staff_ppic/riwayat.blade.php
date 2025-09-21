@extends('staff_ppic.app')

@section('title', 'Riwayat Produksi')

@section('content')
    <div 
        x-data="{
            submissions: {{ json_encode($plans->map(function($plan) {
                return [
                    'id' => $plan->id,
                    'tanggal' => $plan->created_at->format('d M Y'),
                    'tanggal_raw' => $plan->created_at->format('Y-m-d'),
                    'deadline' => $plan->deadline ?? 0,
                    'status_pengajuan' => ucfirst(str_replace('_',' ',$plan->status)),
                    'progress_percentage' => $plan->progress,
                    'products' => $plan->products->map(fn($p) => [
                        'name' => $p->name,
                        'sku' => $p->sku ?? 'N/A',
                        'target' => $p->pivot->quantity ?? 0,
                    ]),
                    'info' => $plan->ppic_note ?? '-'
                ];
            })) }},
            openSubmissionId: null,
            startDate: '',
            endDate: '',
            expandAll: false,

            get filteredSubmissions() {
                if (!this.startDate && !this.endDate) {
                    return this.submissions;
                }
                return this.submissions.filter(submission => {
                    const submissionDate = submission.tanggal_raw;
                    const start = this.startDate ? submissionDate >= this.startDate : true;
                    const end = this.endDate ? submissionDate <= this.endDate : true;
                    return start && end;
                });
            },

            resetFilter() {
                this.startDate = '';
                this.endDate = '';
            },

            toggleAll() {
                if (this.expandAll) {
                    this.openSubmissionId = null;
                    this.expandAll = false;
                } else {
                    this.openSubmissionId = 'ALL';
                    this.expandAll = true;
                }
            },

            downloadPDF() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('landscape');

                const reportTitle = 'Laporan Riwayat Produksi';
                const filterInfo = `Periode: ${this.startDate || 'Semua'} s/d ${this.endDate || 'Semua'}`;

                // Header
                doc.setFontSize(16);
                doc.text(reportTitle, 14, 15);
                doc.setFontSize(10);
                doc.text(filterInfo, 14, 22);

                // Siapkan data tabel
                const rows = this.filteredSubmissions.map(sub => [
                    `RP${sub.id.toString().padStart(3,'0')}`,
                    sub.tanggal,
                    `${sub.deadline} hari`,
                    sub.status_pengajuan,
                    `${sub.progress_percentage}%`,
                    sub.products.map(p => `${p.name} (SKU: ${p.sku}) = ${p.target} pcs`).join('\n'),
                    sub.info
                ]);

                // Generate AutoTable
                doc.autoTable({
                    head: [['ID Rencana', 'Tanggal Dibuat', 'Deadline', 'Status', 'Progress', 'Produk', 'Info']],
                    body: rows,
                    startY: 30,
                    styles: { fontSize: 8, cellWidth: 'wrap' },
                    headStyles: { fillColor: [52, 152, 219] },
                    theme: 'striped'
                });

                // Simpan PDF
                doc.save(`laporan-produksi-${new Date().toISOString().slice(0,10)}.pdf`);
            },

            getStatusColor(status) {
                switch (status) {
                    case 'Disetujui': return 'bg-green-100 text-green-800';
                    case 'Ditolak': return 'bg-red-100 text-red-800';
                    case 'Menunggu persetujuan': return 'bg-yellow-100 text-yellow-800';
                    default: return 'bg-slate-100 text-slate-800';
                }
            }
        }">

        <main class="mx-auto px-4 sm:px-6 lg:px-4 py-2">
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

            {{-- Panel Filter & Ekspor --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label for="start_date" class="block text-xs font-medium text-slate-600 mb-1">Dari Tanggal</label>
                        <input type="date" id="start_date" x-model="startDate"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 text-sm">
                    </div>
                    <div>
                        <label for="end_date" class="block text-xs font-medium text-slate-600 mb-1">Sampai Tanggal</label>
                        <input type="date" id="end_date" x-model="endDate"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 text-sm">
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="resetFilter()"
                            class="w-full bg-slate-200 text-slate-800 font-semibold py-2 px-3 rounded-md hover:bg-slate-300 transition shadow-sm text-sm">
                            Reset
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="downloadPDF()"
                            class="w-full bg-green-600 text-white font-semibold py-2 px-3 rounded-md hover:bg-green-700 transition shadow-sm text-sm">
                            Ekspor PDF
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="toggleAll()"
                            class="w-full bg-blue-600 text-white font-semibold py-2 px-3 rounded-md hover:bg-blue-700 transition shadow-sm text-sm">
                            <span x-text="expandAll ? 'Tutup Semua' : 'Buka Semua'"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Container Riwayat --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="hidden md:grid grid-cols-12 gap-4 p-4 bg-slate-50 border-b font-semibold text-slate-600 text-sm">
                    <div class="col-span-2">ID Rencana</div>
                    <div class="col-span-3">Tanggal Dibuat</div>
                    <div class="col-span-2">Deadline</div>
                    <div class="col-span-2">Status</div>
                    <div class="col-span-2">Progress Produksi</div>
                    <div class="col-span-1 text-center">Aksi</div>
                </div>

                <div x-show="filteredSubmissions.length > 0" class="divide-y divide-slate-200">
                    <template x-for="submission in filteredSubmissions" :key="submission.id">
                        <div>
                            {{-- Row Utama --}}
                            <div class="grid grid-cols-12 gap-4 p-4 hover:bg-slate-50 cursor-pointer"
                                @click="openSubmissionId = (openSubmissionId === submission.id) ? null : submission.id">
                                <div class="col-span-12 md:col-span-2 font-semibold text-slate-800">
                                    <span x-text="`RP${submission.id.toString().padStart(3,'0')}`"></span>
                                </div>
                                <div class="col-span-6 md:col-span-3 text-slate-600">
                                    <span x-text="submission.tanggal"></span>
                                </div>
                                <div class="col-span-6 md:col-span-2 font-medium text-slate-600">
                                    <span x-text="`${submission.deadline} hari`"></span>
                                </div>
                                <div class="col-span-6 md:col-span-2">
                                    <span :class="getStatusColor(submission.status_pengajuan)" 
                                        class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-medium"
                                        x-text="submission.status_pengajuan"></span>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        class="w-5 h-5 transition-transform"
                                        :class="{ 'rotate-180': openSubmissionId === submission.id }" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Detail Produk --}}
                            <div 
                                x-show="openSubmissionId === submission.id || openSubmissionId === 'ALL'" 
                                x-transition
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

                <div x-show="filteredSubmissions.length === 0" class="text-center p-8">
                    <p class="text-slate-500">Tidak ada data yang cocok dengan kriteria filter.</p>
                </div>
            </div>
        </main>
    </div>

    {{-- Tambahkan script jsPDF dan AutoTable --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.4/jspdf.plugin.autotable.min.js"></script>
@endsection

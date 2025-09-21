@extends('manajer.app')

@section('title', 'Verifikasi Rencana Produksi')

{{-- Asumsi SweetAlert2 sudah terpasang di layout utama --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

@section('content')
<div 
    x-data="productionVerification()"
    class=" min-h-screen font-sans"
>
    <div class="mx-auto px-4 sm:px-6 lg:px-4 py-2">
        <!-- Header Halaman -->
        <header class="mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                 <div>
                    <h1 class="text-3xl font-bold text-slate-800">Verifikasi Produksi</h1>
                    <p class="mt-1 text-slate-500">Tinjau dan berikan persetujuan untuk rencana produksi yang diajukan.</p>
                </div>
                <div class="flex items-center gap-2 text-sm font-semibold text-slate-600 bg-white border rounded-full px-4 py-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    <span x-text="new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                </div>
            </div>
        </header>

        <!-- Daftar Pengajuan -->
        <div class="space-y-3">
            <template x-if="submissions.length > 0">
                <template x-for="submission in submissions" :key="submission.id">
                    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 ease-in-out hover:shadow-lg hover:border-sky-300">
                        <!-- Ringkasan Pengajuan (Area Klik) - Desain Ringkas -->
                        <div 
                            class="p-4 cursor-pointer hover:bg-slate-50"
                            @click="toggleAccordion(submission.id)"
                        >
                            <div class="flex items-center justify-between w-full">
                                <!-- Info Kiri: ID & Staff -->
                                <div>
                                    <p class="font-bold text-lg text-slate-800" x-text="`#${submission.id}`"></p>
                                    <p class="text-sm text-slate-600 font-medium" x-text="`Oleh: ${submission.ppic_staff}`"></p>
                                </div>

                                <!-- Info Kanan: Stats & Aksi -->
                                <div class="flex items-center gap-4 sm:gap-6 text-sm">
                                    <div class="text-center hidden sm:block">
                                        <p class="font-semibold text-slate-700" x-text="submission.tanggal"></p>
                                        <p class="text-xs text-slate-500">Tanggal</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="font-bold text-slate-800 text-base" x-text="submission.total_produk"></p>
                                        <p class="text-xs text-slate-500">Produk</p>
                                    </div>
                                     <div class="text-center">
                                        <p class="font-bold text-slate-800 text-base" x-text="submission.total_target.toLocaleString('id-ID')"></p>
                                        <p class="text-xs text-slate-500">Pcs</p>
                                    </div>
                                    <div class="text-center">
                                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-orange-100 text-orange-800" x-text="`${submission.deadline} Hari`"></span>
                                    </div>
                                    
                                    <!-- Icon Expand -->
                                    <div class="pl-2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform" :class="{ 'rotate-180': openSubmissionId === submission.id }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Pengajuan (Accordion Content) -->
                        <div x-show="openSubmissionId === submission.id" x-transition.origin.top class="border-t-2 border-dashed">
                            <div class="bg-slate-50 p-6 grid grid-cols-1 lg:grid-cols-5 gap-6">
                                
                                <!-- Kolom Kiri: Detail Produk & Catatan -->
                                <div class="lg:col-span-3 space-y-6">
                                    <div>
                                        <h4 class="text-md font-semibold text-slate-700 mb-2">Rincian Produk:</h4>
                                        <div class="bg-white rounded-lg border shadow-inner">
                                            <ul class="divide-y divide-slate-200 max-h-60 overflow-y-auto">
                                                <template x-for="product in submission.products" :key="product.sku">
                                                    <li class="flex items-center justify-between p-3 text-sm">
                                                        <div>
                                                            <p class="font-semibold text-slate-800" x-text="product.name"></p>
                                                            <p class="text-slate-400" x-text="`SKU: ${product.sku}`"></p>
                                                        </div>
                                                        <p class="font-bold text-slate-800 text-md" x-text="`${product.target.toLocaleString('id-ID')} Pcs`"></p>
                                                    </li>
                                                </template>
                                            </ul>
                                            <div class="bg-slate-100 p-3 font-bold text-slate-800 flex justify-between rounded-b-lg">
                                                <span>Total Target Produksi</span>
                                                <span x-text="`${submission.total_target.toLocaleString('id-ID')} Pcs`"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="text-md font-semibold text-slate-700 mb-2">Catatan dari PPIC:</h5>
                                        <blockquote class="text-sm text-slate-700 italic border-l-4 border-sky-400 bg-sky-50 p-4 rounded-r-lg">
                                            <p x-text="submission.info_ppic || 'Tidak ada catatan.'"></p>
                                        </blockquote>
                                    </div>
                                </div>

                                <!-- Kolom Kanan: Aksi Approval -->
                                <div class="lg:col-span-2">
                                    <div class="bg-white p-5 rounded-lg border-2 border-sky-200 shadow-lg h-full flex flex-col justify-between">
                                        <div>
                                            <h5 class="font-bold text-slate-800 mb-3 text-lg text-center">Formulir Keputusan</h5>
                                            <div class="mb-4">
                                                <label for="rejectionReason" class="block text-sm font-medium text-slate-600 mb-1">Alasan Penolakan (Wajib jika menolak)</label>
                                                <textarea 
                                                    :id="'rejectionReason-' + submission.id" 
                                                    x-model="rejectionReasons[submission.id]" 
                                                    rows="3" 
                                                    placeholder="Contoh: Stok bahan baku tidak mencukupi, harap revisi target."
                                                    class="w-full border-slate-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 text-sm"
                                                ></textarea>
                                            </div>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="flex gap-3">
                                                <button 
                                                    @click.stop="handleDecision(submission.id, 'reject')" 
                                                    :disabled="!rejectionReasons[submission.id]"
                                                    class="w-full flex items-center justify-center gap-2 text-white font-bold py-3 px-4 rounded-lg transition-colors disabled:bg-slate-300 disabled:cursor-not-allowed bg-red-500 hover:bg-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                    Tolak
                                                </button>
                                                <button 
                                                    @click.stop="handleDecision(submission.id, 'approve')" 
                                                    class="w-full flex items-center justify-center gap-2 bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition-colors hover:bg-green-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                    Setujui
                                                </button>
                                            </div>
                                            <p class="text-xs text-slate-500 text-center mt-2">Keputusan ini final dan akan tercatat di sistem.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            
            <!-- Kondisi Kosong -->
            <div x-show="submissions.length === 0" class="text-center py-20">
                 <div class="inline-block bg-green-100 text-green-700 p-4 rounded-full">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-1.707 1.707A1 1 0 003 15v1a1 1 0 001 1h12a1 1 0 001-1v-1a1 1 0 00-.293-.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                    </svg>
                 </div>
                <h3 class="mt-4 text-xl font-semibold text-slate-700">Tidak Ada Pengajuan Baru</h3>
                <p class="text-slate-500 mt-1">Semua rencana produksi telah berhasil ditinjau. Kerja bagus!</p>
            </div>
        </div>
    </div>
</div>

<script>
    function productionVerification() {
        return {
            submissions: [
                {
                    id: 'RP001',
                    tanggal: '20 Sep 2025',
                    ppic_staff: 'Andi Wijaya',
                    deadline: 14,
                    total_produk: 2,
                    total_target: 350,
                    products: [
                        { name: 'Kemeja Lengan Panjang', sku: 'KMLP-001', target: 100 },
                        { name: 'Kaos Polos Cotton 30s', sku: 'KPC-003', target: 250 },
                    ],
                    info_ppic: 'Prioritas tinggi untuk klien A. Mohon segera diproses untuk menjaga hubungan baik.'
                },
                {
                    id: 'RP004',
                    tanggal: '21 Sep 2025',
                    ppic_staff: 'Rina Putri',
                    deadline: 10,
                    total_produk: 1,
                    total_target: 50,
                    products: [
                        { name: 'Jaket Bomber Parasut', sku: 'JBP-004', target: 50 },
                    ],
                    info_ppic: 'Stok di gudang hampir habis, butuh produksi segera untuk menghindari kekosongan produk.'
                },
            ],
            openSubmissionId: null,
            rejectionReasons: {}, // Objek untuk menyimpan alasan penolakan per-ID

            toggleAccordion(id) {
                this.openSubmissionId = this.openSubmissionId === id ? null : id;
            },
            
            handleDecision(submissionId, decision) {
                const submission = this.submissions.find(s => s.id === submissionId);
                if (!submission) return;

                const reason = this.rejectionReasons[submissionId];

                if (decision === 'reject' && !reason) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Harap isi alasan penolakan!',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }

                const confirmationTitle = decision === 'approve' 
                    ? `Setujui pengajuan #${submissionId}?` 
                    : `Tolak pengajuan #${submissionId}?`;
                
                const confirmationText = decision === 'approve'
                    ? "Pengajuan ini akan dilanjutkan ke tahap produksi."
                    : `Alasan: "${reason}". Keputusan ini akan diberitahukan ke staff PPIC.`;

                Swal.fire({
                    title: confirmationTitle,
                    text: confirmationText,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: decision === 'approve' ? '#16a34a' : '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: `Ya, ${decision === 'approve' ? 'Setujui' : 'Tolak'}!`,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Di sini Anda akan mengirim data ke backend
                        console.log(`Pengajuan #${submissionId} ${decision}.`);
                        if(decision === 'reject') {
                            console.log('Alasan:', reason);
                        }

                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: `Keputusan untuk #${submissionId} berhasil dicatat.`,
                            showConfirmButton: false,
                            timer: 3000
                        });
                        
                        // Hapus dari daftar UI
                        this.submissions = this.submissions.filter(s => s.id !== submissionId);
                        delete this.rejectionReasons[submissionId]; // Bersihkan alasan setelah diproses
                    }
                });
            }
        }
    }
</script>
@endsection


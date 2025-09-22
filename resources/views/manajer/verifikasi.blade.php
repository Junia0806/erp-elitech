@extends('manajer.app')

@section('title', 'Approval Rencana Produksi')

@section('content')
    <div class=" mx-auto p-6 bg-white shadow-md rounded-lg" x-data="productionVerification({{ $plans }})">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-4">
                <div class="flex items-center gap-4">
                    <div class="bg-sky-100 text-sky-600 p-3 rounded-lg hidden sm:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Verifikasi Rencana Produksi</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-500">Tinjau dan verifikasi pengajuan rencana produksi
                            yang masuk dari staff PPIC.</p>
                    </div>
                </div>
                <div
                    class="flex-shrink-0 flex items-center gap-2 text-sm font-semibold text-slate-600 bg-slate-50 border rounded-full px-4 py-2 shadow-sm w-full sm:w-auto justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>


        {{-- Ganti @foreach lama Anda dengan blok kode ini --}}
        @forelse ($plans as $plan)
            <div
                class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 ease-in-out hover:shadow-lg hover:border-sky-300 mb-4">

                {{-- Header Accordion yang Informatif dan Rapi --}}
                <div class="p-4 cursor-pointer hover:bg-slate-50" @click="toggleAccordion({{ $plan->id }})">
                    <div class="flex items-center justify-between flex-wrap gap-4 w-full">
                        {{-- Info Utama (Kiri) --}}
                        <div>
                            <p class="font-bold text-lg text-slate-800">Rencana Produksi
                                #{{ str_pad($plan->id, 3, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-sm text-slate-600 font-medium">Oleh: {{ $plan->creator->name ?? 'N/A' }}</p>
                        </div>

                        {{-- Ringkasan Data (Kanan) --}}
                        <div class="flex items-center gap-4 sm:gap-6 text-sm">
                            <div class="text-center">
                                <p class="font-bold text-slate-800 text-base">{{ $plan->total_product_types }}</p>
                                <p class="text-xs text-slate-500">Produk</p>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-slate-800 text-base">
                                    {{ number_format($plan->total_quantity, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-500">Pcs</p>
                            </div>
                            <div class="text-center">
                                <span
                                    class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-orange-100 text-orange-800">{{ $plan->deadline ?? 'N/A' }}
                                    Hari</span>
                            </div>
                            <div class="pl-2 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform"
                                    :class="{ 'rotate-180': openAccordions.includes({{ $plan->id }}) }" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Konten Accordion dengan Tata Letak Dua Kolom --}}
                <div x-show="openAccordions.includes({{ $plan->id }})" x-transition.origin.top
                    class="border-t-2 border-dashed">
                    <div class="bg-slate-50/70 p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-5 gap-6">

                        {{-- Kolom Kiri: Detail Rencana --}}
                        <div class="lg:col-span-3 space-y-6">
                            <div>
                                <h4 class="text-md font-semibold text-slate-700 mb-2">Rincian Produk:</h4>
                                <div class="bg-white rounded-lg border shadow-inner">
                                    <ul class="divide-y divide-slate-200">
                                        @foreach ($plan->products as $product)
                                            <li class="flex items-center justify-between p-3 text-sm">
                                                <div>
                                                    <p class="font-semibold text-slate-800">{{ $product->name }}</p>
                                                    <p class="text-slate-400">SKU: {{ $product->sku }}</p>
                                                </div>
                                                <p class="font-bold text-slate-800 text-md">
                                                    {{ number_format($product->pivot->quantity, 0, ',', '.') }} Pcs</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div
                                        class="bg-slate-100 p-3 font-bold text-slate-800 flex justify-between rounded-b-lg text-sm">
                                        <span>Total Target Produksi</span>
                                        <span>{{ number_format($plan->total_quantity, 0, ',', '.') }} Pcs</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5 class="text-md font-semibold text-slate-700 mb-2">Catatan dari PPIC:</h5>
                                <blockquote
                                    class="text-sm text-slate-700 italic border-l-4 border-sky-400 bg-sky-50 p-4 rounded-r-lg">
                                    <p>{{ $plan->ppic_note ?: 'Tidak ada catatan.' }}</p>
                                </blockquote>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Formulir Keputusan --}}
                        <div class="lg:col-span-2">
                            <div
                                class="bg-white p-5 rounded-lg border-2 border-sky-200 shadow-lg h-full flex flex-col justify-between">
                                <div>
                                    <h5 class="font-bold text-slate-800 mb-4 text-lg text-center">Formulir Keputusan</h5>
                                    <label for="rejectionReason-{{ $plan->id }}"
                                        class="block text-sm font-medium text-slate-600 mb-1">Alasan Penolakan (Wajib jika
                                        menolak)</label>
                                    <textarea id="rejectionReason-{{ $plan->id }}" x-model="rejectionReasons[{{ $plan->id }}]" rows="4"
                                        placeholder="Contoh: Stok bahan baku tidak mencukupi, harap revisi target."
                                        class="w-full border-slate-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 text-sm placeholder-slate-400"></textarea>
                                </div>
                                <div class="mt-4 space-y-2">
                                    <div class="flex gap-3">
                                        <button @click.stop="handleDecision({{ $plan->id }}, 'reject')"
                                            :disabled="!rejectionReasons[{{ $plan->id }}]"
                                            class="w-full flex items-center justify-center gap-2 text-white font-bold py-2.5 px-4 rounded-lg transition-colors disabled:bg-slate-300 disabled:cursor-not-allowed bg-red-500 hover:bg-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Tolak
                                        </button>
                                        <button @click.stop="handleDecision({{ $plan->id }}, 'approve')"
                                            class="w-full flex items-center justify-center gap-2 bg-green-600 text-white font-bold py-2.5 px-4 rounded-lg transition-colors hover:bg-green-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Setujui
                                        </button>
                                    </div>
                                    <p class="text-xs text-slate-500 text-center pt-1">Keputusan ini final dan akan tercatat
                                        di sistem.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <div class="inline-block bg-sky-100 text-sky-700 p-4 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="mt-4 text-xl font-semibold text-slate-700">Tidak Ada Pengajuan Baru</h3>
                <p class="text-slate-500 mt-1">Semua rencana produksi telah berhasil ditinjau. Kerja bagus!</p>
            </div>
        @endforelse



        {{-- Form hidden untuk submit --}}
        <form x-ref="decisionForm" method="POST" style="display:none">
            @csrf
            <input type="hidden" name="decision" x-ref="decisionInput">
            <input type="hidden" name="notes" x-ref="decisionNotes">
        </form>
    </div>
@endsection

@push('scripts')
    {{-- SweetAlert & Alpine --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>

    <script>
        function productionVerification(plans) {
            return {
                plans,
                openAccordions: [],
                rejectionReasons: {}, // FIX: harus ada biar tidak error

                toggleAccordion(id) {
                    if (this.openAccordions.includes(id)) {
                        this.openAccordions = this.openAccordions.filter(i => i !== id);
                    } else {
                        this.openAccordions.push(id);
                    }
                },

                handleDecision(submissionId, decision) {
                    const reason = this.rejectionReasons[submissionId] || '';

                    if (decision === 'reject' && !reason.trim()) {
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

                    const confirmationTitle = decision === 'approve' ?
                        `Setujui pengajuan #${submissionId}?` :
                        `Tolak pengajuan #${submissionId}?`;

                    const confirmationText = decision === 'approve' ?
                        "Pengajuan ini akan dilanjutkan ke tahap produksi." :
                        `Alasan: "${reason}". Keputusan ini akan diberitahukan ke staff PPIC.`;

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
                            const form = this.$refs.decisionForm;
                            const route =
                                "{{ route('produksi.manager.verification.decide', ['plan' => 'PLAN_ID']) }}";
                            form.action = route.replace('PLAN_ID', submissionId);

                            this.$refs.decisionInput.value = decision;
                            this.$refs.decisionNotes.value = reason;

                            form.submit();
                        }
                    });
                }
            }
        }
    </script>
@endpush

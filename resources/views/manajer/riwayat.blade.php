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
                        Total {{ $plans->count() }} pengajuan telah diproses.
                    </p>
                </div>
                <div class="flex items-center bg-slate-100 p-1 rounded-full space-x-1">
                    <a href="{{ route('produksi.manager.history.index') }}" 
                       class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors {{ !$filterStatus ? 'bg-white text-slate-800 shadow' : 'text-slate-600 hover:bg-slate-200' }}">
                        Semua
                    </a>
                    <a href="{{ route('produksi.manager.history.index', ['status' => 'disetujui']) }}" 
                       class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors {{ $filterStatus == 'disetujui' ? 'bg-white text-green-600 shadow' : 'text-slate-600 hover:bg-slate-200' }}">
                        Disetujui
                    </a>
                    <a href="{{ route('produksi.manager.history.index', ['status' => 'ditolak']) }}" 
                       class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors {{ $filterStatus == 'ditolak' ? 'bg-white text-red-600 shadow' : 'text-slate-600 hover:bg-slate-200' }}">
                        Ditolak
                    </a>
                </div>
            </div>
        </header>

        <!-- Daftar Riwayat -->
        <div class="space-y-3">
            @forelse ($plans as $plan)
                <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 ease-in-out hover:shadow-lg relative">
                    <div class="absolute left-0 top-0 h-full w-1.5 rounded-l-xl @if($plan->status == 'disetujui') bg-green-500 @else bg-red-500 @endif"></div>
                    
                    <div 
                        class="p-4 pl-6 cursor-pointer hover:bg-slate-50/70"
                        @click="toggleAccordion({{ $plan->id }})"
                    >
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <p class="font-bold text-lg text-slate-800">#{{ $plan->id }}</p>
                                <p class="text-sm text-slate-500">Pengajuan: {{ $plan->created_at->isoFormat('D MMM YYYY') }}</p>
                            </div>
                            <div class="flex items-center gap-4 sm:gap-6 text-sm text-center">
                                <div>
                                    <p class="font-bold text-slate-700">{{ $plan->total_product_types }}</p>
                                    <p class="text-xs text-slate-500">Produk</p>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700">{{ number_format($plan->total_quantity, 0, ',', '.') }}</p>
                                    <p class="text-xs text-slate-500">Pcs</p>
                                </div>
                                <div class="text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform" :class="{ 'rotate-180': openHistoryId === {{ $plan->id }} }" fill="none" viewBox="0 0 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>
                        
                        @if ($plan->status == 'disetujui')
                            <div>
                                <div class="flex justify-between text-xs font-medium text-slate-500 mb-1">
                                    <span class="font-semibold text-green-700">Disetujui</span>
                                    <span>Estimasi Selesai: {{ $plan->estimated_completion }}</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-sky-600 h-2 rounded-full" style="width: {{ $plan->progress }}%"></div>
                                </div>
                            </div>
                        @elseif ($plan->status == 'ditolak')
                            <div class="bg-red-50 text-red-700 p-2 rounded-md border border-red-200">
                                <p class="text-sm font-semibold">Ditolak pada <span class="font-normal">{{ $plan->updated_at->isoFormat('D MMM YYYY') }}</span></p>
                            </div>
                        @endif
                    </div>

                    <!-- Detail Riwayat (Accordion Content) -->
                    <div x-show="openHistoryId === {{ $plan->id }}" x-transition.origin.top class="border-t border-slate-200">
                        <div class="bg-slate-50 p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <!-- Rincian Produk -->
                            <div>
                                <h4 class="font-semibold text-slate-700 mb-2">Rincian Produk</h4>
                                <ul class="divide-y divide-slate-200 bg-white border rounded-lg p-2 shadow-inner">
                                    @foreach ($plan->products as $product)
                                        <li class="flex justify-between items-center py-2 px-1 text-sm">
                                            <span>
                                                <span class="font-medium text-slate-700">{{ $product->name }}</span>
                                                <span class="text-slate-400"> (SKU: {{ $product->sku }})</span>
                                            </span>
                                            <span class="font-bold text-slate-800">{{ number_format($product->pivot->quantity, 0, ',', '.') }} Pcs</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            
                          
                            <!-- Catatan PPIC & Manajer -->
                            <div class="space-y-4">
                           
                                <div>
                                    <h5 class="font-semibold text-slate-700 mb-2">Catatan dari PPIC</h5>
                                    <blockquote class="text-sm text-slate-700 italic border-l-4 border-slate-400 bg-slate-100 p-3 rounded-r-lg">
                                        <p>{{ $plan->ppic_note ?: 'Tidak ada catatan dari PPIC.' }}</p>
                                    </blockquote>
                                </div>
                                
                                <div>
                                    <h5 class="font-semibold text-slate-700 mb-2">Catatan Manajer</h5>
                                    @if($plan->status == 'disetujui')
                                        <blockquote class="text-sm text-green-800 italic border-l-4 border-green-500 bg-green-50 p-3 rounded-r-lg">
                                            {{-- Jika disetujui, ambil catatan dari order produksi. --}}
                                            <p>{{ $plan->prod_note ?: 'Disetujui tanpa catatan tambahan.' }}</p>
                                        </blockquote>
                                    @else {{-- status 'ditolak' --}}
                                        <blockquote class="text-sm text-red-800 italic border-l-4 border-red-500 bg-red-50 p-3 rounded-r-lg">
                                            {{-- Jika ditolak, tampilkan alasan penolakan yang juga tersimpan di $plan->notes. --}}
                                            <p>{{ $plan->prod_note ?: 'Tidak ada alasan penolakan yang diberikan.' }}</p>
                                        </blockquote>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20">
                     <div class="inline-block bg-sky-100 text-sky-700 p-4 rounded-full">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                     </div>
                    <h3 class="mt-4 text-xl font-semibold text-slate-700">Belum Ada Riwayat</h3>
                    <p class="text-slate-500 mt-1">Riwayat pengajuan yang telah diproses akan muncul di sini.</p>
                </div>
            @endforelse
            
            @if ($plans->isEmpty() && $filterStatus)
            <div class="text-center py-20">
                   <div class="inline-block bg-yellow-100 text-yellow-700 p-4 rounded-full">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                   </div>
                <h3 class="mt-4 text-xl font-semibold text-slate-700">Tidak Ada Hasil</h3>
                <p class="text-slate-500 mt-1">Tidak ada riwayat yang cocok dengan filter '{{ $filterStatus }}'.</p>
            </div>
            @endif
        </div>
    </main>
</div>

<script>
    function productionHistory() {
        return {
            openHistoryId: null,
            toggleAccordion(id) {
                this.openHistoryId = this.openHistoryId === id ? null : id;
            },
        }
    }
</script>
@endsection


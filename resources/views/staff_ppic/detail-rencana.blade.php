@extends('staff_ppic.app')

@section('title', 'Detail Rencana Produksi')

@section('content')

    <div x-data="{
        // Data produk ditulis langsung di sini untuk keperluan slicing & testing
        items: [
            { id: 1, sku: 'KMLP-001', name: 'Kemeja Lengan Panjang', quantity: 100 },
            { id: 3, sku: 'KPC-003', name: 'Kaos Polos Cotton 30s', quantity: 250 },
            { id: 4, sku: 'JBP-004', name: 'Jaket Bomber Parasut', quantity: 75 }
        ],
        deadline: 14, // Nilai default tenggat waktu
        notes: '',
    
        // Menghitung total target produksi secara otomatis
        get totalTarget() {
            return this.items.reduce((total, item) => total + (parseInt(item.quantity) || 0), 0);
        },
    
        // Fungsi untuk menampilkan konfirmasi sebelum submit
        confirmSubmit() {
            const hasInvalidQuantity = this.items.some(item => !item.quantity || item.quantity <= 0);
            if (hasInvalidQuantity) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pastikan semua target produksi diisi dan lebih dari 0.',
                    confirmButtonColor: '#0ea5e9',
                });
                return;
            }
    
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
                    Swal.fire({
                        title: 'Terkirim!',
                        text: 'Rencana produksi Anda telah berhasil dikirim.',
                        icon: 'success',
                        confirmButtonColor: '#0ea5e9',
                    });
                }
            })
        }
    }">

        <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <header class="mb-8 border-b border-slate-200 pb-4">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Detail Rencana Produksi
                </h1>
                <p class="mt-1 text-base text-slate-500">
                    Atur jumlah target, tenggat waktu, dan informasi tambahan untuk produk yang dipilih.
                </p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-8 items-start">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white p-6 rounded-xl shadow-md border border-slate-200">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3 mb-5">
                            <span
                                class="bg-sky-100 text-sky-600 rounded-full h-8 w-8 flex items-center justify-center font-bold">1</span>
                            Tentukan Target Produksi
                        </h2>

                        <div class="space-y-4">
                            <template x-for="item in items" :key="item.id">
                                <div
                                    class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 p-3 bg-slate-50 rounded-lg border">
                                    <div class="flex-grow">
                                        <p class="font-semibold text-slate-800" x-text="item.name"></p>
                                        <p class="text-sm text-slate-500">SKU: <span x-text="item.sku"></span></p>
                                    </div>
                                    <div class="flex-shrink-0 w-full sm:w-32">
                                        <label :for="'quantity-' + item.id" class="sr-only">Target</label>
                                        <input type="number" :id="'quantity-' + item.id" x-model.number="item.quantity"
                                            min="1"
                                            class="w-full rounded-md border-slate-300 text-center font-bold bg-white py-2 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition"
                                            placeholder="Jumlah">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md border border-slate-200">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3 mb-5">
                            <span
                                class="bg-sky-100 text-sky-600 rounded-full h-8 w-8 flex items-center justify-center font-bold">2</span>
                            Atur Jadwal & Informasi
                        </h2>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="deadline" class="block text-sm font-medium text-slate-700 mb-1">Deadline
                                    Produksi</label>
                                <div class="relative">
                                    <input type="number" id="deadline" x-model.number="deadline" min="1"
                                        class="w-full rounded-md border-slate-300 pl-3 pr-12 py-2 font-semibold focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <span class="text-slate-500">Hari</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">
                                    Informasi Tambahan <span class="font-normal text-slate-400">(Opsional)</span>
                                </label>
                                <textarea id="notes" x-model="notes" rows="4"
                                    class="w-full rounded-md border-slate-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition placeholder-slate-400"
                                    placeholder="Contoh: Prioritaskan produksi kemeja..."></textarea>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:sticky lg:top-8 mt-6 lg:mt-0">
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                        <h2 class="text-lg font-bold text-slate-900 border-b pb-3 mb-4">Ringkasan Rencana</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">Total Jenis Produk</span>
                                <span class="font-bold text-slate-800" x-text="items.length"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">Total Target Produksi</span>
                                <span class="font-bold text-slate-800" x-text="totalTarget.toLocaleString('id-ID')"></span>
                            </div>
                            <div class="flex justify-between items-center pt-2 mt-2 border-t">
                                <span class="font-semibold text-sky-700">Estimasi Selesai</span>
                                <span class="font-bold text-sky-600"><span x-text="deadline"></span> Hari</span>
                            </div>
                        </div>
                        <button @click="confirmSubmit"
                            class="w-full mt-6 bg-sky-600 text-white font-bold py-3 px-5 rounded-lg hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-300 transition duration-300 flex items-center justify-center gap-2">
                            <span>Kirim Rencana Produksi</span>
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>
@endsection

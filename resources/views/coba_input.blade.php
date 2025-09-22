<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uji Coba Rencana Produksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto max-w-4xl p-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Form Uji Coba Rencana Produksi</h1>
            <p class="text-gray-600 mb-8">Gunakan form ini untuk mengirim data ke method `store`.</p>

            <!-- Menampilkan error validasi jika ada -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('ppic.checkout.store') }}" method="POST">
                @csrf

                <!-- Bagian Target Produksi -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">1. Tentukan Target Produksi</h2>
                    <p class="text-sm text-gray-500 mb-4">Masukkan ID produk yang ada di database Anda dan jumlah kuantitasnya.</p>
                    
                    <!-- Contoh Produk 1 -->
                    <div class="grid grid-cols-5 gap-4 items-center mb-3">
                        <label class="col-span-2 text-gray-700">Produk #1</label>
                        <input type="number" name="products[0][id]" placeholder="ID Produk" value="1" class="col-span-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <input type="number" name="products[0][quantity]" placeholder="Kuantitas" value="100" class="col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>

                    <!-- Contoh Produk 2 -->
                    <div class="grid grid-cols-5 gap-4 items-center mb-3">
                        <label class="col-span-2 text-gray-700">Produk #2</label>
                        <input type="number" name="products[1][id]" placeholder="ID Produk" value="2" class="col-span-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <input type="number" name="products[1][quantity]" placeholder="Kuantitas" value="250" class="col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>

                     <!-- Contoh Produk 3 -->
                     <div class="grid grid-cols-5 gap-4 items-center mb-3">
                        <label class="col-span-2 text-gray-700">Produk #3</label>
                        <input type="number" name="products[2][id]" placeholder="ID Produk" value="3" class="col-span-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <input type="number" name="products[2][quantity]" placeholder="Kuantitas" value="75" class="col-span-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </div>

                <!-- Bagian Jadwal & Informasi -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">2. Atur Jadwal & Informasi</h2>
                    <div class="mb-4">
                        <label for="deadline" class="block mb-2 text-sm font-medium text-gray-900">Deadline Produksi (dalam hari)</label>
                        <input type="number" id="deadline" name="deadline" value="14" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900">Informasi Tambahan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">butuh cepat untuk klien prioritas</textarea>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-8 py-3">
                        Kirim Rencana Produksi
                    </button>
                </div>

            </form>
        </div>
    </div>

</body>
</html>

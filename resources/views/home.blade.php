<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Peran - Sistem Produksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50">

    <div class="flex flex-col items-center justify-center min-h-screen p-6">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold text-slate-800 tracking-tight">Selamat Datang di Sistem Manajemen Produksi</h1>
            <p class="mt-3 text-lg text-slate-500 max-w-2xl mx-auto">Silakan pilih peran Anda untuk melanjutkan ke dasbor yang sesuai.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 w-full max-w-6xl">
            <!-- Kartu Staff PPIC -->
            <div class="group bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden text-center p-8 flex flex-col items-center hover:-translate-y-2 transition-transform duration-300">
                <div class="mb-6 bg-sky-100 text-sky-600 rounded-full p-4 transform group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Staff PPIC</h3>
                <p class="text-slate-500 mb-8 flex-grow">Mengajukan rencana produksi, memantau stok, dan memastikan jadwal produksi berjalan lancar.</p>
                <a href="{{ route('ppic.login.index') }}" class="w-full bg-sky-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-sky-700 transition-colors">
                    Masuk sebagai PPIC
                </a>
            </div>

            <!-- Kartu Manajer -->
            <div class="group bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden text-center p-8 flex flex-col items-center hover:-translate-y-2 transition-transform duration-300">
                <div class="mb-6 bg-green-100 text-green-600 rounded-full p-4 transform group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Manajer</h3>
                <p class="text-slate-500 mb-8 flex-grow">Memverifikasi pengajuan, memantau riwayat produksi, dan mengambil keputusan strategis.</p>
                <a href="{{ route('produksi.login.index') }}" class="w-full bg-green-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-green-700 transition-colors">
                    Masuk sebagai Manajer
                </a>
            </div>

            <!-- Kartu Staff Produksi -->
            <div class="group bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden text-center p-8 flex flex-col items-center hover:-translate-y-2 transition-transform duration-300">
                <div class="mb-6 bg-orange-100 text-orange-600 rounded-full p-4 transform group-hover:scale-110 transition-transform">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Staff Produksi</h3>
                <p class="text-slate-500 mb-8 flex-grow">Mengerjakan tugas produksi, memperbarui status pekerjaan, dan membuat laporan hasil aktual.</p>
                <a href="{{ route('produksi.login.index') }}" class="w-full bg-orange-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-orange-600 transition-colors">
                    Masuk sebagai Produksi
                </a>
            </div>

        </div>
    </div>

</body>
</html>

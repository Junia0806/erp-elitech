<h1 align="center"><b>ERP Elitech</b></h1>

<p align="center">
  Website ERP sederhana dengan 3 role utama: <br>
  <b>Staff PPIC</b> (perencana produksi), <b>Manajer</b> (validator), dan <b>Staff Produksi</b> (eksekutor).
</p>

---

## Ringkasan
- **Staff PPIC**: membuat rencana produksi dan melihat riwayat laporan.  
- **Manajer**: memvalidasi rencana (setuju/tolak).  
- **Staff Produksi**: memperbarui status produksi yang disetujui manajer dan melaporkan hasil aktual.  

---

## 🖥️ Prasyarat
Sebelum menjalankan proyek ini di lokal, pastikan laptop/PC sudah terinstal:
- [Visual Studio Code](https://code.visualstudio.com/)  
- [Laragon](https://laragon.org/) (atau XAMPP, tapi proyek ini dikembangkan dengan **Laragon**)  
- [Composer](https://getcomposer.org/)  
- [Node.js & NPM](https://nodejs.org/)  
- MySQL Database  
- PHP ≥ 8.1  
- Laravel 10  

---
## Instalasi / Cara menjalankan di lokal
1. Clone proyek
```bash
  git clone https://github.com/Junia0806/erp-elitech.git
```
2. Jalankan composer 
```bash
  composer install
```
3. Instal library menggunakan npm
```bash
  npm install
```
4. Setup database mySQL di local
5. Copy + Paste .env.example lalu rename menjadi .env
6. Generate `APP_KEY` pada file .env dengan
```bash
  php artisan key:generate
```
8. Konfigurasi `DB_DATABASE` `DB_USERNAME` `DB_PASSWORD` di file .env
9. Jalankan seeder database
```bash
  php artisan migrate:fresh --seed
```
9. Buat link storage ke public directory
```bash
  php artisan storage:link
```
10. Jalankan node runtime (di vscode)
```bash
  npm run dev
```
11. Jalankan proyek Laravel (di terminal laragon)
```bash
  php artisan serve
```

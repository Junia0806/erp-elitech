<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<p align="center"><a href="https://www.satuhati.site" target="_blank"><img src="https://raw.githubusercontent.com/CodeWithRey/satu-hati/master/public/assets/images/logo-no-text.png" width="400" alt="Satu Hati Logo"></a></p>

<h1 align="center"><b>ERP Elitech</b></h1>

<p align="center">
  Website ERP sederhana dengan 3 role utama: <br>
  <b>Staff PPIC</b> (perencana produksi), <b>Manajer</b> (validator), dan <b>Staff Produksi</b> (eksekutor).
</p>

---

## 🚀 Ringkasan
**ERP Elitech** adalah aplikasi berbasis web untuk membantu pengelolaan rencana dan laporan produksi.  
Sistem ini menyediakan alur kerja yang jelas mulai dari perencanaan, persetujuan, hingga eksekusi produksi.

---

## Instalasi / Cara menjalankan di lokal
1. Clone proyek
```bash
  git clone https://github.com/Junia0806/erp-elitech.git
```
2. Jalankan composer update
```bash
  composer update
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
10. Jalankan node runtime
```bash
  npm run dev
```
11. Jalankan proyek Laravel
```bash
  php artisan serve
```

<p align="center"><a href="#"_blank"><img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fid.linkedin.com%2Fcompany%2Felitechtechnovision&psig=AOvVaw1OljRGpVB4CI4ZZnQK9AMb&ust=1758611622386000&source=images&cd=vfe&opi=89978449&ved=0CBUQjRxqFwoTCLDRpvvo648DFQAAAAAdAAAAABAE" width="400" alt="Elitech Logo"></a></p>

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

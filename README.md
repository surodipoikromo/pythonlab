# PythonLab

PythonLab adalah aplikasi web pembelajaran **Python dasar** berbasis Laravel. Pengunjung dapat membaca materi dan langsung menjalankan kode Python di browser tanpa login, tanpa instalasi Python, dan tanpa penyimpanan progres.

## Fitur V1

- UI Bahasa Indonesia.
- Tanpa login/registrasi.
- 10 modul: Hello World, Variabel, Tipe Data, Operator, Input dan Output, Percabangan, Perulangan, List, Dictionary, dan Function.
- Materi ringkas, tujuan belajar, contoh kode, dan navigasi antar-modul.
- Lab Python interaktif di browser menggunakan Pyodide.
- Mendukung `print()` dan `input()`; `input()` ditampilkan melalui dialog browser.
- Tombol Jalankan dan Reset Kode.
- Pintasan `Ctrl+Enter` / `Cmd+Enter` untuk menjalankan kode.
- Latihan pada setiap modul dengan solusi yang dapat dibuka.
- Responsive UI dengan Bootstrap 5.
- Tidak memerlukan database.

## Arsitektur

Laravel menangani routing, Blade, dan struktur materi. Kode Python **tidak dikirim ke server**. Eksekusi berlangsung pada browser pengguna melalui Pyodide/WebAssembly.

```text
Browser
 ├─ Laravel Blade: materi & UI
 └─ Pyodide: eksekusi kode Python
```

Pendekatan ini membuat V1 sederhana untuk deployment dan menghindari risiko menjalankan kode pengguna pada server Laravel.

## Kebutuhan

- PHP 8.2+
- Composer
- Browser modern dengan WebAssembly
- Koneksi internet ketika membuka lab karena runtime Pyodide dimuat dari CDN

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Buka `http://127.0.0.1:8000`.

Tidak ada migration atau database yang perlu disiapkan.

## Menjalankan test

```bash
php artisan test
```

## Struktur materi

Semua materi V1 berada di `config/lessons.php`. Setiap modul memiliki:

- `slug`
- `title`
- `summary`
- `objectives`
- `content`
- `example`
- `starter`
- `exercise`
- `solution`

Dengan struktur ini, modul baru dapat ditambahkan tanpa membuat route atau view baru.

## Catatan Pyodide

V1 memuat build Pyodide versi terpin dari jsDelivr. Python berjalan sepenuhnya di browser. Program yang terlalu berat tetap dapat membuat tab browser lambat; materi V1 sengaja dibatasi pada Python dasar.

## Ide pengembangan berikutnya

V1 sengaja kecil. Pengembangan opsional antara lain syntax highlighting, challenge checker otomatis, pencarian materi, dark mode, dan modul tambahan. Login/progress tracking tidak menjadi bagian dari konsep utama V1.

## Live Demo

PythonLab dapat dicoba langsung melalui GitHub Pages tanpa instalasi Laravel.

**Demo:** https://surodipoikromo.github.io/pythonlab/

Versi demo berjalan sepenuhnya di browser menggunakan Pyodide, sehingga pengguna dapat membaca materi dan menjalankan kode Python langsung tanpa memerlukan server Python terpisah.

## Lisensi

MIT. Silakan gunakan sebagai bahan belajar atau portfolio.

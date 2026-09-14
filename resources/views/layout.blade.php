<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PythonLab')</title>
    <meta name="description" content="Belajar Python dasar melalui materi singkat dan lab interaktif langsung di browser.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg border-bottom bg-white sticky-top">
    <div class="container py-1">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}"><span class="brand-mark">&gt;_</span> PythonLab</a>
        <span class="navbar-text small text-secondary d-none d-sm-inline">Belajar. Coba. Pahami.</span>
    </div>
</nav>
<main>@yield('content')</main>
<footer class="border-top mt-5 py-4 bg-white">
    <div class="container small text-secondary d-flex flex-column flex-md-row justify-content-between gap-2">
        <span>PythonLab — proyek pembelajaran Python dasar berbasis Laravel.</span>
        <span>Eksekusi Python berlangsung di browser menggunakan Pyodide.</span>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

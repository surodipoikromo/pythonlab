@extends('layout')
@section('title', 'PythonLab — Belajar Python Dasar')
@section('content')
<section class="hero py-5">
    <div class="container py-lg-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="eyebrow">PYTHON DASAR • GRATIS • TANPA LOGIN</span>
                <h1 class="display-4 fw-bold mt-3 mb-3">Belajar Python sambil langsung mencoba kodenya.</h1>
                <p class="lead text-secondary mb-4">Sepuluh modul ringkas untuk memahami dasar Python. Tidak perlu akun, tidak perlu instal Python, dan tidak ada progres yang harus disimpan.</p>
                <a href="{{ route('lessons.show', $lessons[0]['slug']) }}" class="btn btn-dark btn-lg px-4">Mulai dari Modul 1</a>
            </div>
            <div class="col-lg-5">
                <div class="terminal-card shadow-sm">
                    <div class="terminal-top"><span></span><span></span><span></span></div>
                    <pre class="mb-0"><code><span class="code-blue">nama</span> = <span class="code-green">"Python"</span>
<span class="code-purple">for</span> i <span class="code-purple">in</span> range(3):
    print(<span class="code-green">f"Halo, {nama}!"</span>)

<span class="code-muted"># Jalankan langsung di browser 🚀</span></code></pre>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div><span class="eyebrow">KURIKULUM V1</span><h2 class="fw-bold mt-2 mb-0">10 modul inti</h2></div>
            <span class="text-secondary d-none d-md-inline">Dari program pertama sampai function</span>
        </div>
        <div class="row g-3">
            @foreach($lessons as $i => $lesson)
            <div class="col-md-6 col-lg-4">
                <a class="lesson-card h-100 text-decoration-none" href="{{ route('lessons.show', $lesson['slug']) }}">
                    <div class="d-flex gap-3">
                        <div class="lesson-number">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <div>
                            <div class="fs-4 mb-2">{{ $lesson['icon'] }}</div>
                            <h3 class="h5 text-dark fw-bold">{{ $lesson['title'] }}</h3>
                            <p class="text-secondary mb-0">{{ $lesson['summary'] }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
<section class="container pb-5">
    <div class="feature-strip p-4 p-lg-5">
        <div class="row g-4">
            <div class="col-md-4"><strong>Tanpa login</strong><p class="mb-0 text-secondary mt-1">Siapa pun bisa langsung belajar.</p></div>
            <div class="col-md-4"><strong>Lab interaktif</strong><p class="mb-0 text-secondary mt-1">Python berjalan di browser dengan Pyodide.</p></div>
            <div class="col-md-4"><strong>Latihan singkat</strong><p class="mb-0 text-secondary mt-1">Setiap modul memiliki latihan dan solusi.</p></div>
        </div>
    </div>
</section>
@endsection

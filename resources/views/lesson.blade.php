@extends('layout')
@section('title', $lesson['title'].' — PythonLab')
@push('head')
<script src="https://cdn.jsdelivr.net/pyodide/v314.0.6/full/pyodide.js"></script>
@endpush
@section('content')
<div class="container py-4 py-lg-5">
    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="sticky-lg-top lesson-sidebar">
                <a href="{{ route('home') }}" class="small text-decoration-none text-secondary">← Semua modul</a>
                <div class="mt-3 mb-1 small text-uppercase text-secondary fw-semibold">Modul {{ $number }} dari {{ $total }}</div>
                <div class="progress progress-thin mb-4" role="progressbar"><div class="progress-bar bg-dark" style="width: {{ ($number / $total) * 100 }}%"></div></div>
                <div class="p-3 sidebar-box">
                    <div class="fs-3 mb-2">{{ $lesson['icon'] }}</div>
                    <strong>{{ $lesson['title'] }}</strong>
                    <p class="small text-secondary mb-0 mt-2">{{ $lesson['summary'] }}</p>
                </div>
            </div>
        </aside>
        <div class="col-lg-9">
            <header class="mb-5">
                <span class="eyebrow">MODUL {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>
                <h1 class="display-5 fw-bold mt-2">{{ $lesson['title'] }}</h1>
                <p class="lead text-secondary">{{ $lesson['summary'] }}</p>
            </header>

            <section class="mb-5">
                <h2 class="h4 fw-bold mb-3">Yang akan dipelajari</h2>
                <ul class="objective-list">
                    @foreach($lesson['objectives'] as $objective)<li>{{ $objective }}</li>@endforeach
                </ul>
            </section>

            @foreach($lesson['content'] as $block)
            <section class="mb-4">
                <h2 class="h4 fw-bold">{{ $block['title'] }}</h2>
                <p class="lesson-text">{{ $block['text'] }}</p>
            </section>
            @endforeach

            <section class="mb-5">
                <div class="section-label">CONTOH</div>
                <pre class="code-block"><code>{{ $lesson['example'] }}</code></pre>
            </section>

            <section class="lab-shell mb-5" id="lab">
                <div class="lab-head d-flex flex-column flex-md-row justify-content-between gap-2 align-items-md-center">
                    <div><div class="section-label mb-1">LAB INTERAKTIF</div><strong>Coba sendiri</strong></div>
                    <div id="runtime-status" class="runtime-status">Menyiapkan Python…</div>
                </div>
                <div class="row g-0">
                    <div class="col-lg-7 lab-editor-pane">
                        <div class="pane-title">kode.py</div>
                        <textarea id="code-editor" spellcheck="false" aria-label="Editor kode Python">{{ $lesson['starter'] }}</textarea>
                        <div class="p-3 border-top d-flex flex-wrap gap-2">
                            <button id="run-btn" class="btn btn-dark" disabled>▶ Jalankan</button>
                            <button id="reset-btn" class="btn btn-outline-secondary">Reset kode</button>
                        </div>
                    </div>
                    <div class="col-lg-5 output-pane">
                        <div class="pane-title">Output</div>
                        <pre id="output" class="output-box">Python sedang dimuat…</pre>
                    </div>
                </div>
            </section>

            <section class="exercise-card mb-5">
                <div class="section-label">LATIHAN</div>
                <h2 class="h4 fw-bold mt-2">Sekarang giliran kamu</h2>
                <p class="lesson-text">{{ $lesson['exercise'] }}</p>
                <button class="btn btn-outline-dark btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#solution">Tampilkan solusi</button>
                <div class="collapse mt-3" id="solution">
                    <div class="solution-box"><div class="small fw-semibold mb-2">Salah satu solusi:</div><pre class="mb-0"><code>{{ $lesson['solution'] }}</code></pre></div>
                </div>
            </section>

            <nav class="lesson-nav d-flex justify-content-between gap-3">
                <div>@if($previous)<a class="btn btn-outline-dark" href="{{ route('lessons.show', $previous['slug']) }}">← {{ $previous['title'] }}</a>@endif</div>
                <div>@if($next)<a class="btn btn-dark" href="{{ route('lessons.show', $next['slug']) }}">{{ $next['title'] }} →</a>@else<a class="btn btn-dark" href="{{ route('home') }}">Kembali ke Semua Modul</a>@endif</div>
            </nav>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const starterCode = @json($lesson['starter']);
let pyodide = null;
const editor = document.getElementById('code-editor');
const output = document.getElementById('output');
const runBtn = document.getElementById('run-btn');
const resetBtn = document.getElementById('reset-btn');
const statusEl = document.getElementById('runtime-status');

async function bootPython() {
    try {
        pyodide = await loadPyodide({indexURL: 'https://cdn.jsdelivr.net/pyodide/v314.0.6/full/'});
        statusEl.textContent = 'Python siap';
        statusEl.classList.add('ready');
        output.textContent = 'Siap. Tekan “Jalankan” untuk menjalankan kode.';
        runBtn.disabled = false;
    } catch (error) {
        statusEl.textContent = 'Gagal memuat Python';
        output.textContent = 'Pyodide gagal dimuat. Periksa koneksi internet lalu muat ulang halaman.';
    }
}

async function executeCode() {
    if (!pyodide) return;
    runBtn.disabled = true;
    output.textContent = 'Menjalankan…';
    pyodide.globals.set('user_code', editor.value);
    try {
        const result = await pyodide.runPythonAsync(`
import io, sys, builtins, traceback
from js import prompt as _browser_prompt
_out = io.StringIO()
_old_stdout, _old_stderr, _old_input = sys.stdout, sys.stderr, builtins.input
sys.stdout = _out
sys.stderr = _out
builtins.input = lambda message='': (_browser_prompt(message) or '')
try:
    exec(user_code, {})
except BaseException:
    traceback.print_exc()
finally:
    sys.stdout, sys.stderr, builtins.input = _old_stdout, _old_stderr, _old_input
_result = _out.getvalue()
_result
        `);
        output.textContent = result || '(Program selesai tanpa output)';
    } catch (error) {
        output.textContent = String(error);
    } finally {
        runBtn.disabled = false;
    }
}

runBtn.addEventListener('click', executeCode);
resetBtn.addEventListener('click', () => { editor.value = starterCode; output.textContent = 'Kode dikembalikan ke contoh awal.'; });
editor.addEventListener('keydown', (e) => {
    if (e.key === 'Tab') {
        e.preventDefault();
        const start = editor.selectionStart, end = editor.selectionEnd;
        editor.value = editor.value.substring(0, start) + '    ' + editor.value.substring(end);
        editor.selectionStart = editor.selectionEnd = start + 4;
    }
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') { e.preventDefault(); executeCode(); }
});
bootPython();
</script>
@endpush

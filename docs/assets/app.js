let lessons = [];
let pyodide = null;
let pyodidePromise = null;

const esc = (s='') => String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
const nlEsc = (s='') => esc(s);

async function loadLessons(){
  const r = await fetch('assets/lessons.json', {cache:'no-store'});
  lessons = await r.json();
}

function home(){
  document.title = 'PythonLab — Belajar Python Dasar';
  const cards = lessons.map((l,i)=>`
    <div class="col-md-6 col-lg-4">
      <a class="lesson-card h-100 text-decoration-none" href="#/modul/${l.slug}">
        <div class="d-flex gap-3">
          <div class="lesson-number">${String(i+1).padStart(2,'0')}</div>
          <div><div class="fs-4 mb-2">${l.icon}</div><h3 class="h5 text-dark fw-bold">${esc(l.title)}</h3><p class="text-secondary mb-0">${esc(l.summary)}</p></div>
        </div>
      </a>
    </div>`).join('');

  document.querySelector('#app').innerHTML = `
  <section class="hero py-5"><div class="container py-lg-4"><div class="row align-items-center g-4">
    <div class="col-lg-7"><span class="eyebrow">PYTHON DASAR • GRATIS • TANPA LOGIN</span><h1 class="display-4 fw-bold mt-3 mb-3">Belajar Python sambil langsung mencoba kodenya.</h1><p class="lead text-secondary mb-4">Sepuluh modul ringkas untuk memahami dasar Python. Tidak perlu akun, tidak perlu instal Python, dan tidak ada progres yang harus disimpan.</p><a href="#/modul/${lessons[0].slug}" class="btn btn-dark btn-lg px-4">Mulai dari Modul 1</a></div>
    <div class="col-lg-5"><div class="terminal-card shadow-sm"><div class="terminal-top"><span></span><span></span><span></span></div><pre class="mb-0"><code><span class="code-blue">nama</span> = <span class="code-green">"Python"</span>\n<span class="code-purple">for</span> i <span class="code-purple">in</span> range(3):\n    print(<span class="code-green">f"Halo, {nama}!"</span>)\n\n<span class="code-muted"># Jalankan langsung di browser 🚀</span></code></pre></div></div>
  </div></div></section>
  <section class="py-5"><div class="container"><div class="d-flex justify-content-between align-items-end mb-4"><div><span class="eyebrow">KURIKULUM V1</span><h2 class="fw-bold mt-2 mb-0">10 modul inti</h2></div><span class="text-secondary d-none d-md-inline">Dari program pertama sampai function</span></div><div class="row g-3 module-list">${cards}</div></div></section>
  <section class="container pb-5"><div class="feature-strip p-4 p-lg-5"><div class="row g-4"><div class="col-md-4"><strong>Tanpa login</strong><p class="mb-0 text-secondary mt-1">Siapa pun bisa langsung belajar.</p></div><div class="col-md-4"><strong>Lab interaktif</strong><p class="mb-0 text-secondary mt-1">Python berjalan di browser dengan Pyodide.</p></div><div class="col-md-4"><strong>Latihan singkat</strong><p class="mb-0 text-secondary mt-1">Setiap modul memiliki latihan dan solusi.</p></div></div></div></section>`;
}

function lesson(slug){
  const idx = lessons.findIndex(x=>x.slug===slug);
  if(idx<0){ location.hash='#/'; return; }
  const l=lessons[idx], prev=lessons[idx-1], next=lessons[idx+1];
  document.title = `${l.title} — PythonLab`;
  document.querySelector('#app').innerHTML = `
  <div class="container py-4 py-lg-5"><div class="row g-4">
    <aside class="col-lg-3"><div class="sticky-lg-top lesson-sidebar"><a href="#/" class="small text-decoration-none text-secondary">← Semua modul</a><div class="mt-3 mb-1 small text-uppercase text-secondary fw-semibold">Modul ${idx+1} dari ${lessons.length}</div><div class="progress progress-thin mb-4"><div class="progress-bar bg-dark" style="width:${((idx+1)/lessons.length)*100}%"></div></div><div class="p-3 sidebar-box"><div class="fs-3 mb-2">${l.icon}</div><strong>${esc(l.title)}</strong><p class="small text-secondary mb-0 mt-2">${esc(l.summary)}</p></div></div></aside>
    <div class="col-lg-9"><header class="mb-5"><span class="eyebrow">MODUL ${String(idx+1).padStart(2,'0')}</span><h1 class="display-5 fw-bold mt-2">${esc(l.title)}</h1><p class="lead text-secondary">${esc(l.summary)}</p></header>
    <section class="mb-5"><h2 class="h4 fw-bold mb-3">Yang akan dipelajari</h2><ul class="objective-list">${l.objectives.map(x=>`<li>${esc(x)}</li>`).join('')}</ul></section>
    ${l.content.map(b=>`<section class="mb-4"><h2 class="h4 fw-bold">${esc(b.title)}</h2><p class="lesson-text">${esc(b.text)}</p></section>`).join('')}
    <section class="mb-5"><div class="section-label">CONTOH</div><pre class="code-block"><code>${nlEsc(l.example)}</code></pre></section>
    <section class="lab-shell mb-5" id="lab"><div class="lab-head d-flex flex-column flex-md-row justify-content-between gap-2 align-items-md-center"><div><div class="section-label mb-1">LAB INTERAKTIF</div><strong>Coba sendiri</strong></div><div id="runtime-status" class="runtime-status">Menyiapkan Python…</div></div><div class="row g-0"><div class="col-lg-7 lab-editor-pane"><div class="pane-title">kode.py</div><textarea id="code-editor" spellcheck="false" aria-label="Editor kode Python">${esc(l.starter)}</textarea><div class="p-3 border-top d-flex flex-wrap gap-2"><button id="run-btn" class="btn btn-dark" disabled>▶ Jalankan</button><button id="reset-btn" class="btn btn-outline-secondary">Reset kode</button></div></div><div class="col-lg-5 output-pane"><div class="pane-title">Output</div><pre id="output" class="output-box">Python sedang dimuat…</pre></div></div></section>
    <section class="exercise-card mb-5"><div class="section-label">LATIHAN</div><h2 class="h4 fw-bold mt-2">Sekarang giliran kamu</h2><p class="lesson-text">${esc(l.exercise)}</p><button class="btn btn-outline-dark btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#solution">Tampilkan solusi</button><div class="collapse mt-3" id="solution"><div class="solution-box"><div class="small fw-semibold mb-2">Salah satu solusi:</div><pre class="mb-0"><code>${nlEsc(l.solution)}</code></pre></div></div></section>
    <nav class="lesson-nav d-flex justify-content-between gap-3"><div>${prev?`<a class="btn btn-outline-dark" href="#/modul/${prev.slug}">← ${esc(prev.title)}</a>`:''}</div><div>${next?`<a class="btn btn-dark" href="#/modul/${next.slug}">${esc(next.title)} →</a>`:`<a class="btn btn-dark" href="#/">Kembali ke Semua Modul</a>`}</div></nav>
    </div></div></div>`;
  wireLab(l.starter);
}

function ensurePython(){
  if(pyodide) return Promise.resolve(pyodide);
  if(!pyodidePromise){
    pyodidePromise = loadPyodide({indexURL:'https://cdn.jsdelivr.net/pyodide/v314.0.6/full/'}).then(p=>pyodide=p);
  }
  return pyodidePromise;
}

function wireLab(starter){
  const editor=document.querySelector('#code-editor'), output=document.querySelector('#output'), runBtn=document.querySelector('#run-btn'), resetBtn=document.querySelector('#reset-btn'), status=document.querySelector('#runtime-status');
  ensurePython().then(()=>{status.textContent='Python siap'; status.classList.add('ready'); output.textContent='Siap. Tekan “Jalankan” untuk menjalankan kode.'; runBtn.disabled=false;}).catch(()=>{status.textContent='Gagal memuat Python'; output.textContent='Pyodide gagal dimuat. Periksa koneksi internet lalu muat ulang halaman.';});
  async function execute(){
    if(!pyodide) return; runBtn.disabled=true; output.textContent='Menjalankan…'; pyodide.globals.set('user_code', editor.value);
    try{
      const result=await pyodide.runPythonAsync(`
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
_result`);
      output.textContent=result || '(Program selesai tanpa output)';
    }catch(e){ output.textContent=String(e); } finally {runBtn.disabled=false;}
  }
  runBtn.addEventListener('click', execute);
  resetBtn.addEventListener('click',()=>{editor.value=starter;output.textContent='Kode dikembalikan ke contoh awal.';});
  editor.addEventListener('keydown',e=>{if(e.key==='Tab'){e.preventDefault();const s=editor.selectionStart,n=editor.selectionEnd;editor.value=editor.value.substring(0,s)+'    '+editor.value.substring(n);editor.selectionStart=editor.selectionEnd=s+4;} if((e.ctrlKey||e.metaKey)&&e.key==='Enter'){e.preventDefault();execute();}});
}

function route(){
  window.scrollTo({top:0});
  const hash=location.hash.replace(/^#\/?/,'');
  if(hash.startsWith('modul/')) lesson(decodeURIComponent(hash.slice(6))); else home();
}

(async()=>{try{await loadLessons(); addEventListener('hashchange',route); route();}catch(e){document.querySelector('#app').innerHTML='<div class="container py-5"><div class="alert alert-danger">Gagal memuat materi PythonLab.</div></div>';console.error(e);}})();

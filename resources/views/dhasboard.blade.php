@php
    $accounts = collect($users ?? [])->map(fn($u) => [
        'id' => $u->id_user, 'name' => $u->nama_lengkap, 'username' => $u->username,
        'role' => ucfirst($u->role), 'status' => $u->status_aktif ? 'Aktif' : 'Nonaktif',
    ])->values();
    $tariffs = collect($tarif ?? [])->map(fn($i) => [
        'id' => $i->id_tarif, 'jenis' => ucfirst($i->jenis_kendaraan), 'perJam' => $i->tarif_per_jam,
    ])->values();
    $areas = collect($area ?? [])->map(fn($i) => [
        'id' => $i->id_area, 'nama' => $i->nama_area, 'kapasitas' => $i->kapasitas ?? 0, 'terisi' => $i->terisi ?? 0,
    ])->values();
    $vehicles = collect($vehicles ?? [])->map(fn($i) => [
        'id' => $i->id_kendaraan, 'plat' => $i->plat_nomor, 'jenis' => ucfirst($i->jenis_kendaraan),
        'pemilik' => $i->nama_pemilik ?? $i->pemilik ?? '',
    ])->values();
    $txs = collect($transaksi ?? []);
    $transactions = $txs->map(fn($i) => [
        'id' => 'TX-' . str_pad($i->id_parkir, 4, '0', STR_PAD_LEFT), 'dbId' => $i->id_parkir,
        'plat' => $i->plat_nomor, 'jenis' => ucfirst($i->jenis_kendaraan), 'area' => $i->nama_area,
        'masukTs' => strtotime($i->waktu_masuk) * 1000,
        'keluarTs' => $i->waktu_keluar ? strtotime($i->waktu_keluar) * 1000 : null,
        'total' => $i->biaya_total ?? 0,
        'status' => in_array(strtolower($i->status ?? ''), ['keluar', 'selesai'], true) ? 'Selesai' : 'Berlangsung',
        'petugas' => $i->nama_petugas ?? 'Sistem',
    ])->values();
    $logList = collect($logs ?? [])->values();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Parkir</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
@vite('resources/css/app.css')
<style>
:root{--asphalt:#202325;--asphalt-2:#2B2F32;--yellow:#FFC93C;--yellow-dim:#B08B2A;--yellow-bg:#FFF6DF;--paper:#fff;--bg:#F5F5F2;--ink:#202325;--ink-dim:#767B7E;--line:#E4E4E2;--danger:#B4442E;--danger-bg:#FBECE8;--green:#2F7D5A;--green-bg:#E8F3ED}
*{box-sizing:border-box}html,body{margin:0;padding:0}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--ink);min-height:100vh}
button{font-family:'Inter',sans-serif}
.field{display:flex;flex-direction:column;gap:.4rem;margin-bottom:1.1rem}.field label{font-size:12px;font-weight:600}
.field input,.field select{background:#FAFAF9;border:1.5px solid var(--line);border-radius:8px;padding:.7rem .85rem;font-size:14px;color:var(--ink);outline:none;font-family:inherit;width:100%}
.field input:focus,.field select:focus{border-color:var(--yellow-dim);background:#fff}
.field input[readonly]{background:#F0F0EE}
.error-msg{font-size:12px;color:var(--danger);min-height:14px}
.btn{pointer-events:auto;background:var(--yellow);color:var(--asphalt);border:none;border-radius:8px;padding:.75rem 1rem;font-size:13px;font-weight:700;cursor:pointer;font-family:'Space Grotesk',sans-serif}
.btn:hover{background:#FFD766}.btn:disabled{opacity:.5;cursor:not-allowed}
.btn.ghost{background:transparent;color:var(--ink);border:1px solid var(--line)}.btn.ghost:hover{background:#EFEFEC}
.btn.danger{background:var(--danger);color:#fff}.btn.danger:hover{background:#9C3A26}
.app-body{display:flex;min-height:100vh}
.sidebar{width:226px;flex-shrink:0;background:var(--asphalt);color:#fff;padding:1.5rem 1rem;display:flex;flex-direction:column}
.brand{display:flex;align-items:center;gap:.6rem;padding:0 .4rem;margin-bottom:1.6rem;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px}
.brand .mark{width:28px;height:28px;background:var(--yellow);border-radius:7px;display:flex;align-items:center;justify-content:center;color:var(--asphalt);font-size:14px}
.role-badge{align-self:flex-start;font-size:11px;font-weight:700;color:var(--yellow);background:var(--asphalt-2);padding:3px 9px;border-radius:999px;margin-bottom:1.2rem}
nav{display:flex;flex-direction:column;gap:2px}
.nav-link{display:flex;align-items:center;gap:.65rem;padding:.6rem;border-radius:8px;font-size:14px;color:#B7BABC;font-weight:500;background:none;border:none;text-align:left;cursor:pointer;width:100%}
.nav-link .ico{width:18px;height:18px;flex-shrink:0;display:flex;opacity:.75}.nav-link .ico svg,.nav-link .ico img{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.nav-link:hover{background:var(--asphalt-2);color:#fff}.nav-link.active{background:var(--asphalt-2);color:var(--yellow)}.nav-link.active .ico,.nav-link:hover .ico{opacity:1}
.nav-link.logout{margin-top:10px;border-top:1px solid var(--asphalt-2);border-radius:0 0 8px 8px;padding-top:12px;color:#E7A499}
.sidebar-foot{margin-top:auto;padding-top:1rem;border-top:1px solid var(--asphalt-2)}
.who{display:flex;align-items:center;gap:.6rem;padding:0 .5rem}
.avatar{width:30px;height:30px;border-radius:50%;background:#4A4F52;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700}
.who p{margin:0;font-size:13px;font-weight:600}.who span{font-size:12px;color:#9A9E9F}
.main{flex:1;padding:1.8rem 2.2rem;min-width:0}
.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.6rem;flex-wrap:wrap;gap:10px}
.topbar h1{font-family:'Space Grotesk',sans-serif;font-size:20px;margin:0}.topbar .sub{font-size:13px;color:var(--ink-dim);margin:2px 0 0}
.topright{display:flex;align-items:center;gap:14px;flex-wrap:wrap}.user-chip{display:flex;align-items:center;gap:.6rem;background:var(--paper);border:1px solid var(--line);border-radius:999px;padding:5px 16px 5px 5px}.user-chip .avatar{background:var(--yellow);color:var(--asphalt)}.user-chip p{margin:0;font-size:13px;font-weight:600;line-height:1.25}.user-chip span{font-size:11px;color:var(--ink-dim)}
.view{display:none}.view.active{display:block;animation:fadeIn .2s ease}
@keyframes fadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:none}}
.welcome{background:var(--asphalt);color:#fff;border-radius:12px;padding:1.2rem 1.4rem;margin-bottom:1.4rem;border-left:5px solid var(--yellow)}.welcome h2{font:700 18px 'Space Grotesk',sans-serif;margin:0 0 4px}.welcome h2 span{color:var(--yellow)}.welcome p{margin:0;font-size:13px;color:#B7BABC}
.hexes{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:1.6rem}
.hex-card{background:var(--paper);border:1px solid var(--line);border-radius:14px;padding:1.3rem 1rem 1.1rem;text-align:center;min-width:0}
.hex,.hex div{clip-path:polygon(50% 0,100% 25%,100% 75%,50% 100%,0 75%,0 25%);display:flex;align-items:center;justify-content:center}
.hex{width:104px;height:120px;margin:0 auto .8rem;background:var(--yellow)}
.hex div{width:94px;height:109px;background:var(--asphalt);color:var(--yellow);font-family:'Space Grotesk',sans-serif;font-weight:700;text-align:center;line-height:1.1;padding:0 8px}
.hex-lbl{font-size:12px;color:var(--ink-dim)}
@media(max-width:980px){.hexes{grid-template-columns:repeat(2,1fr)}}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:1.6rem}
.stat-card{background:var(--paper);border:1px solid var(--line);border-radius:12px;padding:1.1rem 1.2rem}
.stat-card .label{font-size:12px;color:var(--ink-dim);margin-bottom:.5rem}.stat-card .value{font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:700}
.area-summary{margin-bottom:1.6rem}
.area-summary-head{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;margin-bottom:.9rem}
.area-summary-head h2{font-size:15px;margin:0}.area-summary-head p{font-size:12px;color:var(--ink-dim);margin:3px 0 0}.area-summary-count{font-size:12px;color:var(--ink-dim)}
.area-summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:12px}
.area-card{background:var(--paper);border:1px solid var(--line);border-radius:10px;padding:1rem}
.area-card-head{display:flex;justify-content:space-between;gap:10px;margin-bottom:.8rem}
.area-card h3{font-size:14px;margin:0}.area-card small{display:block;color:var(--ink-dim);font-size:11px;margin-top:3px}
.area-total{font-family:'Space Grotesk',sans-serif;font-size:20px;font-weight:700;white-space:nowrap}.area-total span{font:500 11px 'Inter';color:var(--ink-dim)}
.area-bar{height:7px;background:#EFEFEC;border-radius:999px;overflow:hidden;margin-bottom:.75rem}.area-bar span{display:block;height:100%;background:var(--yellow)}
.area-main{display:flex;align-items:center;gap:14px;margin-bottom:.8rem}.ring{position:relative;width:78px;height:78px;flex-shrink:0}.ring svg{width:100%;height:100%}.ring-txt{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;line-height:1.1}.ring-txt b{font:700 15px 'Space Grotesk',sans-serif}.ring-txt span{font-size:9px;color:var(--ink-dim)}.area-info{min-width:0}
.area-types{display:flex;flex-wrap:wrap;gap:5px}.area-type{background:var(--yellow-bg);color:var(--yellow-dim);border-radius:999px;padding:4px 8px;font-size:11px;font-weight:600}
.muted{color:var(--ink-dim);font-size:12px}
.panel{background:var(--paper);border:1px solid var(--line);border-radius:12px;padding:1.3rem 1.4rem}.panel+.panel{margin-top:14px}
.panel-head{margin-bottom:1.1rem}.panel-head h2{font-size:15px;margin:0}
.table-wrap{overflow-x:auto}table{width:100%;border-collapse:collapse}
th{text-align:left;font-size:12px;color:var(--ink-dim);font-weight:600;padding:0 .4rem .6rem;border-bottom:1px solid var(--line);white-space:nowrap}
td{font-size:13px;padding:.65rem .4rem;border-bottom:1px solid var(--line);white-space:nowrap}tr:last-child td{border-bottom:none}
.badge{font-size:11px;font-weight:600;padding:3px 8px;border-radius:999px}
.badge.success{background:var(--green-bg);color:var(--green)}.badge.pending{background:var(--yellow-bg);color:var(--yellow-dim)}.badge.neutral{background:#EFEFEC;color:var(--ink-dim)}
.row-actions{display:flex;gap:6px}
.icon-btn{background:none;border:1px solid var(--line);border-radius:6px;padding:4px 8px;font-size:12px;cursor:pointer;color:var(--ink)}
.icon-btn:hover{background:#F5F5F2}.icon-btn:disabled{opacity:.4;cursor:not-allowed}
.icon-btn.danger{color:var(--danger);border-color:#EBC7BC}.icon-btn.danger:hover{background:var(--danger-bg)}
.filter-row{display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap}.filter-row .field{margin-bottom:0;min-width:150px}
.modal-overlay{position:fixed;inset:0;background:rgba(32,35,37,.45);display:none;align-items:center;justify-content:center;z-index:60;padding:1rem;overflow-y:auto}
.modal-overlay.show{display:flex}
.modal{display:block!important;visibility:visible!important;opacity:1!important;position:relative!important;inset:auto!important;transform:none!important;height:auto;pointer-events:auto;margin:auto;background:var(--paper);border-radius:14px;padding:1.6rem;width:100%;max-width:420px;max-height:calc(100vh - 2rem);overflow-y:auto}
.modal h3{font-family:'Space Grotesk',sans-serif;font-size:17px;margin:0 0 1.1rem}
.modal-actions{display:flex;gap:8px;margin-top:.6rem}.modal-actions .btn{flex:1}
.receipt{background:#FFFDF5;border:1px solid #E8D89A;border-top:5px solid var(--yellow);border-radius:10px;padding:1.2rem 1.1rem;max-width:280px;margin:0 auto}
.receipt-head{text-align:center;margin-bottom:.8rem;padding-bottom:.7rem;border-bottom:1px solid #E8D89A}
.receipt-head b{font:700 17px 'Space Grotesk';display:block}.receipt-head span{font-size:11px;color:var(--yellow-dim);font-weight:600}
.receipt hr{border:none;border-top:1px dashed #D9C77E;margin:.6rem 0}
.receipt-row{display:flex;justify-content:space-between;gap:12px;font-size:12.5px;margin-bottom:5px}.receipt-row span:first-child{color:var(--ink-dim)}.receipt-row span:last-child{font-weight:600;text-align:right}
.receipt-total{display:flex;justify-content:space-between;font-size:14px;font-weight:700;margin-top:6px;padding:.65rem .7rem;border-radius:7px;background:var(--yellow-bg)}.receipt-total span:last-child{color:var(--green)}
.receipt-foot{text-align:center;font-size:11px;color:var(--ink-dim);margin-top:.8rem}
.toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--asphalt);color:#fff;padding:.7rem 1.1rem;border-radius:10px;font-size:13px;opacity:0;pointer-events:none;transition:.2s;z-index:70}
.toast.show{opacity:1;transform:translateX(-50%)}
@media print{body *{visibility:hidden}#printArea,#printArea *{visibility:visible}#printArea{position:fixed;top:0;left:0;width:100%}}
@media(max-width:980px){.stats{grid-template-columns:repeat(2,1fr)}}
@media(max-width:680px){.app-body{flex-direction:column}.sidebar{width:100%}nav{flex-direction:row;overflow-x:auto}.sidebar-foot{display:none}.main{padding:1.4rem}}
</style>
</head>
<body>
<div class="app-body">
  <aside class="sidebar">
    <div class="brand"><span class="mark">P</span>Parkir</div>
    <div class="role-badge" id="roleBadge"></div>
    <nav id="mainNav"></nav>
  </aside>
  <main class="main">
    <div class="topbar"><div><h1 id="pageTitle"></h1><p class="sub" id="pageSub"></p></div><div class="topright"><div id="topActions"></div><div class="user-chip"><div class="avatar" id="userAvatar"></div><div><p id="userNameLbl"></p><span id="userRoleLbl"></span></div></div></div></div>

    <section class="view" id="view-ringkasan">
      <div class="welcome"><h2 id="welcomeTitle"></h2><p id="welcomeSub"></p></div>
      <div class="hexes" id="ringkasanStats"></div>
      <div class="area-summary">
        <div class="area-summary-head"><div><h2>Posisi kendaraan saat ini</h2><p>Kendaraan aktif per area parkir dan jenisnya</p></div><div class="area-summary-count" id="areaSummaryCount"></div></div>
        <div class="area-summary-grid" id="areaSummaryGrid"></div>
      </div>
      <div class="panel">
        <div class="panel-head"><h2>Log aktivitas terbaru</h2><div class="muted">5 aktivitas terakhir</div></div>
        <div class="table-wrap"><table><thead><tr><th>Waktu</th><th>Pengguna</th><th>Aktivitas</th></tr></thead><tbody id="ringkasanLogTable"></tbody></table></div>
      </div>
    </section>

    <section class="view" id="view-users"><div class="panel"><div class="panel-head"><h2>Daftar akun pengguna</h2><div class="muted" id="cnt-users"></div></div><div class="table-wrap"><table id="tbl-users"></table></div></div></section>
    <section class="view" id="view-tariffs"><div class="panel"><div class="panel-head"><h2>Tarif parkir</h2><div class="muted" id="cnt-tariffs"></div></div><div class="table-wrap"><table id="tbl-tariffs"></table></div></div></section>
    <section class="view" id="view-areas"><div class="panel"><div class="panel-head"><h2>Area parkir</h2><div class="muted" id="cnt-areas"></div></div><div class="table-wrap"><table id="tbl-areas"></table></div></div></section>
    <section class="view" id="view-vehicles"><div class="panel"><div class="panel-head"><h2>Data kendaraan terdaftar</h2><div class="muted" id="cnt-vehicles"></div></div><div class="table-wrap"><table id="tbl-vehicles"></table></div></div></section>

    <section class="view" id="view-log">
      <div class="panel"><div class="panel-head"><h2>Log aktivitas sistem</h2><div class="muted" id="logCount"></div></div>
      <div class="table-wrap"><table><thead><tr><th>Waktu</th><th>Pengguna</th><th>Peran</th><th>Aktivitas</th></tr></thead><tbody id="logTable"></tbody></table></div></div>
    </section>

    <section class="view" id="view-transaksi">
      <div class="panel">
        <div class="panel-head"><h2>Kendaraan masuk</h2><div class="muted">Isi data kendaraan yang baru masuk</div></div>
        <div class="filter-row">
          <div class="field"><label>Kendaraan belum parkir</label><select id="txPlat" style="width:220px"></select></div>
          <div class="field"><label>Jenis kendaraan</label><input type="text" id="txJenis" readonly style="width:140px"></div>
          <div class="field"><label>Area parkir</label><select id="txArea" style="width:160px"></select></div>
          <button class="btn" id="btnCatatMasuk">+ Catat masuk</button>
        </div>
      </div>
      <div class="panel">
        <div class="panel-head"><h2>Transaksi berjalan &amp; selesai</h2><div class="muted" id="txCount"></div></div>
        <div class="table-wrap"><table><thead><tr><th>ID</th><th>Plat</th><th>Jenis</th><th>Area</th><th>Masuk</th><th>Keluar</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="txTable"></tbody></table></div>
      </div>
    </section>

    <section class="view" id="view-struk">
      <div class="panel"><div class="panel-head"><h2>Kendaraan keluar</h2><div class="muted">Kendaraan yang sudah keluar dan siap dicetak struknya</div></div>
      <div class="table-wrap"><table><thead><tr><th>ID</th><th>Plat</th><th>Area</th><th>Keluar</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="strukTable"></tbody></table></div></div>
    </section>

    <section class="view" id="view-rekap">
      <div class="panel"><div class="panel-head"><h2>Filter periode</h2></div>
        <div class="filter-row">
          <div class="field"><label>Dari tanggal</label><input type="text" id="rekapFrom" maxlength="10" placeholder="DD/MM/YYYY"></div>
          <div class="field"><label>Sampai tanggal</label><input type="text" id="rekapTo" maxlength="10" placeholder="DD/MM/YYYY"></div>
          <button class="btn" id="btnFilterRekap">Terapkan filter</button>
          <button class="btn ghost" id="btnResetRekap">Reset</button>
        </div>
      </div>
      <div class="stats" id="rekapStats" style="margin-top:14px"></div>
      <div class="panel"><div class="panel-head"><h2>Rincian transaksi</h2><div class="muted" id="rekapCount"></div></div>
      <div class="table-wrap"><table><thead><tr><th>ID</th><th>Plat</th><th>Area</th><th>Masuk</th><th>Keluar</th><th>Durasi</th><th>Total</th><th>Petugas</th></tr></thead><tbody id="rekapTable"></tbody></table></div></div>
    </section>
  </main>
</div>

<div class="modal-overlay" id="mForm"><div class="modal">
  <h3 id="mTitle"></h3><div id="mFields"></div><div class="error-msg" id="mErr"></div>
  <div class="modal-actions"><button class="btn ghost" data-close="mForm" type="button">Batal</button><button class="btn" id="mSave" type="button">Simpan</button></div>
</div></div>

<div class="modal-overlay" id="modalKeluar"><div class="modal">
  <h3>Catat kendaraan keluar</h3>
  <p class="muted" style="margin-top:-.6rem">Transaksi <b id="keluarId"></b> — <span id="keluarPlat"></span></p>
  <div class="field"><label>Waktu keluar (perkiraan)</label><input id="fKeluarWaktu" readonly></div>
  <div class="field"><label>Durasi (perkiraan)</label><input id="fKeluarDurasi" readonly></div>
  <div class="field"><label>Total bayar (perkiraan)</label><input id="fKeluarTotal" readonly></div>
  <p class="muted">Nilai final dihitung oleh server saat dikonfirmasi.</p>
  <div class="modal-actions"><button class="btn ghost" data-close="modalKeluar" type="button">Batal</button><button class="btn" id="saveKeluarModal" type="button">Konfirmasi keluar</button></div>
</div></div>

<div class="modal-overlay" id="modalStruk"><div class="modal">
  <div id="printArea"><div class="receipt" id="receiptContent"></div></div>
  <div class="modal-actions" style="margin-top:1.2rem"><button class="btn ghost" data-close="modalStruk" type="button">Tutup</button><button class="btn" id="printStrukBtn" type="button">🖨 Cetak</button></div>
</div></div>

<div class="modal-overlay" id="modalConfirmDelete"><div class="modal">
  <h3>Hapus data?</h3><p class="muted" id="confirmDeleteMsg" style="font-size:13px"></p>
  <div class="modal-actions"><button class="btn ghost" data-close="modalConfirmDelete" type="button">Batal</button><button class="btn danger" id="confirmDeleteBtn" type="button">Hapus</button></div>
</div></div>

<div class="toast" id="toast"></div>

<script>
const $ = id => document.getElementById(id);
const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const rupiah = n => 'Rp' + Number(n || 0).toLocaleString('id-ID');
const fmtTime = ts => { if (!ts) return '—'; const d = new Date(ts); return d.toLocaleDateString('id-ID',{day:'2-digit',month:'short'}) + ' ' + d.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}); };
const fmtDuration = ms => { const t = Math.max(1, Math.round(ms/60000)), h = Math.floor(t/60), m = t%60; return (h ? h+'j ' : '') + m + 'm'; };
const initials = n => String(n || '?').trim().split(/\s+/).filter(Boolean).map(w => w[0]).slice(0,2).join('').toUpperCase() || '?';

/* ---------- DATA (dari server) ---------- */
const accounts = @json($accounts);
const tariffs = @json($tariffs);
const areas = @json($areas);
const vehicles = @json($vehicles);
const transactions = @json($transactions);
const activityLog = @json($logList);
const sessionUser = @json($sessionUser ?? null);

let currentUser = null, cur = null, deleteHandler = null, toastTimer = null, keluarId = null;

/* ---------- HELPER UI ---------- */
function showToast(msg){ const t = $('toast'); t.textContent = msg; t.classList.add('show'); clearTimeout(toastTimer); toastTimer = setTimeout(() => t.classList.remove('show'), 2400); }
function openModal(id){ $(id).classList.add('show'); const f = $(id).querySelector('input:not([readonly]),select'); if (f) setTimeout(() => f.focus({preventScroll:true}), 50); }
function closeModal(id){ $(id).classList.remove('show'); }
document.addEventListener('click', e => { const c = e.target.closest('[data-close]'); if (c) closeModal(c.dataset.close); });
function askDelete(msg, fn){ $('confirmDeleteMsg').textContent = msg; deleteHandler = fn; openModal('modalConfirmDelete'); }
$('confirmDeleteBtn').addEventListener('click', () => { closeModal('modalConfirmDelete'); if (deleteHandler) deleteHandler(); });

/* ---------- API ---------- */
async function api(url, method, payload, msg, view){
  const opt = { method, headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } };
  if (method !== 'DELETE') { opt.headers['Content-Type'] = 'application/json'; opt.body = JSON.stringify(payload || {}); }
  const res = await fetch(url, opt);
  if (!res.ok) {
    const r = await res.json().catch(() => ({}));
    throw new Error((r.errors && Object.values(r.errors).flat()[0]) || r.message || 'Permintaan gagal.');
  }
  showToast(msg);
  sessionStorage.setItem('dashboardActiveView', view || document.querySelector('.view.active')?.id.replace('view-','') || 'ringkasan');
  setTimeout(() => location.reload(), 400);
}

/* ---------- KONFIGURASI CRUD (admin) ---------- */
const JENIS = ['Mobil','Motor','Becak','Truk','Bus','Sepeda','Lainnya'];
const E = {
  users: { url:'/dashboard/users', data:accounts, title:'akun', add:'+ Tambah user',
    cols:[['name','Nama'],['username','Username'],['role','Peran'],['status','Status',v=>`<span class="badge ${v==='Aktif'?'success':'neutral'}">${esc(v)}</span>`]],
    fields:[['name','Nama lengkap','text'],['username','Username','text'],['password','Password','password'],['role','Peran','select',['Admin','Petugas','Owner']],['status','Status','select',['Aktif','Nonaktif']]],
    label:x=>x.name,
    check:(v,x,isNew)=>{ if(!v.name||!v.username) return 'Nama dan username wajib diisi.';
      if((isNew||v.password)&&v.password.length<6) return 'Password minimal 6 karakter.';
      if(accounts.some(a=>a.username===v.username&&a.id!==x.id)) return 'Username sudah dipakai.'; },
    body:v=>({nama_lengkap:v.name,username:v.username,...(v.password?{password:v.password}:{}),role:v.role.toLowerCase(),status_aktif:v.status==='Aktif'}) },
  tariffs: { url:'/dashboard/tariffs', data:tariffs, title:'tarif', add:'+ Tambah tarif',
    cols:[['jenis','Jenis kendaraan'],['perJam','Tarif/jam',rupiah]],
    fields:[['jenis','Jenis kendaraan','select',JENIS],['perJam','Tarif per jam (Rp)','number']],
    label:x=>x.jenis,
    check:(v,x)=>{ const n=Number(v.perJam); if(!Number.isInteger(n)||n<=0) return 'Tarif per jam harus angka bulat lebih dari 0.';
      if(tariffs.some(t=>t.jenis.toLowerCase()===v.jenis.toLowerCase()&&t.id!==x.id)) return 'Jenis kendaraan tersebut sudah memiliki tarif.'; },
    body:v=>({jenis_kendaraan:v.jenis.toLowerCase(),tarif_per_jam:Number(v.perJam)}) },
  areas: { url:'/dashboard/areas', data:areas, title:'area', add:'+ Tambah area',
    cols:[['nama','Nama area'],['kapasitas','Kapasitas'],['terisi','Terisi',(v,a)=>`${a.terisi}/${a.kapasitas}`]],
    fields:[['nama','Nama area','text'],['kapasitas','Kapasitas','number']],
    label:x=>x.nama,
    check:v=>{ if(!v.nama||!(parseInt(v.kapasitas)>0)) return 'Nama area dan kapasitas wajib diisi dengan benar.'; },
    body:v=>({nama_area:v.nama,kapasitas:parseInt(v.kapasitas)}) },
  vehicles: { url:'/dashboard/vehicles', data:vehicles, title:'kendaraan', add:'+ Tambah kendaraan',
    cols:[['plat','Plat nomor'],['jenis','Jenis'],['pemilik','Pemilik']],
    fields:[['plat','Plat nomor','text'],['jenis','Jenis','text'],['pemilik','Pemilik','text']],
    label:x=>x.plat,
    check:v=>{ if(!v.plat||!v.jenis||!v.pemilik) return 'Plat, jenis, dan pemilik wajib diisi.'; },
    body:v=>({plat_nomor:v.plat,jenis_kendaraan:v.jenis.toLowerCase(),nama_pemilik:v.pemilik}) },
};

function renderEntity(k){
  const c = E[k];
  $('cnt-'+k).textContent = c.data.length + ' data terdaftar';
  $('tbl-'+k).innerHTML = '<thead><tr>' + c.cols.map(x=>`<th>${x[1]}</th>`).join('') + '<th>Aksi</th></tr></thead><tbody>' +
    (c.data.map(r => '<tr>' + c.cols.map(([f,,fn]) => `<td>${fn ? fn(r[f], r) : esc(r[f])}</td>`).join('') +
      `<td class="row-actions"><button class="icon-btn" data-edit="${k}:${r.id}">Edit</button><button class="icon-btn danger" data-del="${k}:${r.id}">Hapus</button></td></tr>`).join('')
      || `<tr><td colspan="${c.cols.length+1}" class="muted">Belum ada data.</td></tr>`) + '</tbody>';
}

function openForm(k, id){
  const c = E[k], it = id ? c.data.find(x => x.id === id) : {};
  cur = { k, id };
  $('mTitle').textContent = (id ? 'Edit ' : 'Tambah ') + c.title;
  $('mFields').innerHTML = c.fields.map(([n,l,t,o]) => `<div class="field"><label>${l}</label>` + (t==='select'
    ? `<select data-f="${n}">${o.map(x=>`<option${String(it[n]).toLowerCase()===x.toLowerCase()?' selected':''}>${x}</option>`).join('')}</select>`
    : `<input data-f="${n}" type="${t}" value="${t==='password'?'':esc(it[n] ?? '')}" ${t==='password'?`placeholder="${id?'Kosongkan jika tidak diubah':'Minimal 6 karakter'}" autocomplete="new-password"`:''}>`) + '</div>').join('');
  $('mErr').textContent = '';
  openModal('mForm');
}
$('mSave').addEventListener('click', async () => {
  const c = E[cur.k], it = cur.id ? c.data.find(x => x.id === cur.id) : {};
  const v = {}; document.querySelectorAll('#mFields [data-f]').forEach(el => v[el.dataset.f] = el.value.trim());
  const err = c.check(v, it, !cur.id);
  if (err) { $('mErr').textContent = err; return; }
  $('mSave').disabled = true;
  try {
    await api(cur.id ? `${c.url}/${cur.id}` : c.url, cur.id ? 'PUT' : 'POST', c.body(v), cur.id ? 'Data diperbarui' : 'Data ditambahkan');
  } catch (e) { $('mErr').textContent = e.message; $('mSave').disabled = false; }
});
document.addEventListener('click', e => {
  const ed = e.target.closest('[data-edit]'), dl = e.target.closest('[data-del]');
  if (ed) { const [k,id] = ed.dataset.edit.split(':'); openForm(k, Number(id)); }
  if (dl) {
    const [k,idS] = dl.dataset.del.split(':'), id = Number(idS), c = E[k], it = c.data.find(x => x.id === id);
    if (k === 'users' && it.username === currentUser.username) { showToast('Tidak bisa menghapus akun yang sedang dipakai.'); return; }
    askDelete(`Hapus ${c.title} "${c.label(it)}"?`, () => api(`${c.url}/${id}`, 'DELETE', null, 'Data dihapus').catch(err => showToast(err.message)));
  }
});

/* ---------- NAVIGASI ---------- */
const navByRole = {
  Admin: [['ringkasan','Ringkasan'],['users','Kelola User'],['tariffs','Tarif Parkir'],['areas','Area Parkir'],['vehicles','Kendaraan'],['log','Log Aktivitas'],['logout','Keluar']],
  Petugas: [['ringkasan','Ringkasan'],['struk','Kendaraan Keluar'],['transaksi','Kendaraan Masuk'],['logout','Keluar']],
  Owner: [['ringkasan','Ringkasan'],['rekap','Rekap Transaksi'],['logout','Keluar']],
};
const pageTitles = {
  ringkasan:['Ringkasan','Ikhtisar aktivitas sistem'], users:['Kelola User','Kelola akun admin, petugas, dan owner'],
  tariffs:['Tarif Parkir','Atur tarif per jenis kendaraan'], areas:['Area Parkir','Kelola area dan kapasitas parkir'],
  vehicles:['Kendaraan','Data kendaraan yang terdaftar'], log:['Log Aktivitas','Riwayat semua aktivitas di sistem'],
  transaksi:['Kendaraan Masuk','Catat kendaraan yang masuk area parkir'], struk:['Kendaraan Keluar','Lihat kendaraan keluar dan cetak struk'],
  rekap:['Rekap Transaksi','Rekap transaksi berdasarkan periode'],
};

const ICONS = {
  ringkasan:'<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
  users:'<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
  tariffs:'<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
  areas:'<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
  vehicles:'<path d="M5 17H3v-5l2-5h14l2 5v5h-2"/><path d="M9 17h6"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/>',
  log:'<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/>',
  transaksi:'<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>',
  struk:'<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>',
  rekap:'<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
  logout:'<path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/>',
};
const icon = id => `<span class="ico"><svg viewBox="0 0 24 24" aria-hidden="true">${ICONS[id] || ''}</svg></span>`;

function goToView(name){
  if (name === 'logout') { sessionStorage.removeItem('welcomed'); location.href = '/logout'; return; }
  if (!pageTitles[name]) name = 'ringkasan';
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  $('view-'+name).classList.add('active');
  document.querySelectorAll('.nav-link').forEach(b => b.classList.toggle('active', b.dataset.view === name));
  $('pageTitle').textContent = pageTitles[name][0];
  $('pageSub').textContent = pageTitles[name][1];
  $('topActions').innerHTML = E[name] ? `<button class="btn" data-add="${name}">${E[name].add}</button>` : '';
}
$('topActions').addEventListener('click', e => { const b = e.target.closest('[data-add]'); if (b) openForm(b.dataset.add); });
$('mainNav').addEventListener('click', e => { const b = e.target.closest('.nav-link'); if (b) goToView(b.dataset.view); });

/* ---------- RINGKASAN ---------- */
function renderRingkasan(){
  const selesai = transactions.filter(t => t.status === 'Selesai'), aktif = transactions.filter(t => t.status === 'Berlangsung');
  const pendapatan = selesai.reduce((a,t) => a + Number(t.total || 0), 0);
  const cards = {
    Admin: [['Total akun',accounts.length],['Total area',areas.length],['Kendaraan terdaftar',vehicles.length],['Aktivitas tercatat',activityLog.length]],
    Petugas: [['Sedang parkir',aktif.length],['Transaksi selesai',selesai.length],['Pendapatan tercatat',pendapatan,1],['Area tersedia',areas.length]],
    Owner: [['Total transaksi',transactions.length],['Transaksi selesai',selesai.length],['Total pendapatan',pendapatan,1],['Area aktif',areas.length]],
  }[currentUser.role];
  const fs = t => t.length <= 3 ? 30 : t.length <= 5 ? 26 : t.length <= 8 ? 17 : 13;
  $('ringkasanStats').innerHTML = cards.map(([l,v,money]) => { const t = String(money ? rupiah(v) : v); return `<div class="hex-card"><div class="hex"><div style="font-size:${fs(t)}px">${t}</div></div><div class="hex-lbl">${l}</div></div>`; }).join('');

  $('areaSummaryCount').textContent = aktif.length + ' kendaraan sedang parkir';
  $('areaSummaryGrid').innerHTML = areas.map(a => {
    const cap = Number(a.kapasitas) || 0, occ = Number(a.terisi) || 0, pct = cap ? Math.min(100, Math.round(occ/cap*100)) : 0;
    const counts = {}; aktif.filter(t => t.area === a.nama).forEach(t => counts[t.jenis || 'Lainnya'] = (counts[t.jenis || 'Lainnya'] || 0) + 1);
    const types = Object.entries(counts).map(([t,n]) => `<span class="area-type">${esc(t)} ${n}</span>`).join('');
    const C = 2 * Math.PI * 34, off = C * (1 - pct / 100), col = pct >= 90 ? 'var(--danger)' : 'var(--yellow)';
    return `<article class="area-card"><div class="area-main">
      <div class="ring" role="img" aria-label="${pct}% terisi"><svg viewBox="0 0 80 80"><circle cx="40" cy="40" r="34" fill="none" stroke="#EFEFEC" stroke-width="9"/>
        <circle cx="40" cy="40" r="34" fill="none" stroke="${col}" stroke-width="9" stroke-linecap="round" stroke-dasharray="${C.toFixed(1)}" stroke-dashoffset="${off.toFixed(1)}" transform="rotate(-90 40 40)" style="transition:stroke-dashoffset .4s ease"/></svg>
        <div class="ring-txt"><b>${pct}%</b><span>terisi</span></div></div>
      <div class="area-info"><h3>${esc(a.nama)}</h3><small>${occ} dari ${cap} terisi</small>
        <div class="area-total">${Math.max(0,cap-occ)} <span>slot tersisa</span></div></div></div>
      <div class="area-types">${types || '<span class="muted">Belum ada kendaraan</span>'}</div></article>`;
  }).join('') || '<div class="panel muted">Belum ada area parkir yang terdaftar.</div>';

  $('ringkasanLogTable').innerHTML = activityLog.slice(0,5).map(l => `<tr><td>${fmtTime(l.ts)}</td><td>${esc(l.user)}</td><td>${esc(l.aksi)}</td></tr>`).join('') || '<tr><td colspan="3" class="muted">Belum ada aktivitas.</td></tr>';
}

function renderLog(){
  $('logCount').textContent = activityLog.length + ' entri tercatat';
  $('logTable').innerHTML = activityLog.map(l => `<tr><td>${fmtTime(l.ts)}</td><td>${esc(l.user)}</td><td>${esc(l.role)}</td><td>${esc(l.aksi)}</td></tr>`).join('') || '<tr><td colspan="4" class="muted">Belum ada aktivitas.</td></tr>';
}

/* ---------- TRANSAKSI (petugas) ---------- */
function fillSelects(){
  const p = $('txPlat'), j = $('txJenis'), a = $('txArea'), pv = p.value, av = a.value;
  const kendaraanAktif = new Set(transactions.filter(t => t.status === 'Berlangsung').map(t => t.plat));
  const kendaraanTersedia = vehicles.filter(v => !kendaraanAktif.has(v.plat));
  p.innerHTML = kendaraanTersedia.map(v => `<option value="${esc(v.plat)}">${esc(v.plat)} · ${esc(v.jenis)}</option>`).join('')
    || `<option value="">${vehicles.length ? 'Semua kendaraan sedang parkir' : 'Belum ada kendaraan terdaftar'}</option>`;
  if (kendaraanTersedia.some(v => v.plat === pv)) p.value = pv;
  j.value = kendaraanTersedia.find(v => v.plat === p.value)?.jenis || '';
  a.innerHTML = areas.map(x => `<option value="${x.id}">${esc(x.nama)}</option>`).join('');
  if (av) a.value = av;
}
 $('txPlat').addEventListener('change', () => {
  const vehicle = vehicles.find(v => v.plat === $('txPlat').value);
  $('txJenis').value = vehicle?.jenis || '';
});
function renderTransaksi(){
  fillSelects();
  $('txCount').textContent = transactions.length + ' transaksi tercatat';
  $('txTable').innerHTML = transactions.slice().reverse().map(t => {
    const done = t.status === 'Selesai';
    return `<tr><td>${t.id}</td><td>${esc(t.plat)}</td><td>${esc(t.jenis)}</td><td>${esc(t.area)}</td><td>${fmtTime(t.masukTs)}</td><td>${fmtTime(t.keluarTs)}</td>
      <td>${done ? rupiah(t.total) : '—'}</td><td><span class="badge ${done?'success':'pending'}">${t.status}</span></td>
      <td>${done ? `<button class="icon-btn" data-struk="${t.id}">Cetak struk</button>` : `<button class="icon-btn" data-keluar="${t.id}">Catat keluar</button>`}</td></tr>`;
  }).join('') || '<tr><td colspan="9" class="muted">Belum ada transaksi. Catat kendaraan masuk terlebih dahulu.</td></tr>';
}
$('btnCatatMasuk').addEventListener('click', async () => {
  const plat = $('txPlat').value.trim(), jenis = $('txJenis').value, area = $('txArea').value;
  if (!plat) return showToast(vehicles.length ? 'Semua kendaraan sudah tercatat masuk.' : 'Tambahkan kendaraan melalui menu Kendaraan terlebih dahulu.');
  if (!jenis || !area) return showToast('Jenis kendaraan dan area parkir belum tersedia (atur di menu Admin)');
  try { await api('/dashboard/transactions', 'POST', { plat_nomor: plat, id_area: Number(area) }, `Kendaraan ${plat} tercatat masuk`, 'transaksi'); }
  catch (e) { showToast(e.message); }
});
function openKeluarModal(id){
  keluarId = id;
  const t = transactions.find(x => x.id === id), tr = tariffs.find(x => x.jenis === t.jenis) || { perJam: 3000 };
  const now = Date.now(), dur = now - t.masukTs, jam = Math.max(1, Math.ceil(dur/3600000));
  $('keluarId').textContent = t.id; $('keluarPlat').textContent = t.plat + ' · ' + t.area;
  $('fKeluarWaktu').value = fmtTime(now); $('fKeluarDurasi').value = fmtDuration(dur); $('fKeluarTotal').value = rupiah(jam * tr.perJam);
  openModal('modalKeluar');
}
$('saveKeluarModal').addEventListener('click', async () => {
  const t = transactions.find(x => x.id === keluarId);
  if (!t?.dbId) return showToast('Transaksi ini belum terhubung ke database.');
  try { await api(`/dashboard/transactions/${t.dbId}/close`, 'POST', {}, `Transaksi ${t.id} selesai`, 'transaksi'); }
  catch (e) { showToast(e.message); }
});

/* ---------- STRUK ---------- */
function renderStruk(){
  $('strukTable').innerHTML = transactions.slice().reverse().map(t => {
    const d = t.status === 'Selesai';
    return `<tr><td>${t.id}</td><td>${esc(t.plat)}</td><td>${esc(t.area)}</td><td>${d ? fmtTime(t.keluarTs) : '—'}</td><td>${d ? rupiah(t.total) : '—'}</td>
      <td><span class="badge ${d?'success':'pending'}">${d ? 'Selesai' : 'Menunggu keluar'}</span></td>
      <td><button class="icon-btn" ${d ? `data-struk="${t.id}"` : 'disabled title="Catat kendaraan keluar dulu di menu Kendaraan Masuk"'}>🖨 Cetak</button></td></tr>`;
  }).join('') || '<tr><td colspan="7" class="muted">Belum ada transaksi.</td></tr>';
}
function printStruk(id){
  const t = transactions.find(x => x.id === id), row = (a,b) => `<div class="receipt-row"><span>${a}</span><span>${esc(b)}</span></div>`;
  $('receiptContent').innerHTML = `<div class="receipt-head"><b>Parkir</b><span>${esc(t.area)}</span></div><hr>` +
    row('No. Transaksi',t.id) + row('Plat nomor',t.plat) + row('Jenis',t.jenis) + row('Masuk',fmtTime(t.masukTs)) + row('Keluar',fmtTime(t.keluarTs)) +
    row('Durasi',fmtDuration(t.keluarTs - t.masukTs)) + row('Petugas',t.petugas) +
    `<hr><div class="receipt-total"><span>Total</span><span>${rupiah(t.total)}</span></div><div class="receipt-foot">Terima kasih — simpan struk ini<br>sebagai bukti pembayaran.</div>`;
  openModal('modalStruk');
}
$('printStrukBtn').addEventListener('click', () => window.print());
document.addEventListener('click', e => {
  const s = e.target.closest('[data-struk]'), k = e.target.closest('[data-keluar]');
  if (s) printStruk(s.dataset.struk);
  if (k) openKeluarModal(k.dataset.keluar);
});

/* ---------- REKAP (owner) ---------- */
function parseDate(v, end){
  const m = v.trim().match(/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/);
  if (!m) return NaN;
  const d = +m[1], mo = +m[2], y = +m[3], dt = new Date(y, mo-1, d, end?23:0, end?59:0, end?59:0);
  return (dt.getFullYear() === y && dt.getMonth() === mo-1 && dt.getDate() === d) ? dt.getTime() : NaN;
}
function renderRekap(){
  const fv = $('rekapFrom').value.trim(), tv = $('rekapTo').value.trim();
  let rows = transactions.filter(t => t.status === 'Selesai');
  if (fv) { const f = parseDate(fv, false); if (isNaN(f)) return showToast('Format tanggal "Dari" salah (DD/MM/YYYY)'); rows = rows.filter(t => t.masukTs >= f); }
  if (tv) { const t2 = parseDate(tv, true); if (isNaN(t2)) return showToast('Format tanggal "Sampai" salah (DD/MM/YYYY)'); rows = rows.filter(t => t.masukTs <= t2); }
  const total = rows.reduce((a,t) => a + Number(t.total || 0), 0), avg = rows.length ? Math.round(total/rows.length) : 0;
  $('rekapStats').innerHTML = [['Total transaksi',rows.length],['Total pendapatan',rupiah(total)],['Rata-rata / transaksi',rupiah(avg)],['Periode',`${esc(fv)||'Semua'} s/d ${esc(tv)||'Semua'}`]]
    .map(([l,v],i) => `<div class="stat-card"><div class="label">${l}</div><div class="value" ${i===3?'style="font-size:14px"':''}>${v}</div></div>`).join('');
  $('rekapCount').textContent = rows.length + ' transaksi ditemukan';
  $('rekapTable').innerHTML = rows.slice().reverse().map(t => `<tr><td>${t.id}</td><td>${esc(t.plat)}</td><td>${esc(t.area)}</td><td>${fmtTime(t.masukTs)}</td><td>${fmtTime(t.keluarTs)}</td><td>${fmtDuration(t.keluarTs - t.masukTs)}</td><td>${rupiah(t.total)}</td><td>${esc(t.petugas)}</td></tr>`).join('') || '<tr><td colspan="8" class="muted">Tidak ada transaksi pada periode ini.</td></tr>';
}
$('btnFilterRekap').addEventListener('click', renderRekap);
$('btnResetRekap').addEventListener('click', () => { $('rekapFrom').value = ''; $('rekapTo').value = ''; renderRekap(); });

/* ---------- START ---------- */
function renderAll(){
  renderRingkasan();
  if (currentUser.role === 'Admin') { Object.keys(E).forEach(renderEntity); renderLog(); }
  if (currentUser.role === 'Petugas') { renderTransaksi(); renderStruk(); }
  if (currentUser.role === 'Owner') renderRekap();
}
const WELCOME = {
  Admin: ['Selamat datang, Admin','Kelola user, tarif, area parkir, dan data kendaraan dari sini.'],
  Petugas: ['Selamat datang, Petugas','Catat kendaraan masuk dan keluar, lalu cetak struk.'],
  Owner: ['Selamat datang, Owner','Pantau rekap transaksi dan pendapatan parkir.'],
};
function showWelcome(){
  const w = WELCOME[currentUser.role];
  $('welcomeTitle').innerHTML = w[0].replace(currentUser.role, '<span>' + currentUser.role + '</span>') + ' 👋';
  $('welcomeSub').textContent = currentUser.name + ' — ' + w[1];
  if (!sessionStorage.getItem('welcomed')) { sessionStorage.setItem('welcomed', '1'); showToast('Selamat datang, ' + currentUser.name + ' (' + currentUser.role + ')'); }
}
function enterApp(){
  $('roleBadge').textContent = currentUser.role.toUpperCase();
  $('userNameLbl').textContent = currentUser.name;
  $('userRoleLbl').textContent = currentUser.role;
  $('userAvatar').textContent = initials(currentUser.name);
  $('mainNav').innerHTML = navByRole[currentUser.role].map(([id,l]) => `<button class="nav-link${id==='logout'?' logout':''}" data-view="${id}">${icon(id)}${l}</button>`).join('');
  renderAll();
  showWelcome();
  const saved = sessionStorage.getItem('dashboardActiveView');
  sessionStorage.removeItem('dashboardActiveView');
  goToView(saved && navByRole[currentUser.role].some(i => i[0] === saved) ? saved : 'ringkasan');
}

if (sessionUser?.username && navByRole[sessionUser.role]) { currentUser = sessionUser; enterApp(); }
else { location.href = '/index'; }
</script>
</body>
</html>
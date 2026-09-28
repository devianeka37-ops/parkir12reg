<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite('resources/css/app.css')
</head>
<style>

:root{
  --asphalt: #202325;
  --asphalt-2: #2B2F32;
  --yellow: #FFC93C;
  --yellow-dim: #B08B2A;
  --yellow-bg: #FFF6DF;
  --paper: #FFFFFF;
  --bg: #F5F5F2;
  --ink: #202325;
  --ink-dim: #767B7E;
  --line: #E4E4E2;
  --danger: #B4442E;
  --danger-bg: #FBECE8;
  --green: #2F7D5A;
  --green-bg: #E8F3ED;
}
*{ box-sizing:border-box; }
html,body{ margin:0; padding:0; }
body{
  font-family:'Inter', sans-serif;
  background: var(--bg);
  color: var(--ink);
  min-height:100vh;
}
button{ font-family:'Inter', sans-serif; }
 
.field{ display:flex; flex-direction:column; gap:0.4rem; margin-bottom:1.1rem; }
.field label{ font-size:12px; font-weight:600; }
.field input, .field select{
  background: #FAFAF9;
  border: 1.5px solid var(--line);
  border-radius: 8px;
  padding: 0.7rem 0.85rem;
  font-size: 14px;
  color: var(--ink);
  outline:none;
  font-family:'Inter', sans-serif;
  width:100%;
}
.field input:focus, .field select:focus{ border-color: var(--yellow-dim); background:#fff; }
.field-error input{ border-color: var(--danger); background: #FBECE8; }
.error-msg{ font-size:12px; color: var(--danger); min-height:14px; }
 
.btn{
  pointer-events:auto;
  background: var(--yellow);
  color: var(--asphalt);
  border:none;
  border-radius: 8px;
  padding: 0.75rem 1rem;
  font-size: 13px;
  font-weight: 700;
  cursor:pointer;
  font-family:'Space Grotesk', sans-serif;
}
.btn:hover{ background:#FFD766; }
.btn.block{ width:100%; }
.btn.ghost{ background: transparent; color: var(--ink); border: 1px solid var(--line); }
.btn.ghost:hover{ background:#EFEFEC; }
.btn.danger{ background: var(--danger); color:#fff; }
.btn.danger:hover{ background:#9C3A26; }
.btn.sm{ padding: 0.45rem 0.7rem; font-size:12px; border-radius:7px; }
 
/* ================= APP SHELL ================= */
.app-body{ display:flex; min-height:100vh; }
 
.sidebar{
  width: 226px; flex-shrink:0;
  background: var(--asphalt); color:#fff;
  padding: 1.5rem 1rem; display:flex; flex-direction:column;
}
.brand{
  display:flex; align-items:center; gap:0.6rem; padding:0 0.4rem; margin-bottom:1.6rem;
  font-family:'Space Grotesk', sans-serif; font-weight:700; font-size:16px;
}
.brand .mark{
  width:28px; height:28px; background:var(--yellow); border-radius:7px;
  display:flex; align-items:center; justify-content:center;
  color:var(--asphalt); font-weight:700; font-size:14px; flex-shrink:0;
}
.role-badge{
  display:inline-block; font-size:11px; font-weight:700; color: var(--yellow);
  background: var(--asphalt-2); padding: 3px 9px; border-radius:999px; margin-bottom:1.2rem;
}
nav{ display:flex; flex-direction:column; gap:2px; }
nav button.nav-link{
  display:flex; align-items:center; gap:0.65rem;
  padding:0.6rem 0.6rem; border-radius:8px;
  font-size:14px; color:#B7BABC; font-weight:500;
  background:none; border:none; text-align:left; cursor:pointer; width:100%;
}
nav button.nav-link .dot{ width:6px; height:6px; border-radius:50%; background:currentColor; opacity:.5; flex-shrink:0; }
nav button.nav-link:hover{ background: var(--asphalt-2); color:#fff; }
nav button.nav-link.active{ background: var(--asphalt-2); color: var(--yellow); }
nav button.nav-link.active .dot{ background: var(--yellow); opacity:1; }
nav button.nav-link.logout-link{ margin-top: 10px; padding-top: 12px; border-top: 1px solid var(--asphalt-2); color:#E7A499; }
nav button.nav-link.logout-link:hover{ background: #3A2420; color:#fff; }
nav button.nav-link.logout-link .dot{ background: #E7A499; opacity:1; }
 
.sidebar-foot{
  margin-top:auto; display:flex; flex-direction:column; gap:0.7rem;
  padding-top:1rem; border-top:1px solid var(--asphalt-2);
}
.who{ display:flex; align-items:center; gap:0.6rem; padding: 0 0.5rem; }
.avatar{ width:30px; height:30px; border-radius:50%; background:#4A4F52; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; }
.who p{ margin:0; font-size:13px; font-weight:600; color:#fff; }
.who span{ font-size:12px; color:#9A9E9F; }
.logout-btn{
  display:flex; align-items:center; justify-content:center; gap:6px;
  background: var(--asphalt-2); color:#fff; border:none; border-radius:8px;
  padding:0.6rem; font-size:13px; font-weight:600; cursor:pointer;
}
.logout-btn:hover{ background:#3A3F42; }
 
.main{ flex:1; padding: 1.8rem 2.2rem; min-width:0; }
.topbar{ display:flex; align-items:center; justify-content:space-between; margin-bottom:1.6rem; flex-wrap:wrap; gap:10px; }
.topbar h1{ font-family:'Space Grotesk', sans-serif; font-size:20px; margin:0; }
.topbar .sub{ font-size:13px; color:var(--ink-dim); margin:2px 0 0; }
 
.view{ display:none; }
.view.active{ display:block; animation:fadeIn .2s ease; }
@keyframes fadeIn{ from{opacity:0; transform:translateY(6px);} to{opacity:1; transform:translateY(0);} }
 
.stats{ display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:1.6rem; }
.stat-card{ background:var(--paper); border:1px solid var(--line); border-radius:12px; padding:1.1rem 1.2rem; }
.stat-card .label{ font-size:12px; color:var(--ink-dim); margin-bottom:0.5rem; }
.stat-card .value{ font-family:'Space Grotesk', sans-serif; font-size:22px; font-weight:700; }

.area-summary{ margin-bottom:1.6rem; }
.area-summary-head{ display:flex; align-items:flex-end; justify-content:space-between; gap:12px; margin-bottom:0.9rem; }
.area-summary-head h2{ font-size:15px; font-weight:600; margin:0; }
.area-summary-head p{ font-size:12px; color:var(--ink-dim); margin:3px 0 0; }
.area-summary-count{ font-size:12px; color:var(--ink-dim); white-space:nowrap; }
.area-summary-grid{ display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:12px; }
.area-summary-card{ background:var(--paper); border:1px solid var(--line); border-radius:10px; padding:1rem; }
.area-summary-card-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:10px; margin-bottom:0.8rem; }
.area-summary-card h3{ font-size:14px; margin:0; font-weight:600; }
.area-summary-card small{ display:block; color:var(--ink-dim); font-size:11px; margin-top:3px; }
.area-summary-total{ font-family:'Space Grotesk', sans-serif; font-size:20px; font-weight:700; white-space:nowrap; }
.area-summary-total span{ font-family:'Inter', sans-serif; font-size:11px; color:var(--ink-dim); font-weight:500; }
.area-summary-bar{ height:7px; background:#EFEFEC; border-radius:999px; overflow:hidden; margin-bottom:0.75rem; }
.area-summary-bar span{ display:block; height:100%; background:var(--yellow); border-radius:inherit; transition:width .25s ease; }
.area-summary-types{ display:flex; flex-wrap:wrap; gap:5px; }
.area-summary-type{ background:var(--yellow-bg); color:var(--yellow-dim); border-radius:999px; padding:4px 8px; font-size:11px; font-weight:600; }
.area-summary-empty{ color:var(--ink-dim); font-size:12px; }
 
.panel{ background:var(--paper); border:1px solid var(--line); border-radius:12px; padding:1.3rem 1.4rem; }
.panel + .panel{ margin-top:14px; }
.panel-head{ display:flex; align-items:center; justify-content:space-between; margin-bottom:1.1rem; flex-wrap:wrap; gap:8px; }
.panel-head h2{ font-size:15px; font-weight:600; margin:0; }
.panel-head .sub{ font-size:12px; color:var(--ink-dim); }
 
.table-wrap{ overflow-x:auto; }
table{ width:100%; border-collapse:collapse; }
th{ text-align:left; font-size:12px; color:var(--ink-dim); font-weight:600; padding:0 0.4rem 0.6rem; border-bottom:1px solid var(--line); white-space:nowrap; }
td{ font-size:13px; padding:0.65rem 0.4rem; border-bottom:1px solid var(--line); white-space:nowrap; }
tr:last-child td{ border-bottom:none; }
.badge{ display:inline-block; font-size:11px; font-weight:600; padding:3px 8px; border-radius:999px; }
.badge.success{ background:var(--green-bg); color:var(--green); }
.badge.pending{ background:var(--yellow-bg); color:var(--yellow-dim); }
.badge.failed{ background:var(--danger-bg); color:var(--danger); }
.badge.neutral{ background:#EFEFEC; color:var(--ink-dim); }
.row-actions{ display:flex; gap:6px; }
.icon-btn{
  background:none; border:1px solid var(--line); border-radius:6px;
  padding:4px 8px; font-size:12px; cursor:pointer; color:var(--ink);
}
.icon-btn:hover{ background:#F5F5F2; }
.icon-btn.danger{ color:var(--danger); border-color:#EBC7BC; }
.icon-btn.danger:hover{ background:var(--danger-bg); }
 
.filter-row{ display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap; margin-bottom:1.2rem; }
.filter-row .field{ margin-bottom:0; min-width:150px; }
 
/* modal */
.modal-overlay{ position:fixed; inset:0; background:rgba(32,35,37,0.45); display:none; align-items:center; justify-content:center; z-index:60; padding:1rem; overflow-y:auto; }
.modal-overlay.show{ display:flex; }
.modal{ display:block; visibility:visible; position:relative; pointer-events:auto; background:var(--paper); border-radius:14px; padding:1.6rem; width:100%; max-width:420px; max-height:calc(100vh - 2rem); overflow-y:auto; margin:auto; animation:cardIn .18s ease; }
@keyframes cardIn{ from{opacity:0; transform:translateY(10px) scale(.98);} to{opacity:1; transform:translateY(0) scale(1);} }
.modal h3{ font-family:'Space Grotesk', sans-serif; font-size:17px; margin:0 0 1.1rem; }
.modal-actions{ display:flex; gap:8px; margin-top:0.6rem; }
.modal-actions .btn{ flex:1; text-align:center; }
 
/* struk (receipt) */
.receipt{
  font-family:'Inter', monospace, sans-serif;
  background:#FFFDF5; border:1px solid #E8D89A; border-top:5px solid var(--yellow);
  border-radius:10px; padding:1.2rem 1.1rem; max-width:280px; margin:0 auto;
  box-shadow:0 4px 14px rgba(32,35,37,.08);
}
.receipt-head{ text-align:center; margin-bottom:0.8rem; padding-bottom:0.7rem; border-bottom:1px solid #E8D89A; }
.receipt-head b{ font-family:'Space Grotesk', sans-serif; font-size:17px; display:block; color:var(--asphalt); letter-spacing:.3px; }
.receipt-head span{ font-size:11px; color:var(--yellow-dim); text-transform:capitalize; font-weight:600; }
.receipt hr{ border:none; border-top:1px dashed #D9C77E; margin:0.6rem 0; }
.receipt-row{ display:flex; justify-content:space-between; gap:12px; font-size:12.5px; margin-bottom:5px; }
.receipt-row span:first-child{ color:var(--ink-dim); }
.receipt-row span:last-child{ color:var(--asphalt); font-weight:600; text-align:right; }
.receipt-total{ display:flex; justify-content:space-between; gap:12px; font-size:14px; font-weight:700; margin-top:6px; padding:.65rem .7rem; border-radius:7px; background:var(--yellow-bg); color:var(--asphalt); }
.receipt-total span:last-child{ color:var(--green); }
.receipt-foot{ text-align:center; font-size:11px; color:var(--ink-dim); margin-top:0.8rem; line-height:1.45; }
 
/* toast */
.toast{
  position:fixed; bottom:24px; left:50%; transform:translateX(-50%) translateY(20px);
  background:var(--asphalt); color:#fff; padding:0.7rem 1.1rem; border-radius:10px;
  font-size:13px; font-weight:500; display:flex; align-items:center; gap:8px;
  opacity:0; pointer-events:none; transition:opacity .2s ease, transform .2s ease; z-index:70;
}
.toast.show{ opacity:1; transform:translateX(-50%) translateY(0); }
.toast .check{ width:16px; height:16px; border-radius:50%; background:var(--yellow); color:var(--asphalt); display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; flex-shrink:0; }
 
@media print{
  body *{ visibility:hidden; }
  #printArea, #printArea *{ visibility:visible; }
  #printArea{ position:fixed; top:0; left:0; width:100%; }
}
 
@media (max-width: 980px){
  .stats{ grid-template-columns:repeat(2,1fr); }
}
@media (max-width: 680px){
  .app-body{ flex-direction:column; }
  .sidebar{ width:100%; flex-direction:row; align-items:center; flex-wrap:wrap; }
  .brand{ margin-bottom:0; }
  .role-badge{ margin-bottom:0; }
  nav{ flex-direction:row; overflow-x:auto; width:100%; order:3; }
  .sidebar-foot{ display:none; }
  .main{ padding:1.4rem; }
}
</style>
<body>
   <!-- ======================================================= -->
<!-- APP SHELL                                                -->
<!-- ======================================================= -->
<div id="appShell">
<div class="app-body">
 
  <aside class="sidebar">
    <div class="brand"><span class="mark">P</span>Parkir</div>
    <div class="role-badge" id="roleBadge">ADMIN</div>
    <div class="field" style="margin-bottom:1rem;">
      <label style="color:#9A9E9F;">Lihat sebagai</label>
      <select id="roleSwitcher" style="background:var(--asphalt-2); border:1px solid #3A3F42; color:#fff;">
        <option value="Admin">Admin</option>
        <option value="Petugas">Petugas</option>
        <option value="Owner">Owner</option>
      </select>
    </div>
    <nav id="mainNav"></nav>
    <div class="sidebar-foot">
      <div class="who">
        <div class="avatar" id="userAvatar">A</div>
        <div><p id="userNameLbl">Admin</p><span id="userRoleLbl">Administrator</span></div>
      </div>
    </div>
  </aside>
 
  <main class="main">
    <div class="topbar">
      <div><h1 id="pageTitle">Ringkasan</h1><p class="sub" id="pageSub"></p></div>
      <div id="topActions"></div>
    </div>
 
    <!-- ===== RINGKASAN (semua role) ===== -->
    <section class="view active" id="view-ringkasan">
      <div class="stats" id="ringkasanStats"></div>
      <div class="area-summary">
        <div class="area-summary-head">
          <div><h2>Posisi kendaraan saat ini</h2><p>Jumlah kendaraan aktif berdasarkan area parkir dan jenisnya</p></div>
          <div class="area-summary-count" id="areaSummaryCount"></div>
        </div>
        <div class="area-summary-grid" id="areaSummaryGrid"></div>
      </div>
      <div class="panel">
        <div class="panel-head"><div><h2>Log aktivitas terbaru</h2><div class="sub">5 aktivitas terakhir di sistem</div></div></div>
        <div class="table-wrap">
          <table><thead><tr><th>Waktu</th><th>Pengguna</th><th>Aktivitas</th></tr></thead><tbody id="ringkasanLogTable"></tbody></table>
        </div>
      </div>
    </section>
 
    <!-- ===== ADMIN: KELOLA ADMIN ===== -->
    <section class="view" id="view-admin">
      <div class="panel">
        <div class="panel-head"><div><h2>Daftar akun pengguna</h2><div class="sub" id="adminCount"></div></div></div>
        <div class="table-wrap">
          <table><thead><tr><th>Nama</th><th>Username</th><th>Peran</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="adminTable"></tbody></table>
        </div>
      </div>
    </section>
 
    <!-- ===== ADMIN: TARIF PARKIR ===== -->
    <section class="view" id="view-tarif">
      <div class="panel">
        <div class="panel-head"><div><h2>Tarif parkir</h2><div class="sub" id="tarifCount"></div></div></div>
        <div class="table-wrap">
          <table><thead><tr><th>Jenis kendaraan</th><th>Tarif/jam</th><th>Aksi</th></tr></thead><tbody id="tarifTable"></tbody></table>
        </div>
      </div>
    </section>
 
    <!-- ===== ADMIN: AREA PARKIR ===== -->
    <section class="view" id="view-area">
      <div class="panel">
        <div class="panel-head"><div><h2>Area parkir</h2><div class="sub" id="areaCount"></div></div></div>
        <div class="table-wrap">
          <table><thead><tr><th>Nama area</th><th>Kapasitas</th><th>Terisi</th><th>Aksi</th></tr></thead><tbody id="areaTable"></tbody></table>
        </div>
      </div>
    </section>
 
    <!-- ===== ADMIN: KENDARAAN ===== -->
    <section class="view" id="view-kendaraan">
      <div class="panel">
        <div class="panel-head"><div><h2>Data kendaraan terdaftar</h2><div class="sub" id="kendaraanCount"></div></div></div>
        <div class="table-wrap">
          <table><thead><tr><th>Plat nomor</th><th>Jenis</th><th>Pemilik</th><th>Area favorit</th><th>Aksi</th></tr></thead><tbody id="kendaraanTable"></tbody></table>
        </div>
      </div>
    </section>
 
    <!-- ===== ADMIN: LOG AKTIVITAS ===== -->
    <section class="view" id="view-log">
      <div class="panel">
        <div class="panel-head"><div><h2>Log aktivitas sistem</h2><div class="sub" id="logCount"></div></div></div>
        <div class="table-wrap">
          <table><thead><tr><th>Waktu</th><th>Pengguna</th><th>Peran</th><th>Aktivitas</th></tr></thead><tbody id="logTable"></tbody></table>
        </div>
      </div>
    </section>
 
    <!-- ===== PETUGAS: TRANSAKSI ===== -->
    <section class="view" id="view-transaksi">
      <div class="panel">
        <div class="panel-head"><div><h2>Kendaraan masuk</h2><div class="sub">Isi data kendaraan yang baru masuk</div></div></div>
        <div class="filter-row">
          <div class="field"><label>Plat nomor</label><input type="text" id="txPlat" placeholder="cth. B 1234 XYZ" style="width:160px;"></div>
          <div class="field"><label>Jenis kendaraan</label><select id="txJenis" style="width:140px;"></select></div>
          <div class="field"><label>Area parkir</label><select id="txArea" style="width:160px;"></select></div>
          <button class="btn" id="btnCatatMasuk">+ Catat masuk</button>
        </div>
      </div>
 
      <div class="panel">
        <div class="panel-head"><div><h2>Transaksi berjalan &amp; selesai</h2><div class="sub" id="txCount"></div></div></div>
        <div class="table-wrap">
          <table><thead><tr><th>ID</th><th>Plat</th><th>Jenis</th><th>Area</th><th>Masuk</th><th>Keluar</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="txTable"></tbody></table>
        </div>
      </div>
    </section>
 
    <!-- ===== PETUGAS: CETAK STRUK ===== -->
    <section class="view" id="view-struk">
      <div class="panel">
        <div class="panel-head"><div><h2>Kendaraan keluar</h2><div class="sub">Daftar kendaraan yang sudah keluar dan siap dicetak struknya</div></div></div>
        <div class="table-wrap">
          <table><thead><tr><th>ID</th><th>Plat</th><th>Area</th><th>Keluar</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="strukTable"></tbody></table>
        </div>
      </div>
    </section>
 
    <!-- ===== OWNER: REKAP TRANSAKSI ===== -->
    <section class="view" id="view-rekap">
      <div class="panel">
        <div class="panel-head"><div><h2>Filter periode</h2></div></div>
        <div class="filter-row">
          <div class="field"><label>Dari tanggal</label><input type="text" id="rekapFrom" inputmode="numeric" maxlength="10" placeholder="DD/MM/YYYY" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}"></div>
          <div class="field"><label>Sampai tanggal</label><input type="text" id="rekapTo" inputmode="numeric" maxlength="10" placeholder="DD/MM/YYYY" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}"></div>
          <button class="btn" id="btnFilterRekap">Terapkan filter</button>
          <button class="btn ghost" id="btnResetRekap">Reset</button>
        </div>
      </div>
 
      <div class="stats" id="rekapStats"></div>
 
      <div class="panel">
        <div class="panel-head"><div><h2>Rincian transaksi</h2><div class="sub" id="rekapCount"></div></div></div>
        <div class="table-wrap">
          <table><thead><tr><th>ID</th><th>Plat</th><th>Area</th><th>Tanggal &amp; jam masuk</th><th>Tanggal &amp; jam keluar</th><th>Durasi</th><th>Total</th><th>Petugas</th></tr></thead><tbody id="rekapTable"></tbody></table>
        </div>
      </div>
    </section>
 
  </main>
</div>
</div>
 
<!-- ======================================================= -->
<!-- MODALS                                                   -->
<!-- ======================================================= -->
 
<div class="modal-overlay" id="modalAdmin">
  <div class="modal">
    <h3 id="modalAdminTitle">Tambah akun</h3>
    <div class="field"><label>Nama lengkap</label><input type="text" id="fAdminName"></div>
    <div class="field"><label>Username</label><input type="text" id="fAdminUser"></div>
    <div class="field"><label>Password</label><input type="password" id="fAdminPassword" placeholder="Minimal 6 karakter"></div>
    <div class="field"><label>Peran</label>
      <select id="fAdminRole"><option value="Admin">Admin</option><option value="Petugas">Petugas</option><option value="Owner">Owner</option></select>
    </div>
    <div class="field"><label>Status</label>
      <select id="fAdminStatus"><option value="Aktif">Aktif</option><option value="Nonaktif">Nonaktif</option></select>
    </div>
    <div class="error-msg" id="adminModalError"></div>
    <div class="modal-actions">
      <button class="btn ghost" id="cancelAdminModal" type="button">Batal</button>
      <button class="btn" id="saveAdminModal" type="button">Simpan</button>
    </div>
  </div>
</div>
 
<div class="modal-overlay" id="modalTarif">
  <div class="modal">
    <h3 id="modalTarifTitle">Tambah tarif</h3>
    <div class="field"><label>Jenis kendaraan</label>
      <select id="fTarifJenis">
        <option value="mobil">Mobil</option>
        <option value="motor">Motor</option>
        <option value="becak">Becak</option>
        <option value="truk">Truk</option>
        <option value="bus">Bus</option>
        <option value="sepeda">Sepeda</option>
        <option value="lainnya">Lainnya</option>
      </select>
    </div>
    <div class="field"><label>Tarif per jam (Rp)</label><input type="number" id="fTarifJam" placeholder="cth. 3000"></div>
    <div class="error-msg" id="tarifModalError"></div>
    <div class="modal-actions">
      <button class="btn ghost" id="cancelTarifModal" type="button">Batal</button>
      <button class="btn" id="saveTarifModal" type="button">Simpan</button>
    </div>
  </div>
</div>
 
<div class="modal-overlay" id="modalArea">
  <div class="modal">
    <h3 id="modalAreaTitle">Tambah area</h3>
    <div class="field"><label>Nama area</label><input type="text" id="fAreaNama" placeholder="cth. Lantai 2"></div>
    <div class="field"><label>Kapasitas</label><input type="number" id="fAreaKapasitas" placeholder="cth. 50"></div>
    <div class="error-msg" id="areaModalError"></div>
    <div class="modal-actions">
      <button class="btn ghost" id="cancelAreaModal" type="button">Batal</button>
      <button class="btn" id="saveAreaModal" type="button">Simpan</button>
    </div>
  </div>
</div>
 
<div class="modal-overlay" id="modalKendaraan">
  <div class="modal">
    <h3 id="modalKendaraanTitle">Tambah kendaraan</h3>
    <div class="field"><label>Plat nomor</label><input type="text" id="fKendPlat" placeholder="cth. B 1234 XYZ"></div>
    <div class="field"><label>Jenis</label><input type="text" id="fKendJenis" placeholder="cth. Motor"></div>
    <div class="field"><label>Pemilik</label><input type="text" id="fKendPemilik" placeholder="Nama pemilik"></div>
    <div class="field"><label>Area favorit</label><input type="text" id="fKendArea" placeholder="cth. Lantai 1"></div>
    <div class="error-msg" id="kendModalError"></div>
    <div class="modal-actions">
      <button class="btn ghost" id="cancelKendModal" type="button">Batal</button>
      <button class="btn" id="saveKendModal" type="button">Simpan</button>
    </div>
  </div>
</div>
 
<div class="modal-overlay" id="modalKeluar">
  <div class="modal">
    <h3>Catat kendaraan keluar</h3>
    <p style="font-size:13px; color:var(--ink-dim); margin-top:-0.6rem;">Transaksi <b id="keluarId"></b> — <span id="keluarPlat"></span></p>
    <div class="field"><label>Waktu keluar</label><input type="text" id="fKeluarWaktu" readonly></div>
    <div class="field"><label>Durasi</label><input type="text" id="fKeluarDurasi" readonly></div>
    <div class="field"><label>Total bayar (Rp)</label><input type="text" id="fKeluarTotal" readonly></div>
    <div class="modal-actions">
      <button class="btn ghost" id="cancelKeluarModal" type="button">Batal</button>
      <button class="btn" id="saveKeluarModal" type="button">Konfirmasi keluar</button>
    </div>
  </div>
</div>
 
<div class="modal-overlay" id="modalStruk">
  <div class="modal">
    <div id="printArea">
      <div class="receipt" id="receiptContent"></div>
    </div>
    <div class="modal-actions" style="margin-top:1.2rem;">
      <button class="btn ghost" id="closeStrukModal" type="button">Tutup</button>
      <button class="btn" id="printStrukBtn" type="button">🖨 Cetak</button>
    </div>
  </div>
</div>
 
<div class="modal-overlay" id="modalConfirmDelete">
  <div class="modal">
    <h3>Hapus data?</h3>
    <p style="font-size:13px; color:var(--ink-dim);" id="confirmDeleteMsg">Data yang dihapus tidak bisa dikembalikan.</p>
    <div class="modal-actions">
      <button class="btn ghost" id="cancelDeleteModal" type="button">Batal</button>
      <button class="btn danger" id="confirmDeleteBtn" type="button">Hapus</button>
    </div>
  </div>
</div>
 
<div class="toast" id="toast"><span class="check">✓</span><span id="toastMsg">Berhasil disimpan</span></div>
 
<script>
/* ============================================================
  DATA
  ============================================================ */

 
@php
    $accounts = $users->map(function ($user) {
        return [
            'id' => $user->id_user,
            'name' => $user->nama_lengkap,
            'username' => $user->username,
            'password' => $user->password,
            'role' => ucfirst($user->role),
            'status' => $user->status_aktif ? 'Aktif' : 'Nonaktif',
        ];
    });

    $tariffs = $tarif->map(function ($item) {
        return [
            'id' => $item->id_tarif,
            'jenis' => ucfirst($item->jenis_kendaraan),
            'perJam' => $item->tarif_per_jam,
        ];
    });
        $areas = collect($area ?? [])->map(function ($item) {
          return [
            'id' => $item->id_area,
            'nama' => $item->nama_area,
            'kapasitas' => $item->kapasitas ?? 0,
            'terisi' => $item->terisi ?? 0,
          ];
        });

        $vehicles = collect($vehicles ?? [])->map(function ($item) {
          return [
            'id' => $item->id_kendaraan,
            'plat' => $item->plat_nomor,
            'jenis' => ucfirst($item->jenis_kendaraan),
            'pemilik' => $item->nama_pemilik ?? $item->pemilik ?? '',
            'area' => '',
          ];
        });

        $transactions = collect($transaksi ?? [])->map(function ($item) {
          return [
            'id' => 'TX-' . str_pad($item->id_parkir, 4, '0', STR_PAD_LEFT),
            'dbId' => $item->id_parkir,
            'plat' => $item->plat_nomor,
            'jenis' => ucfirst($item->jenis_kendaraan),
            'area' => $item->nama_area,
            'masukTs' => strtotime($item->waktu_masuk) * 1000,
            'keluarTs' => $item->waktu_keluar
              ? strtotime($item->waktu_keluar) * 1000
              : null,
            'total' => $item->biaya_total ?? 0,
            'status' => in_array(strtolower($item->status ?? ''), ['keluar', 'selesai'], true)
              ? 'Selesai'
              : 'Berlangsung',
            'petugas' => $item->nama_petugas ?? 'Sistem',
          ];
        });

        $nextTransactionId = ($transaksi->max('id_parkir') ?? 1003) + 1;
      @endphp

      const accounts = @json($accounts->values());
      const tariffs = @json($tariffs->values());
      const areas = @json($areas->values());
      const vehicles = @json($vehicles->values());
      let transactions = @json($transactions->values());
      let txCounter = {{ $nextTransactionId }};

let activityLog = @json($logs->values());
 
let currentUser = null; // {name, username, role}
let editingId = null;   // dipakai modal edit
let deleteHandler = null;
 
/* ============================================================
   HELPERS
   ============================================================ */
function rupiah(n){ return 'Rp' + Number(n).toLocaleString('id-ID'); }
function fmtTime(ts){
  if(!ts) return '—';
  const d = new Date(ts);
  return d.toLocaleDateString('id-ID', {day:'2-digit',month:'short'}) + ' ' + d.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
}
function fmtDuration(ms){
  const totalMin = Math.max(1, Math.round(ms/60000));
  const h = Math.floor(totalMin/60), m = totalMin%60;
  return (h>0 ? h+'j ' : '') + m+'m';
}
function initials(name){ return name.trim().split(' ').map(w=>w[0]).slice(0,2).join('').toUpperCase(); }
function logActivity(aksi){
  activityLog.unshift({ts: Date.now(), user: currentUser.name, role: currentUser.role, aksi});
}
let toastTimer=null;
function showToast(msg){
  const toast = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  toast.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(()=> toast.classList.remove('show'), 2200);
}
async function saveToDatabase(url, payload, successMessage, returnView = null){
  const response = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify(payload),
  });

  if(!response.ok){
    const result = await response.json().catch(() => ({}));
    const firstError = result.errors ? Object.values(result.errors).flat()[0] : null;
    throw new Error(firstError || result.message || 'Data gagal disimpan.');
  }

  showToast(successMessage);
  const activeView = document.querySelector('.view.active')?.id.replace('view-', '');
  const viewToRestore = returnView || activeView;
  if(viewToRestore){
    sessionStorage.setItem('dashboardActiveView', viewToRestore);
  }
  window.location.reload();
}
function openModal(id){
  const modal = document.getElementById(id);
  modal.classList.add('show');
  const firstField = modal.querySelector('input:not([readonly]), select');
  if(firstField){
    setTimeout(() => {
      firstField.focus({preventScroll: true});
      firstField.select?.();
    }, 50);
  }
}
function closeModal(id){ document.getElementById(id).classList.remove('show'); }
 
function askDelete(msg, onConfirm){
  document.getElementById('confirmDeleteMsg').textContent = msg;
  deleteHandler = onConfirm;
  openModal('modalConfirmDelete');
}
document.getElementById('cancelDeleteModal').addEventListener('click', ()=> closeModal('modalConfirmDelete'));
document.getElementById('confirmDeleteBtn').addEventListener('click', ()=>{
  if(deleteHandler) deleteHandler();
  closeModal('modalConfirmDelete');
});
 
/* ============================================================
   LOGOUT — kembali ke halaman login terpisah (login.html)
   ============================================================ */
function doLogout(){
  window.location.href = '/logout';
}
 
/* ============================================================
   NAVIGASI PER ROLE
   ============================================================ */
const navByRole = {
  Admin: [
    {id:'ringkasan', label:'Ringkasan'},
    {id:'admin', label:'Kelola User'},
    {id:'tarif', label:'Tarif Parkir'},
    {id:'area', label:'Area Parkir'},
    {id:'kendaraan', label:'Kendaraan'},
    {id:'log', label:'Log Aktivitas'},
    {id:'logout', label:'Keluar'},
  ],
  Petugas: [
    {id:'ringkasan', label:'Ringkasan'},
    {id:'struk', label:'Kendaraan Keluar'},
    {id:'transaksi', label:'Kendaraan Masuk'},
    {id:'logout', label:'Keluar'},
  ],
  Owner: [
    {id:'ringkasan', label:'Ringkasan'},
    {id:'rekap', label:'Rekap Transaksi'},
    {id:'logout', label:'Keluar'},
  ],
};
 
const pageTitles = {
  ringkasan: ['Ringkasan', 'Ikhtisar aktivitas sistem'],
  admin: ['Kelola User', 'Kelola akun admin, petugas, dan owner'],
  tarif: ['Tarif Parkir', 'Atur tarif per jenis kendaraan'],
  area: ['Area Parkir', 'Kelola area dan kapasitas parkir'],
  kendaraan: ['Kendaraan', 'Data kendaraan yang terdaftar'],
  log: ['Log Aktivitas', 'Riwayat semua aktivitas di sistem'],
  transaksi: ['Kendaraan Masuk', 'Catat kendaraan yang masuk area parkir'],
  struk: ['Kendaraan Keluar', 'Lihat kendaraan keluar dan cetak struk'],
  rekap: ['Rekap Transaksi', 'Rekap transaksi berdasarkan periode'],
};
 
const topActionsByView = {
  admin: '<button class="btn" id="btnAddAdmin">+ Tambah user</button>',
  tarif: '<button class="btn" id="btnAddTarif">+ Tambah tarif</button>',
  area: '<button class="btn" id="btnAddArea">+ Tambah area</button>',
  kendaraan: '<button class="btn" id="btnAddKend">+ Tambah kendaraan</button>',
};
 
function enterApp(){
  document.getElementById('roleBadge').textContent = currentUser.role.toUpperCase();
  document.getElementById('userNameLbl').textContent = currentUser.name;
  document.getElementById('userRoleLbl').textContent = currentUser.role;
  document.getElementById('userAvatar').textContent = initials(currentUser.name);
  document.getElementById('roleSwitcher').value = currentUser.role;
 
  const nav = document.getElementById('mainNav');
  nav.innerHTML = navByRole[currentUser.role].map((item,i) => `
    <button class="nav-link${i===0?' active':''}${item.id==='logout' ? ' logout-link' : ''}" data-view="${item.id}"><span class="dot"></span>${item.label}</button>
  `).join('');
 
  const savedView = sessionStorage.getItem('dashboardActiveView');
  sessionStorage.removeItem('dashboardActiveView');
  goToView(savedView && navByRole[currentUser.role].some(item => item.id === savedView) ? savedView : 'ringkasan');
  renderAll();
}
 
function goToView(name){
  if(name === 'logout'){
    doLogout();
    return;
  }
  document.querySelectorAll('.view').forEach(v=>v.classList.remove('active'));
  document.getElementById('view-'+name).classList.add('active');
  document.querySelectorAll('.nav-link').forEach(b=>b.classList.remove('active'));
  const btn = document.querySelector('.nav-link[data-view="'+name+'"]');
  if(btn) btn.classList.add('active');
 
  const t = pageTitles[name];
  document.getElementById('pageTitle').textContent = t[0];
  document.getElementById('pageSub').textContent = t[1];
  document.getElementById('topActions').innerHTML = topActionsByView[name] || '';
 
  renderAll();
}
 
document.getElementById('mainNav').addEventListener('click', (e)=>{
  const btn = e.target.closest('.nav-link');
  if(!btn) return;
  goToView(btn.dataset.view);
});
 
document.getElementById('topActions').addEventListener('click', (e)=>{
  const button = e.target.closest('button');
  if(!button) return;

  const actions = {
    btnAddAdmin: openAdminModal,
    btnAddTarif: openTarifModal,
    btnAddArea: openAreaModal,
    btnAddKend: openKendModal,
  };

  const action = actions[button.id];
  if(action) action();
});
 
/* ============================================================
   RENDER — RINGKASAN
   ============================================================ */
function renderRingkasan(){
  const selesai = transactions.filter(t=>t.status==='Selesai');
  const berlangsung = transactions.filter(t=>t.status==='Berlangsung');
 const totalPendapatan = selesai.reduce(
    (a, t) => a + Number(t.total || 0),
    0
);
  let statsHtml = '';
  if(currentUser.role === 'Admin'){
    statsHtml = `
      <div class="stat-card"><div class="label">Total akun</div><div class="value">${accounts.length}</div></div>
      <div class="stat-card"><div class="label">Total area</div><div class="value">${areas.length}</div></div>
      <div class="stat-card"><div class="label">Kendaraan terdaftar</div><div class="value">${vehicles.length}</div></div>
      <div class="stat-card"><div class="label">Aktivitas tercatat</div><div class="value">${activityLog.length}</div></div>`;
  } else if(currentUser.role === 'Petugas'){
    statsHtml = `
      <div class="stat-card"><div class="label">Sedang parkir</div><div class="value">${berlangsung.length}</div></div>
      <div class="stat-card"><div class="label">Transaksi selesai</div><div class="value">${selesai.length}</div></div>
      <div class="stat-card"><div class="label">Pendapatan tercatat</div><div class="value">${rupiah(totalPendapatan)}</div></div>
      <div class="stat-card"><div class="label">Area tersedia</div><div class="value">${areas.length}</div></div>`;
  } else {
    statsHtml = `
      <div class="stat-card"><div class="label">Total transaksi</div><div class="value">${transactions.length}</div></div>
      <div class="stat-card"><div class="label">Transaksi selesai</div><div class="value">${selesai.length}</div></div>
      <div class="stat-card"><div class="label">Total pendapatan</div><div class="value">${rupiah(totalPendapatan)}</div></div>
      <div class="stat-card"><div class="label">Area aktif</div><div class="value">${areas.length}</div></div>`;
  }
  document.getElementById('ringkasanStats').innerHTML = statsHtml;
  renderAreaSummary();
 
  document.getElementById('ringkasanLogTable').innerHTML = activityLog.slice(0,5).map(l => `
    <tr><td>${fmtTime(l.ts)}</td><td>${l.user}</td><td>${l.aksi}</td></tr>
  `).join('') || '<tr><td colspan="3" style="color:var(--ink-dim)">Belum ada aktivitas.</td></tr>';
}

function renderAreaSummary(){
  const activeTransactions = transactions.filter(t=>t.status==='Berlangsung');
  const grid = document.getElementById('areaSummaryGrid');
  const countLabel = document.getElementById('areaSummaryCount');

  countLabel.textContent = activeTransactions.length + ' kendaraan sedang parkir';
  grid.innerHTML = areas.map(area => {
    const areaTransactions = activeTransactions.filter(t => t.area === area.nama);
    const vehicleTypes = areaTransactions.reduce((counts, transaction) => {
      const type = transaction.jenis || 'Lainnya';
      counts[type] = (counts[type] || 0) + 1;
      return counts;
    }, {});
    const occupied = areaTransactions.length || Number(area.terisi) || 0;
    const capacity = Number(area.kapasitas) || 0;
    const percentage = capacity ? Math.min(100, Math.round((occupied / capacity) * 100)) : 0;
    const types = Object.entries(vehicleTypes)
      .map(([type, total]) => `<span class="area-summary-type">${type} ${total}</span>`)
      .join('');

    return `
      <article class="area-summary-card">
        <div class="area-summary-card-head">
          <div><h3>${area.nama}</h3><small>Kapasitas ${capacity} kendaraan</small></div>
          <div class="area-summary-total">${occupied} <span>/ ${capacity}</span></div>
        </div>
        <div class="area-summary-bar" aria-label="${percentage}% terisi"><span style="width:${percentage}%"></span></div>
        <div class="area-summary-types">${types || '<span class="area-summary-empty">Belum ada kendaraan</span>'}</div>
      </article>`;
  }).join('') || '<div class="panel area-summary-empty">Belum ada area parkir yang terdaftar.</div>';
}
 
/* ============================================================
   RENDER + CRUD — ADMIN (kelola akun)
   ============================================================ */
function renderAdmin(){
  document.getElementById('adminCount').textContent = accounts.length + ' akun terdaftar';
  document.getElementById('adminTable').innerHTML = accounts.map(a => `
    <tr>
      <td>${a.name}</td><td>${a.username}</td><td>${a.role}</td>
      <td><span class="badge ${a.status==='Aktif' ? 'success' : 'neutral'}">${a.status}</span></td>
      <td class="row-actions">
        <button class="icon-btn" onclick="openAdminModal(${a.id})">Edit</button>
        <button class="icon-btn danger" onclick="deleteAdmin(${a.id})">Hapus</button>
      </td>
    </tr>`).join('');
}
 
function openAdminModal(id){
  editingId = id || null;
  document.getElementById('adminModalError').textContent = '';
  if(id){
    const a = accounts.find(x=>x.id===id);
    document.getElementById('modalAdminTitle').textContent = 'Edit akun';
    document.getElementById('fAdminName').value = a.name;
    document.getElementById('fAdminUser').value = a.username;
    document.getElementById('fAdminPassword').value = '';
    document.getElementById('fAdminRole').value = a.role;
    document.getElementById('fAdminStatus').value = a.status;
  } else {
    document.getElementById('modalAdminTitle').textContent = 'Tambah akun';
    document.getElementById('fAdminName').value = '';
    document.getElementById('fAdminUser').value = '';
    document.getElementById('fAdminPassword').value = '';
    document.getElementById('fAdminRole').value = 'Admin';
    document.getElementById('fAdminStatus').value = 'Aktif';
  }
  openModal('modalAdmin');
}
document.getElementById('cancelAdminModal').addEventListener('click', ()=> closeModal('modalAdmin'));
document.getElementById('saveAdminModal').addEventListener('click', async ()=>{
  const name = document.getElementById('fAdminName').value.trim();
  const username = document.getElementById('fAdminUser').value.trim();
  const password = document.getElementById('fAdminPassword').value;
  const role = document.getElementById('fAdminRole').value;
  const status = document.getElementById('fAdminStatus').value;
  if(!name || !username){
    document.getElementById('adminModalError').textContent = 'Nama dan username wajib diisi.';
    return;
  }
  if(!editingId && password.length < 6){
    document.getElementById('adminModalError').textContent = 'Password akun baru minimal 6 karakter.';
    return;
  }
  if(editingId && password && password.length < 6){
    document.getElementById('adminModalError').textContent = 'Password minimal 6 karakter.';
    return;
  }
  const dup = accounts.find(a => a.username===username && a.id !== editingId);
  if(dup){
    document.getElementById('adminModalError').textContent = 'Username sudah dipakai.';
    return;
  }
  if(editingId){
    const a = accounts.find(x=>x.id===editingId);
    Object.assign(a, {name, username, role, status});
    if(password) a.password = password;
    logActivity('Mengubah akun ' + name);
    showToast('Akun diperbarui');
  } else {
    try {
      await saveToDatabase('/dashboard/users', {
        nama_lengkap: name,
        username,
        password,
        role: role.toLowerCase(),
        status_aktif: status === 'Aktif',
      }, 'Akun baru ditambahkan');
    } catch (requestError) {
      document.getElementById('adminModalError').textContent = requestError.message;
    }
    return;
  }
  closeModal('modalAdmin');
  renderAll();
});
function deleteAdmin(id){
  const a = accounts.find(x=>x.id===id);
  askDelete('Hapus akun "' + a.name + '"?', ()=>{
    const idx = accounts.findIndex(x=>x.id===id);
    accounts.splice(idx,1);
    logActivity('Menghapus akun ' + a.name);
    showToast('Akun dihapus');
    renderAll();
  });
}
 
/* ============================================================
   RENDER + CRUD — TARIF PARKIR
   ============================================================ */
function renderTarif(){
  document.getElementById('tarifCount').textContent = tariffs.length + ' jenis tarif';
  document.getElementById('tarifTable').innerHTML = tariffs.map(t => `
    <tr>
      <td>${t.jenis}</td><td>${rupiah(t.perJam)}</td>
      <td class="row-actions">
        <button class="icon-btn" onclick="openTarifModal(${t.id})">Edit</button>
        <button class="icon-btn danger" onclick="deleteTarif(${t.id})">Hapus</button>
      </td>
    </tr>`).join('');
 
  // sinkronkan opsi jenis kendaraan di form transaksi
  const sel = document.getElementById('txJenis');
  if(sel){
    const current = sel.value;
    sel.innerHTML = tariffs.map(t => `<option value="${t.jenis}">${t.jenis}</option>`).join('');
    if(current) sel.value = current;
  }
}
function openTarifModal(id){
  editingId = id || null;
  document.getElementById('tarifModalError').textContent = '';
  if(id){
    const t = tariffs.find(x=>x.id===id);
    document.getElementById('modalTarifTitle').textContent = 'Edit tarif';
    document.getElementById('fTarifJenis').value = t.jenis.toLowerCase();
    document.getElementById('fTarifJam').value = t.perJam;
  } else {
    document.getElementById('modalTarifTitle').textContent = 'Tambah tarif';
    document.getElementById('fTarifJenis').value = '';
    document.getElementById('fTarifJam').value = '';
  }
  openModal('modalTarif');
}
document.getElementById('cancelTarifModal').addEventListener('click', ()=> closeModal('modalTarif'));
document.getElementById('saveTarifModal').addEventListener('click', async ()=>{
  const jenis = document.getElementById('fTarifJenis').value.trim();
  const perJam = Number(document.getElementById('fTarifJam').value);
  const error = document.getElementById('tarifModalError');

  if(!jenis){
    error.textContent = 'Jenis kendaraan wajib diisi.';
    return;
  }
  if(!Number.isFinite(perJam) || perJam <= 0 || !Number.isInteger(perJam)){
    error.textContent = 'Tarif per jam harus berupa angka bulat lebih dari 0.';
    return;
  }
  const duplicate = tariffs.find(t => t.jenis.toLowerCase() === jenis.toLowerCase() && t.id !== editingId);
  if(duplicate){
    error.textContent = 'Jenis kendaraan tersebut sudah memiliki tarif.';
    return;
  }
  if(editingId){
    Object.assign(tariffs.find(x=>x.id===editingId), {jenis, perJam});
    logActivity('Mengubah tarif ' + jenis);
    showToast('Tarif diperbarui');
  } else {
    try {
      await saveToDatabase('/dashboard/tariffs', {
        jenis_kendaraan: jenis.toLowerCase(),
        tarif_per_jam: perJam,
      }, 'Tarif baru ditambahkan');
    } catch (requestError) {
      error.textContent = requestError.message;
    }
    return;
  }
  closeModal('modalTarif');
  renderAll();
});
function deleteTarif(id){
  const t = tariffs.find(x=>x.id===id);
  askDelete('Hapus tarif "' + t.jenis + '"?', ()=>{
    tariffs.splice(tariffs.findIndex(x=>x.id===id),1);
    logActivity('Menghapus tarif ' + t.jenis);
    showToast('Tarif dihapus');
    renderAll();
  });
}
 
/* ============================================================
   RENDER + CRUD — AREA PARKIR
   ============================================================ */
function renderArea(){
  document.getElementById('areaCount').textContent = areas.length + ' area terdaftar';
  document.getElementById('areaTable').innerHTML = areas.map(a => `
    <tr>
      <td>${a.nama}</td><td>${a.kapasitas}</td><td>${a.terisi}/${a.kapasitas}</td>
      <td class="row-actions">
        <button class="icon-btn" onclick="openAreaModal(${a.id})">Edit</button>
        <button class="icon-btn danger" onclick="deleteArea(${a.id})">Hapus</button>
      </td>
    </tr>`).join('');
 
  const sel = document.getElementById('txArea');
  if(sel){
    const current = sel.value;
    sel.innerHTML = areas.map(a => `<option value="${a.id}">${a.nama}</option>`).join('');
    if(current) sel.value = current;
  }
}
function openAreaModal(id){
  editingId = id || null;
  document.getElementById('areaModalError').textContent = '';
  if(id){
    const a = areas.find(x=>x.id===id);
    document.getElementById('modalAreaTitle').textContent = 'Edit area';
    document.getElementById('fAreaNama').value = a.nama;
    document.getElementById('fAreaKapasitas').value = a.kapasitas;
  } else {
    document.getElementById('modalAreaTitle').textContent = 'Tambah area';
    document.getElementById('fAreaNama').value = '';
    document.getElementById('fAreaKapasitas').value = '';
  }
  openModal('modalArea');
}
document.getElementById('cancelAreaModal').addEventListener('click', ()=> closeModal('modalArea'));
document.getElementById('saveAreaModal').addEventListener('click', async ()=>{
  const nama = document.getElementById('fAreaNama').value.trim();
  const kapasitas = parseInt(document.getElementById('fAreaKapasitas').value);
  if(!nama || !kapasitas){
    document.getElementById('areaModalError').textContent = 'Nama area dan kapasitas wajib diisi dengan benar.';
    return;
  }
  if(editingId){
    const a = areas.find(x=>x.id===editingId);
    Object.assign(a, {nama, kapasitas});
    logActivity('Mengubah area ' + nama);
    showToast('Area diperbarui');
  } else {
    try {
      await saveToDatabase('/dashboard/areas', {
        nama_area: nama,
        kapasitas,
      }, 'Area baru ditambahkan');
    } catch (requestError) {
      document.getElementById('areaModalError').textContent = requestError.message;
    }
    return;
  }
  closeModal('modalArea');
  renderAll();
});
function deleteArea(id){
  const a = areas.find(x=>x.id===id);
  askDelete('Hapus area "' + a.nama + '"?', ()=>{
    areas.splice(areas.findIndex(x=>x.id===id),1);
    logActivity('Menghapus area ' + a.nama);
    showToast('Area dihapus');
    renderAll();
  });
}
 
/* ============================================================
   RENDER + CRUD — KENDARAAN
   ============================================================ */
function renderKendaraan(){
  document.getElementById('kendaraanCount').textContent = vehicles.length + ' kendaraan terdaftar';
  document.getElementById('kendaraanTable').innerHTML = vehicles.map(k => `
    <tr>
      <td>${k.plat}</td><td>${k.jenis}</td><td>${k.pemilik}</td><td>${k.area}</td>
      <td class="row-actions">
        <button class="icon-btn" onclick="openKendModal(${k.id})">Edit</button>
        <button class="icon-btn danger" onclick="deleteKend(${k.id})">Hapus</button>
      </td>
    </tr>`).join('');
}
function openKendModal(id){
  editingId = id || null;
  document.getElementById('kendModalError').textContent = '';
  if(id){
    const k = vehicles.find(x=>x.id===id);
    document.getElementById('modalKendaraanTitle').textContent = 'Edit kendaraan';
    document.getElementById('fKendPlat').value = k.plat;
    document.getElementById('fKendJenis').value = k.jenis;
    document.getElementById('fKendPemilik').value = k.pemilik;
    document.getElementById('fKendArea').value = k.area;
  } else {
    document.getElementById('modalKendaraanTitle').textContent = 'Tambah kendaraan';
    document.getElementById('fKendPlat').value = '';
    document.getElementById('fKendJenis').value = '';
    document.getElementById('fKendPemilik').value = '';
    document.getElementById('fKendArea').value = '';
  }
  openModal('modalKendaraan');
}
document.getElementById('cancelKendModal').addEventListener('click', ()=> closeModal('modalKendaraan'));
document.getElementById('saveKendModal').addEventListener('click', async ()=>{
  const plat = document.getElementById('fKendPlat').value.trim();
  const jenis = document.getElementById('fKendJenis').value.trim();
  const pemilik = document.getElementById('fKendPemilik').value.trim();
  const area = document.getElementById('fKendArea').value.trim();
  if(!plat || !jenis || !pemilik){
    document.getElementById('kendModalError').textContent = 'Plat, jenis, dan pemilik wajib diisi.';
    return;
  }
  if(editingId){
    Object.assign(vehicles.find(x=>x.id===editingId), {plat, jenis, pemilik, area});
    logActivity('Mengubah data kendaraan ' + plat);
    showToast('Data kendaraan diperbarui');
  } else {
    try {
      await saveToDatabase('/dashboard/vehicles', {
        plat_nomor: plat,
        jenis_kendaraan: jenis,
        nama_pemilik: pemilik,
      }, 'Kendaraan baru ditambahkan');
    } catch (requestError) {
      document.getElementById('kendModalError').textContent = requestError.message;
    }
    return;
  }
  closeModal('modalKendaraan');
  renderAll();
});
function deleteKend(id){
  const k = vehicles.find(x=>x.id===id);
  askDelete('Hapus kendaraan "' + k.plat + '"?', ()=>{
    vehicles.splice(vehicles.findIndex(x=>x.id===id),1);
    logActivity('Menghapus kendaraan ' + k.plat);
    showToast('Kendaraan dihapus');
    renderAll();
  });
}
 
/* ============================================================
   RENDER — LOG AKTIVITAS (admin)
   ============================================================ */
function renderLog(){
  document.getElementById('logCount').textContent = activityLog.length + ' entri tercatat';
  document.getElementById('logTable').innerHTML = activityLog.map(l => `
    <tr><td>${fmtTime(l.ts)}</td><td>${l.user}</td><td>${l.role}</td><td>${l.aksi}</td></tr>
  `).join('') || '<tr><td colspan="4" style="color:var(--ink-dim)">Belum ada aktivitas.</td></tr>';
}
 
/* ============================================================
   RENDER + AKSI — TRANSAKSI (petugas)
   ============================================================ */
function renderTransaksi(){
  document.getElementById('txCount').textContent = transactions.length + ' transaksi tercatat';
  document.getElementById('txTable').innerHTML = transactions.slice().reverse().map(t => `
    <tr>
      <td>${t.id}</td><td>${t.plat}</td><td>${t.jenis}</td><td>${t.area}</td>
      <td>${fmtTime(t.masukTs)}</td><td>${fmtTime(t.keluarTs)}</td>
      <td>${t.status==='Selesai' ? rupiah(t.total) : '—'}</td>
      <td><span class="badge ${t.status==='Selesai' ? 'success' : 'pending'}">${t.status}</span></td>
      <td class="row-actions">
        ${t.status==='Berlangsung'
          ? `<button class="icon-btn" onclick="openKeluarModal('${t.id}')">Catat keluar</button>`
          : `<button class="icon-btn" onclick="printStruk('${t.id}')">Cetak struk</button>`}
      </td>
    </tr>`).join('') || '<tr><td colspan="9" style="color:var(--ink-dim)">Belum ada transaksi. Catat kendaraan masuk terlebih dahulu.</td></tr>';
}
 
document.getElementById('btnCatatMasuk').addEventListener('click', async ()=>{
  const plat = document.getElementById('txPlat').value.trim();
  const jenis = document.getElementById('txJenis').value;
  const area = document.getElementById('txArea').value;
  if(!plat){ showToast('Plat nomor wajib diisi'); return; }
  if(!jenis || !area){ showToast('Lengkapi jenis dan area parkir dulu (di menu Admin)'); return; }
 
  try {
    await saveToDatabase('/dashboard/transactions', {
      plat_nomor: plat,
      jenis_kendaraan: jenis.toLowerCase(),
      id_area: Number(area),
    }, 'Kendaraan ' + plat + ' tercatat masuk', 'transaksi');
  } catch (requestError) {
    showToast(requestError.message);
  }
});
 
function openKeluarModal(id){
  editingId = id;
  const t = transactions.find(x=>x.id===id);
  const tarif = tariffs.find(x=>x.jenis===t.jenis) || {perJam:3000};
  const now = Date.now();
  const durMs = now - t.masukTs;
  const jam = Math.max(1, Math.ceil(durMs/3600000));
  const total = jam * tarif.perJam;
 
  document.getElementById('keluarId').textContent = t.id;
  document.getElementById('keluarPlat').textContent = t.plat + ' · ' + t.area;
  document.getElementById('fKeluarWaktu').value = fmtTime(now);
  document.getElementById('fKeluarDurasi').value = fmtDuration(durMs);
  document.getElementById('fKeluarTotal').value = rupiah(total);
  openModal('modalKeluar');
}
document.getElementById('cancelKeluarModal').addEventListener('click', ()=> closeModal('modalKeluar'));
document.getElementById('saveKeluarModal').addEventListener('click', async ()=>{
  const t = transactions.find(x=>x.id===editingId);
  if(!t?.dbId){
    showToast('Transaksi ini belum terhubung ke database.');
    return;
  }
  try {
    await saveToDatabase('/dashboard/transactions/' + t.dbId + '/close', {}, 'Transaksi ' + t.id + ' selesai', 'transaksi');
  } catch (requestError) {
    showToast(requestError.message);
  }
});
 
/* ============================================================
   RENDER + AKSI — CETAK STRUK (petugas)
   ============================================================ */
function renderStruk(){
  const semua = transactions.slice().reverse();
  document.getElementById('strukTable').innerHTML = semua.map(t => {
    const selesai = t.status === 'Selesai';
    return `
    <tr>
      <td>${t.id}</td><td>${t.plat}</td><td>${t.area}</td>
      <td>${selesai ? fmtTime(t.keluarTs) : '—'}</td>
      <td>${selesai ? rupiah(t.total) : '—'}</td>
      <td><span class="badge ${selesai ? 'success' : 'pending'}">${selesai ? 'Selesai' : 'Menunggu keluar'}</span></td>
      <td>${selesai
        ? `<button class="icon-btn" onclick="printStruk('${t.id}')">🖨 Cetak</button>`
        : `<button class="icon-btn" disabled style="opacity:.4; cursor:not-allowed;" title="Catat kendaraan keluar dulu di menu Transaksi">🖨 Cetak</button>`}</td>
    </tr>`;
  }).join('') || '<tr><td colspan="7" style="color:var(--ink-dim)">Belum ada transaksi selesai. Buka menu Transaksi untuk mencatat kendaraan masuk dan keluar.</td></tr>';
}
 
function printStruk(id){
  const t = transactions.find(x=>x.id===id);
  const durMs = t.keluarTs - t.masukTs;
  document.getElementById('receiptContent').innerHTML = `
    <div class="receipt-head"><b>Parkir</b><span>${t.area}</span></div>
    <hr>
    <div class="receipt-row"><span>No. Transaksi</span><span>${t.id}</span></div>
    <div class="receipt-row"><span>Plat nomor</span><span>${t.plat}</span></div>
    <div class="receipt-row"><span>Jenis</span><span>${t.jenis}</span></div>
    <div class="receipt-row"><span>Masuk</span><span>${fmtTime(t.masukTs)}</span></div>
    <div class="receipt-row"><span>Keluar</span><span>${fmtTime(t.keluarTs)}</span></div>
    <div class="receipt-row"><span>Durasi</span><span>${fmtDuration(durMs)}</span></div>
    <div class="receipt-row"><span>Petugas</span><span>${t.petugas}</span></div>
    <hr>
    <div class="receipt-total"><span>Total</span><span>${rupiah(t.total)}</span></div>
    <div class="receipt-foot">Terima kasih — simpan struk ini<br>sebagai bukti pembayaran.</div>
  `;
  openModal('modalStruk');
}
document.getElementById('closeStrukModal').addEventListener('click', ()=> closeModal('modalStruk'));
document.getElementById('printStrukBtn').addEventListener('click', ()=> window.print());
 
/* ============================================================
   RENDER + FILTER — REKAP TRANSAKSI (owner)
   ============================================================ */
function parseDateInput(value, endOfDay){
  const normalizedValue = value.trim().toLowerCase();
  const monthNames = {jan: 1, feb: 2, mar: 3, apr: 4, mei: 5, may: 5, jun: 6, jul: 7, agu: 8, aug: 8, sep: 9, okt: 10, oct: 10, nov: 11, des: 12, dec: 12};
  const match = normalizedValue.match(/^(\d{1,2})(?:[\/-](\d{1,2})[\/-](\d{2,4})|\s*([a-z]{3})\s*(\d{2,4}))$/);
  if(!match) return NaN;

  const day = Number(match[1]);
  const month = match[4] ? monthNames[match[4]] : Number(match[2]);
  const yearValue = match[4] ? match[5] : match[3];
  const year = Number(yearValue) < 100 ? 2000 + Number(yearValue) : Number(yearValue);
  if(!month) return NaN;
  const date = new Date(year, month - 1, day, endOfDay ? 23 : 0, endOfDay ? 59 : 0, endOfDay ? 59 : 0);

  if(date.getFullYear() !== year || date.getMonth() !== month - 1 || date.getDate() !== day) return NaN;
  return date.getTime();
}

function renderRekap(){
  const fromVal = document.getElementById('rekapFrom').value;
  const toVal = document.getElementById('rekapTo').value;
  let filtered = transactions.filter(t=>t.status==='Selesai');
 
  if(fromVal){
    const fromTs = parseDateInput(fromVal, false);
    filtered = filtered.filter(t => t.masukTs >= fromTs);
  }
  if(toVal){
    const toTs = parseDateInput(toVal, true);
    filtered = filtered.filter(t => t.masukTs <= toTs);
  }
 
  const totalPendapatan = filtered.reduce((a,t)=>a+Number(t.total || 0),0);
  const rataRata = filtered.length ? Math.round(totalPendapatan/filtered.length) : 0;
 
  document.getElementById('rekapStats').innerHTML = `
    <div class="stat-card"><div class="label">Total transaksi</div><div class="value">${filtered.length}</div></div>
    <div class="stat-card"><div class="label">Total pendapatan</div><div class="value">${rupiah(totalPendapatan)}</div></div>
    <div class="stat-card"><div class="label">Rata-rata / transaksi</div><div class="value">${rupiah(rataRata)}</div></div>
    <div class="stat-card"><div class="label">Periode</div><div class="value" style="font-size:14px;">${fromVal||'Semua'} s/d ${toVal||'Semua'}</div></div>
  `;
 
  document.getElementById('rekapCount').textContent = filtered.length + ' transaksi ditemukan';
  document.getElementById('rekapTable').innerHTML = filtered.slice().reverse().map(t => `
    <tr>
      <td>${t.id}</td><td>${t.plat}</td><td>${t.area}</td>
      <td>${fmtTime(t.masukTs)}</td><td>${fmtTime(t.keluarTs)}</td>
      <td>${fmtDuration(t.keluarTs - t.masukTs)}</td>
      <td>${rupiah(t.total)}</td><td>${t.petugas}</td>
    </tr>`).join('') || '<tr><td colspan="8" style="color:var(--ink-dim)">Tidak ada transaksi pada periode ini.</td></tr>';
}
document.getElementById('btnFilterRekap').addEventListener('click', renderRekap);
document.getElementById('btnResetRekap').addEventListener('click', ()=>{
  document.getElementById('rekapFrom').value = '';
  document.getElementById('rekapTo').value = '';
  renderRekap();
});
 
/* ============================================================
   RENDER ALL
   ============================================================ */
function renderAll(){
  if(!currentUser) return;
  renderRingkasan();
  if(currentUser.role === 'Admin'){
    renderAdmin(); renderTarif(); renderArea(); renderKendaraan(); renderLog();
  }
  if(currentUser.role === 'Petugas'){
    renderTarif(); renderArea(); renderTransaksi(); renderStruk();
  }
  if(currentUser.role === 'Owner'){
    renderRekap();
  }
}
 
/* ============================================================
   MASUK — dari query string (dikirim oleh login.html), atau
   langsung sebagai Admin jika halaman ini dibuka tanpa lewat
   login (mis. untuk pratinjau cepat dashboard)
   ============================================================ */
function enterAsRole(roleLabel){
  const acc = accounts.find(a => a.role === roleLabel && a.status === 'Aktif');
  currentUser = {
    name: acc?.name || roleLabel,
    username: acc?.username || roleLabel.toLowerCase(),
    role: roleLabel,
  };
  enterApp();
}
 
document.getElementById('roleSwitcher').addEventListener('change', (e)=>{
  enterAsRole(e.target.value);
  showToast('Melihat dashboard sebagai ' + e.target.value);
});
 
const sessionUser = @json($sessionUser);

if(sessionUser?.username && sessionUser?.role){
  currentUser = sessionUser;
  enterApp();
} else {
  window.location.href = '/index';
}
</script>
</body>
</html>
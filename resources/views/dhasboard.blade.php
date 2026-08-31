<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    display:flex;
    min-height:100vh;
  }

  /* sidebar */
  .sidebar{
    width: 224px;
    flex-shrink:0;
    background: var(--asphalt);
    color: #fff;
    padding: 1.5rem 1rem;
    display:flex;
    flex-direction:column;
  }
  .brand{
    display:flex;
    align-items:center;
    gap: 0.6rem;
    padding: 0 0.4rem;
    margin-bottom: 2rem;
    font-family:'Space Grotesk', sans-serif;
    font-weight: 700;
    font-size: 16px;
  }
  .brand .mark{
    width: 28px; height: 28px;
    background: var(--yellow);
    border-radius: 7px;
    display:flex;
    align-items:center;
    justify-content:center;
    color: var(--asphalt);
    font-weight:700;
    font-size: 14px;
    flex-shrink:0;
  }
  nav{ display:flex; flex-direction:column; gap: 2px; }
  nav a{
    display:flex;
    align-items:center;
    gap: 0.65rem;
    padding: 0.6rem 0.6rem;
    border-radius: 8px;
    font-size: 14px;
    color: #B7BABC;
    text-decoration:none;
    font-weight: 500;
  }
  nav a .dot{ width:6px; height:6px; border-radius:50%; background: currentColor; opacity:.5; flex-shrink:0; }
  nav a:hover{ background: var(--asphalt-2); color: #fff; }
  nav a.active{ background: var(--asphalt-2); color: var(--yellow); }
  nav a.active .dot{ background: var(--yellow); opacity:1; }
  .sidebar-foot{
    margin-top:auto;
    display:flex;
    align-items:center;
    gap: 0.6rem;
    padding: 0.6rem 0.5rem;
    border-top: 1px solid var(--asphalt-2);
    padding-top: 1rem;
  }
  .avatar{ width:30px; height:30px; border-radius:50%; background:#4A4F52; flex-shrink:0; }
  .who p{ margin:0; font-size:13px; font-weight:600; color:#fff; }
  .who span{ font-size:12px; color:#9A9E9F; }

  /* main */
  .main{ flex:1; padding: 1.8rem 2.2rem; min-width:0; }
  .topbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom: 1.8rem;
  }
  .topbar h1{ font-family:'Space Grotesk', sans-serif; font-size: 21px; font-weight: 700; margin:0; }
  .topbar .sub{ font-size: 13px; color: var(--ink-dim); margin: 2px 0 0; }
  .btn{
    background: var(--yellow);
    color: var(--asphalt);
    border:none;
    border-radius: 8px;
    padding: 0.6rem 1rem;
    font-family:'Space Grotesk', sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor:pointer;
  }
  .btn:hover{ background:#FFD766; }

  /* stat cards */
  .stats{
    display:grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 1.6rem;
  }
  .stat-card{
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 1.1rem 1.2rem;
  }
  .stat-card .label{ font-size: 12px; color: var(--ink-dim); margin-bottom: 0.5rem; }
  .stat-card .value{ font-family:'Space Grotesk', sans-serif; font-size: 23px; font-weight: 700; }
  .stat-card .delta{ font-size: 12px; margin-top: 0.4rem; display:inline-flex; align-items:center; gap:3px; }
  .delta.up{ color: var(--green); }
  .delta.down{ color: var(--danger); }

  /* content grid */
  .content-grid{
    display:grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 14px;
  }
  .panel{
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 1.3rem 1.4rem;
  }
  .panel-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom: 1.2rem;
  }
  .panel-head h2{ font-size: 15px; font-weight: 600; margin:0; }
  .panel-head .sub{ font-size: 12px; color: var(--ink-dim); }

  /* occupancy chart */
  .chart{
    display:flex;
    align-items:flex-end;
    gap: 10px;
    height: 150px;
    padding-top: 10px;
  }
  .bar-col{ flex:1; display:flex; flex-direction:column; align-items:center; gap:6px; }
  .bar{
    width: 100%;
    max-width: 26px;
    background: var(--yellow-bg);
    border-radius: 5px 5px 0 0;
  }
  .bar.now{ background: var(--yellow); }
  .bar-col .lbl{ font-size: 11px; color: var(--ink-dim); }

  /* slot grid */
  .slot-grid{
    display:grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 8px;
    margin-top: 1.4rem;
  }
  .slot{
    aspect-ratio: 1;
    border-radius: 6px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size: 11px;
    font-weight: 600;
  }
  .slot.free{ background: var(--green-bg); color: var(--green); }
  .slot.occupied{ background: var(--asphalt); color: #fff; }
  .slot.reserved{ background: var(--yellow-bg); color: var(--yellow-dim); }
  .legend{ display:flex; gap: 1.2rem; margin-top: 1rem; font-size: 12px; color: var(--ink-dim); }
  .legend-item{ display:flex; align-items:center; gap: 6px; }
  .legend-dot{ width:9px; height:9px; border-radius:3px; }

  /* activity list */
  .activity{ display:flex; flex-direction:column; gap: 0.9rem; }
  .activity-item{ display:flex; gap: 0.7rem; align-items:flex-start; }
  .activity-dot{ width:7px; height:7px; border-radius:50%; background: var(--yellow-dim); margin-top:6px; flex-shrink:0; }
  .activity-item p{ margin:0; font-size:13px; }
  .activity-item span{ font-size:12px; color:var(--ink-dim); }

  /* table */
  .table-wrap{ margin-top: 1.6rem; }
  table{ width:100%; border-collapse: collapse; }
  th{
    text-align:left;
    font-size: 12px;
    color: var(--ink-dim);
    font-weight: 600;
    padding: 0 0.4rem 0.6rem;
    border-bottom: 1px solid var(--line);
  }
  td{
    font-size: 13px;
    padding: 0.7rem 0.4rem;
    border-bottom: 1px solid var(--line);
  }
  tr:last-child td{ border-bottom:none; }
  .badge{
    display:inline-block;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 999px;
  }
  .badge.success{ background: var(--green-bg); color: var(--green); }
  .badge.pending{ background: var(--yellow-bg); color: var(--yellow-dim); }
  .badge.failed{ background: var(--danger-bg); color: var(--danger); }

  @media (max-width: 980px){
    .stats{ grid-template-columns: repeat(2, 1fr); }
    .content-grid{ grid-template-columns: 1fr; }
    .slot-grid{ grid-template-columns: repeat(6, 1fr); }
  }
  @media (max-width: 680px){
    body{ flex-direction:column; }
    .sidebar{ width:100%; flex-direction:row; align-items:center; padding: 1rem; }
    .brand{ margin-bottom:0; }
    nav{ flex-direction:row; overflow-x:auto; margin-left: 1rem; }
    .sidebar-foot{ display:none; }
    .stats{ grid-template-columns: 1fr 1fr; }
    .main{ padding: 1.4rem; }
    .slot-grid{ grid-template-columns: repeat(5, 1fr); }
  }
</style>
<body>
    <aside class="sidebar">
  <div class="brand"><span class="mark">P</span>ParkIn</div>
  <nav>
    <a href="#" class="active"><span class="dot"></span>Ringkasan</a>
    <a href="#"><span class="dot"></span>Lokasi parkir</a>
    <a href="#"><span class="dot"></span>Sesi aktif</a>
    <a href="#"><span class="dot"></span>Pembayaran</a>
    <a href="#"><span class="dot"></span>Pelanggan</a>
    <a href="#"><span class="dot"></span>Pengaturan</a>
  </nav>
  <div class="sidebar-foot">
    <div class="avatar"></div>
    <div class="who">
      <p>Devianeka</p>
      <span>Operator</span>
    </div>
  </div>
</aside>

<main class="main">
  <div class="topbar">
    <div>
      <h1>Ringkasan lokasi</h1>
      <p class="sub">Plaza Sentosa · Senin, 31 Agustus 2026</p>
    </div>
    <button class="btn">Tambah lokasi</button>
  </div>

  <div class="stats">
    <div class="stat-card">
      <div class="label">Pendapatan hari ini</div>
      <div class="value">Rp6,4jt</div>
      <div class="delta up">↑ 9,2% dari kemarin</div>
    </div>
    <div class="stat-card">
      <div class="label">Sesi aktif</div>
      <div class="value">142</div>
      <div class="delta up">↑ 14 sesi baru</div>
    </div>
    <div class="stat-card">
      <div class="label">Tingkat okupansi</div>
      <div class="value">78%</div>
      <div class="delta down">↓ 3% dari jam lalu</div>
    </div>
    <div class="stat-card">
      <div class="label">Rata-rata durasi</div>
      <div class="value">1j 42m</div>
      <div class="delta up">↑ 6 menit</div>
    </div>
  </div>

  <div class="content-grid">
    <div class="panel">
      <div class="panel-head">
        <div>
          <h2>Okupansi mingguan</h2>
          <div class="sub">7 hari terakhir</div>
        </div>
      </div>
      <div class="chart">
        <div class="bar-col"><div class="bar" style="height:70px"></div><span class="lbl">Sen</span></div>
        <div class="bar-col"><div class="bar" style="height:84px"></div><span class="lbl">Sel</span></div>
        <div class="bar-col"><div class="bar" style="height:66px"></div><span class="lbl">Rab</span></div>
        <div class="bar-col"><div class="bar" style="height:98px"></div><span class="lbl">Kam</span></div>
        <div class="bar-col"><div class="bar" style="height:110px"></div><span class="lbl">Jum</span></div>
        <div class="bar-col"><div class="bar" style="height:132px"></div><span class="lbl">Sab</span></div>
        <div class="bar-col"><div class="bar now" style="height:117px"></div><span class="lbl">Ming</span></div>
      </div>

      <div class="panel-head" style="margin-top:1.6rem;">
        <div>
          <h2>Denah slot — Lantai 2</h2>
          <div class="sub">48 slot total</div>
        </div>
      </div>
      <div class="slot-grid" id="slotGrid"></div>
      <div class="legend">
        <div class="legend-item"><span class="legend-dot" style="background:var(--green-bg); border:1px solid var(--green);"></span>Kosong</div>
        <div class="legend-item"><span class="legend-dot" style="background:var(--asphalt);"></span>Terisi</div>
        <div class="legend-item"><span class="legend-dot" style="background:var(--yellow-bg); border:1px solid var(--yellow-dim);"></span>Dipesan</div>
      </div>
    </div>

    <div class="panel">
      <div class="panel-head">
        <div>
          <h2>Aktivitas terbaru</h2>
          <div class="sub">Update langsung</div>
        </div>
      </div>
      <div class="activity">
        <div class="activity-item">
          <div class="activity-dot"></div>
          <div>
            <p>Kendaraan B 1234 XYZ masuk slot A-12</p>
            <span>2 menit lalu</span>
          </div>
        </div>
        <div class="activity-item">
          <div class="activity-dot"></div>
          <div>
            <p>Pembayaran Rp18.000 dari slot B-04 berhasil</p>
            <span>9 menit lalu</span>
          </div>
        </div>
        <div class="activity-item">
          <div class="activity-dot"></div>
          <div>
            <p>Slot C-21 dipesan untuk pukul 15.00</p>
            <span>24 menit lalu</span>
          </div>
        </div>
        <div class="activity-item">
          <div class="activity-dot"></div>
          <div>
            <p>Kendaraan D 5678 ABC keluar dari slot A-06</p>
            <span>1 jam lalu</span>
          </div>
        </div>
        <div class="activity-item">
          <div class="activity-dot"></div>
          <div>
            <p>Pembayaran slot B-11 gagal, menunggu retry</p>
            <span>2 jam lalu</span>
          </div>
        </div>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Slot</th>
              <th>Kendaraan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>A-12</td>
              <td>B 1234 XYZ</td>
              <td><span class="badge success">Aktif</span></td>
            </tr>
            <tr>
              <td>B-04</td>
              <td>B 9981 QRS</td>
              <td><span class="badge success">Aktif</span></td>
            </tr>
            <tr>
              <td>C-21</td>
              <td>—</td>
              <td><span class="badge pending">Dipesan</span></td>
            </tr>
            <tr>
              <td>B-11</td>
              <td>D 5521 LMN</td>
              <td><span class="badge failed">Gagal bayar</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<script>
  const states = ['free','free','occupied','occupied','free','reserved','occupied','free',
                   'occupied','free','free','occupied','reserved','occupied','free','occupied',
                   'free','occupied','occupied','free','free','occupied','free','reserved',
                   'occupied','free','occupied','free','occupied','free','free','occupied',
                   'free','occupied','occupied','free','reserved','free','occupied','free',
                   'occupied','free','free','occupied','free','occupied','free','occupied'];
  const grid = document.getElementById('slotGrid');
  states.forEach((state, i)=>{
    const el = document.createElement('div');
    el.className = 'slot ' + state;
    el.textContent = i + 1;
    grid.appendChild(el);
  });
</script>
</body>
</html>
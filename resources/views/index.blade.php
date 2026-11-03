<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Login Parkir</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
@vite('resources/css/app.css')
<style>
:root{--asphalt:#202325;--yellow:#FFC93C;--yellow-dim:#B08B2A;--paper:#fff;--bg:#F5F5F2;--ink:#202325;--ink-dim:#767B7E;--line:#E4E4E2;--danger:#B4442E}
*{box-sizing:border-box}html,body{margin:0;padding:0}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--ink);min-height:100vh}
button{font-family:'Inter',sans-serif}
#loginScreen{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;background:var(--asphalt);background-image:repeating-linear-gradient(115deg,transparent 0 64px,rgba(255,201,60,.06) 64px 68px)}
.login-frame{width:100%;max-width:400px;background:var(--paper);border-radius:16px;padding:2.4rem 2.2rem;box-shadow:0 24px 60px rgba(0,0,0,.35)}
.login-brand{display:flex;align-items:center;gap:.6rem;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:17px;margin-bottom:1.6rem}
.login-brand .mark{width:30px;height:30px;border-radius:8px;background:var(--yellow);display:flex;align-items:center;justify-content:center;color:var(--asphalt);font-weight:700;font-size:15px}
.login-frame h1{font-family:'Space Grotesk',sans-serif;font-size:21px;margin:0 0 .3rem}
.login-frame .sub{font-size:13px;color:var(--ink-dim);margin:0 0 1.6rem}
.role-tabs{display:flex;background:#F0F0EC;border-radius:10px;padding:4px;margin-bottom:1.6rem}
.role-tab{flex:1;text-align:center;padding:.55rem .4rem;border-radius:8px;font-size:13px;font-weight:600;color:var(--ink-dim);background:none;border:none;cursor:pointer}
.role-tab.active{background:var(--paper);color:var(--ink);box-shadow:0 1px 4px rgba(0,0,0,.08)}
.field{display:flex;flex-direction:column;gap:.4rem;margin-bottom:1.1rem}
.field label{font-size:12px;font-weight:600}
.field input{background:#FAFAF9;border:1.5px solid var(--line);border-radius:8px;padding:.7rem .85rem;font-size:14px;color:var(--ink);outline:none;font-family:inherit;width:100%}
.field input:focus{border-color:var(--yellow-dim);background:#fff}
.field-error input{border-color:var(--danger);background:#FBECE8}
.pass-wrap{position:relative}.pass-wrap input{padding-right:2.8rem}.eye{position:absolute;right:6px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--ink-dim);padding:6px;display:flex;border-radius:6px}.eye:hover{color:var(--ink)}.eye svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.error-msg{font-size:12px;color:var(--danger);min-height:14px}
.btn{pointer-events:auto;background:var(--yellow);color:var(--asphalt);border:none;border-radius:8px;padding:.75rem 1rem;font-size:13px;font-weight:700;cursor:pointer;font-family:'Space Grotesk',sans-serif}
.btn:hover{background:#FFD766}.btn:disabled{opacity:.6;cursor:not-allowed}.btn.block{width:100%}
</style>
</head>
<body>
<div id="loginScreen">
  <div class="login-frame">
    <div class="login-brand"><span class="mark">P</span>Parkir</div>
    <h1>Masuk ke sistem</h1>
    <p class="sub">Pilih peran dan masuk sesuai akun kamu.</p>

    <div class="role-tabs" id="roleTabs">
      <button type="button" class="role-tab active" data-role="admin">Admin</button>
      <button type="button" class="role-tab" data-role="petugas">Petugas</button>
      <button type="button" class="role-tab" data-role="owner">Owner</button>
    </div>

    <form id="loginForm" novalidate>
      <div class="field" id="nameField">
        <label>Nama kamu</label>
        <input type="text" id="loginName" placeholder="Masukkan nama lengkap" autocomplete="name">
        <div class="error-msg" id="nameError"></div>
      </div>
      <div class="field" id="userField">
        <label>Username</label>
        <input type="text" id="loginUser" placeholder="Masukkan username" autocomplete="username">
        <div class="error-msg" id="userError"></div>
      </div>
      <div class="field" id="passField">
        <label>Kata sandi</label>
        <div class="pass-wrap">
          <input type="password" id="loginPass" placeholder="Masukkan kata sandi" autocomplete="current-password">
          <button type="button" class="eye" id="togglePass" aria-label="Tampilkan kata sandi" title="Tampilkan kata sandi"><svg viewBox="0 0 24 24" id="eyeIcon" aria-hidden="true"></svg></button>
        </div>
        <div class="error-msg" id="passError"></div>
      </div>
      <button type="submit" class="btn block" id="btnLogin">Masuk</button>
    </form>
  </div>
</div>

<script>
const EYE = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
const EYE_OFF = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
document.getElementById('eyeIcon').innerHTML = EYE;
document.getElementById('togglePass').addEventListener('click', () => {
  const input = document.getElementById('loginPass'), show = input.type === 'password';
  input.type = show ? 'text' : 'password';
  document.getElementById('eyeIcon').innerHTML = show ? EYE_OFF : EYE;
  const label = show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi';
  const btn = document.getElementById('togglePass'); btn.setAttribute('aria-label', label); btn.title = label;
  input.focus();
});

let selectedRole = 'admin';

document.getElementById('roleTabs').addEventListener('click', e => {
  const tab = e.target.closest('.role-tab');
  if (!tab) return;
  document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
  tab.classList.add('active');
  selectedRole = tab.dataset.role;
});

document.getElementById('loginForm').addEventListener('submit', async e => {
  e.preventDefault();
  const $ = id => document.getElementById(id);
  const nameVal = $('loginName').value.trim(), userVal = $('loginUser').value.trim(), passVal = $('loginPass').value;
  ['name','user','pass'].forEach(k => { $(k+'Error').textContent = ''; $(k+'Field').classList.remove('field-error'); });

  const fail = (k, msg) => { $(k+'Field').classList.add('field-error'); $(k+'Error').textContent = msg; };
  if (!nameVal) return fail('name', 'Nama wajib diisi.');
  if (!userVal) return fail('user', 'Username wajib diisi.');
  if (!passVal) return fail('pass', 'Kata sandi wajib diisi.');

  $('btnLogin').disabled = true;
  try {
    const response = await fetch('/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({ name: nameVal, username: userVal, password: passVal, role: selectedRole }),
    });
    const result = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(result.message || 'Username, kata sandi, atau peran tidak cocok.');

    const params = new URLSearchParams({ name: result.name || nameVal, username: result.username, role: result.role });
    window.location.href = '/dhasboard?' + params.toString();
  } catch (err) {
    fail('pass', err.message);
    $('btnLogin').disabled = false;
  }
});
</script>
</body>
</html>
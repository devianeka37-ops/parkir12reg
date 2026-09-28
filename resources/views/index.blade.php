<!DOCTYPE html>
<html>
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
 
/* ================= LOGIN ================= */
#loginScreen{
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding: 2rem;
  background: var(--asphalt);
  background-image: repeating-linear-gradient(115deg, transparent 0 64px, rgba(255,201,60,0.06) 64px 68px);
}
.login-frame{
  width:100%;
  max-width: 400px;
  background: var(--paper);
  border-radius: 16px;
  padding: 2.4rem 2.2rem;
  box-shadow: 0 24px 60px rgba(0,0,0,0.35);
}
.login-brand{
  display:flex; align-items:center; gap:0.6rem;
  font-family:'Space Grotesk', sans-serif; font-weight:700; font-size:17px;
  margin-bottom: 1.6rem;
}
.login-brand .mark{
  width:30px; height:30px; border-radius:8px; background: var(--yellow);
  display:flex; align-items:center; justify-content:center;
  color: var(--asphalt); font-weight:700; font-size:15px;
}
.login-frame h1{ font-family:'Space Grotesk', sans-serif; font-size:21px; margin:0 0 0.3rem; }
.login-frame .sub{ font-size:13px; color:var(--ink-dim); margin:0 0 1.6rem; }
 
.role-tabs{
  display:flex;
  background: #F0F0EC;
  border-radius: 10px;
  padding: 4px;
  margin-bottom: 1.6rem;
}
.role-tab{
  flex:1;
  text-align:center;
  padding: 0.55rem 0.4rem;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  color: var(--ink-dim);
  background:none;
  border:none;
  cursor:pointer;
}
.role-tab.active{ background: var(--paper); color: var(--ink); box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
 
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
.hint-box{
  background: var(--yellow-bg); border:1px solid #F0DFAF; border-radius:8px;
  padding: 0.7rem 0.85rem; font-size:12px; color:#7A5F1E; margin-bottom:1.2rem; line-height:1.5;
}
.hint-box b{ display:block; margin-bottom:2px; }
 
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
</style>
</head>
<body>
 
<!-- ======================================================= -->
<!-- LOGIN SCREEN                                             -->
<!-- ======================================================= -->
<div id="loginScreen">
  <div class="login-frame">
    <div class="login-brand"><span class="mark">P</span>Parkir</div>
    <h1>Masuk ke sistem</h1>
    <p class="sub">Pilih peran dan masuk sesuai akun kamu.</p>
 
    <div class="role-tabs" id="roleTabs">
      <button class="role-tab active" data-role="admin">Admin</button>
      <button class="role-tab" data-role="petugas">Petugas</button>
      <button class="role-tab" data-role="owner">Owner</button>
    </div>
 
    <div class="hint-box" id="loginHint">
      <b>Akun demo Admin</b>
      Username: <b style="display:inline">admin</b> · Kata sandi: <b style="display:inline">admin123</b>
    </div>
 
    <form id="loginForm">
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
        <input type="password" id="loginPass" placeholder="Masukkan kata sandi" autocomplete="current-password">
        <div class="error-msg" id="passError"></div>
      </div>
      <button type="submit" class="btn block">Masuk</button>
    </form>
  </div>
</div>
 
<script>
/* ============================================================
   AKUN — sama seperti dashboard, tidak ada backend/database
   di belakang file ini, jadi disalin di sisi login untuk
   memvalidasi kredensial sebelum diarahkan ke dashboard.
   ============================================================ */
const accounts = [
  {id:1, name:'Devian Eka', username:'admin', password:'admin123', role:'Admin', status:'Aktif'},
  {id:2, name:'Joko Susilo', username:'petugas', password:'petugas123', role:'Petugas', status:'Aktif'},
  {id:3, name:'Hendra Wijaya', username:'owner', password:'owner123', role:'Owner', status:'Aktif'},
  {id:4, name:'Rina Marlina', username:'petugas2', password:'petugas123', role:'Petugas', status:'Aktif'},
];

try {
  const savedData = JSON.parse(localStorage.getItem('parkinDashboardData') || 'null');
  savedData?.accounts?.forEach(savedAccount => {
    const accountIndex = accounts.findIndex(account => account.username === savedAccount.username);
    if(accountIndex === -1){
      accounts.push(savedAccount);
    } else {
      accounts[accountIndex] = savedAccount;
    }
  });
} catch (error) {
  localStorage.removeItem('parkinDashboardData');
}
 
const roleHints = {
  admin: {label:'Admin', user:'admin', pass:'admin123'},
  petugas: {label:'Petugas', user:'petugas', pass:'petugas123'},
  owner: {label:'Owner', user:'owner', pass:'owner123'},
};
let selectedRole = 'admin';
 
document.getElementById('roleTabs').addEventListener('click', (e)=>{
  const tab = e.target.closest('.role-tab');
  if(!tab) return;
  document.querySelectorAll('.role-tab').forEach(t=>t.classList.remove('active'));
  tab.classList.add('active');
  selectedRole = tab.dataset.role;
  const h = roleHints[selectedRole];
  document.getElementById('loginHint').innerHTML = '<b>Akun demo ' + h.label + '</b>Username: <b style="display:inline">' + h.user + '</b> · Kata sandi: <b style="display:inline">' + h.pass + '</b>';
});
 
document.getElementById('loginForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const nameVal = document.getElementById('loginName').value.trim();
  const userVal = document.getElementById('loginUser').value.trim();
  const passVal = document.getElementById('loginPass').value;
  const nameField = document.getElementById('nameField');
  const userField = document.getElementById('userField');
  const passField = document.getElementById('passField');
  document.getElementById('nameError').textContent = '';
  document.getElementById('userError').textContent = '';
  document.getElementById('passError').textContent = '';
  nameField.classList.remove('field-error');
  userField.classList.remove('field-error');
  passField.classList.remove('field-error');
 
  if(!nameVal){
    nameField.classList.add('field-error');
    document.getElementById('nameError').textContent = 'Nama wajib diisi.';
    return;
  }
  if(!userVal){
    userField.classList.add('field-error');
    document.getElementById('userError').textContent = 'Username wajib diisi.';
    return;
  }
  if(!passVal){
    passField.classList.add('field-error');
    document.getElementById('passError').textContent = 'Kata sandi wajib diisi.';
    return;
  }
 
  const roleLabel = roleHints[selectedRole].label;
  try {
    const response = await fetch('/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        username: userVal,
        password: passVal,
        role: roleLabel.toLowerCase(),
      }),
    });
    const result = await response.json();

    if(!response.ok){
      throw new Error(result.message || 'Username, kata sandi, atau peran tidak cocok.');
    }

    const params = new URLSearchParams({
      name: result.name || nameVal,
      username: result.username,
      role: result.role,
    });
    window.location.href = '/dhasboard?' + params.toString();
  } catch (loginError) {
    passField.classList.add('field-error');
    document.getElementById('passError').textContent = loginError.message;
  }
});
</script>
</body>
</html>
<!DOCTYPE html>
<html>
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
    --paper: #FFFFFF;
    --ink: #202325;
    --ink-dim: #767B7E;
    --line: #E4E4E2;
    --danger: #B4442E;
  }
  *{ box-sizing:border-box; }
  html,body{ margin:0; padding:0; }
  body{
    font-family:'Inter', sans-serif;
    background: var(--asphalt);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding: 2rem;
  }
  .frame{
    width:100%;
    max-width: 940px;
    display:grid;
    grid-template-columns: 1.1fr 1fr;
    border-radius: 16px;
    overflow:hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.35);
  }

  /* hero */
  .hero{
    background: var(--asphalt);
    background-image: repeating-linear-gradient(115deg, transparent 0 64px, rgba(255,201,60,0.07) 64px 68px);
    padding: 3rem 2.6rem;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    color: #fff;
    position:relative;
  }
  .brand{
    display:flex;
    align-items:center;
    gap: 0.6rem;
    font-family:'Space Grotesk', sans-serif;
    font-weight: 700;
    font-size: 18px;
  }
  .brand .mark{
    width: 28px; height: 28px;
    background: var(--yellow);
    border-radius: 6px;
    display:flex;
    align-items:center;
    justify-content:center;
    color: var(--asphalt);
    font-weight:700;
    font-size: 14px;
  }

  .gate{
    display:flex;
    justify-content:center;
    padding: 1.5rem 0;
  }
  .gate svg{ width: 100%; max-width: 280px; height:auto; }
  .barrier-arm{
    transform-origin: 30px 78px;
    animation: lift 1.6s cubic-bezier(.3,.9,.3,1) 1;
  }
  @keyframes lift{
    0%{ transform: rotate(0deg); }
    100%{ transform: rotate(-72deg); }
  }

  .hero-copy h1{
    font-family:'Space Grotesk', sans-serif;
    font-weight: 700;
    font-size: 28px;
    line-height: 1.25;
    margin: 0 0 0.6rem;
  }
  .hero-copy p{
    font-size: 14px;
    line-height: 1.6;
    color: #C6C9CB;
    margin: 0;
    max-width: 30ch;
  }

  /* ticket card */
  .ticket{
    background: var(--paper);
    padding: 2.8rem 2.4rem;
    position:relative;
    display:flex;
    flex-direction:column;
    justify-content:center;
  }
  .ticket::before, .ticket::after{
    content:"";
    position:absolute;
    left:-9px;
    width: 18px; height: 18px;
    background: var(--asphalt);
    border-radius: 50%;
  }
  .ticket::before{ top: -9px; }
  .ticket::after{ bottom: -9px; }
  .perf{
    position:absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 1px;
    background-image: linear-gradient(var(--line) 60%, transparent 0%);
    background-size: 1px 10px;
    background-repeat: repeat-y;
  }

  .ticket h2{
    font-family:'Space Grotesk', sans-serif;
    font-weight: 700;
    font-size: 22px;
    margin: 0 0 0.35rem;
    color: var(--ink);
  }
  .ticket .sub{
    font-size: 13px;
    color: var(--ink-dim);
    margin: 0 0 1.8rem;
  }

  form{ display:flex; flex-direction:column; gap: 1.1rem; }
  .field{ display:flex; flex-direction:column; gap: 0.4rem; }
  .field label{
    font-size: 12px;
    font-weight: 600;
    color: var(--ink);
  }
  .field input{
    background: #FAFAF9;
    border: 1.5px solid var(--line);
    border-radius: 8px;
    padding: 0.72rem 0.85rem;
    font-family:'Inter', sans-serif;
    font-size: 14px;
    color: var(--ink);
    outline:none;
    transition: border-color .12s ease, background .12s ease;
  }
  .field input::placeholder{ color: #B0B0AC; }
  .field input:focus{
    border-color: var(--yellow-dim);
    background: #fff;
  }
  .field-error input{ border-color: var(--danger); background: #FBECE8; }
  .error-msg{
    font-size: 12px;
    color: var(--danger);
    min-height: 14px;
  }

  .row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    font-size: 13px;
  }
  .remember{ display:flex; align-items:center; gap: 0.5rem; color: var(--ink-dim); }
  .remember input{ accent-color: var(--yellow-dim); width:14px; height:14px; }
  a.link{ color: var(--yellow-dim); text-decoration:none; font-weight:600; }
  a.link:hover{ text-decoration:underline; }

  button.submit{
    margin-top: 0.3rem;
    background: var(--yellow);
    color: var(--asphalt);
    border: none;
    border-radius: 8px;
    padding: 0.85rem 1rem;
    font-family:'Space Grotesk', sans-serif;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: background .12s ease, transform .1s ease;
  }
  button.submit:hover{ background: #FFD766; }
  button.submit:active{ transform: scale(0.99); }

  .footer-note{
    font-size: 13px;
    color: var(--ink-dim);
    text-align:center;
    margin-top: 1.6rem;
  }
  button:focus-visible, input:focus-visible, a:focus-visible{
    outline: 2px solid var(--yellow-dim);
    outline-offset: 2px;
  }

  @media (max-width: 760px){
    .frame{ grid-template-columns: 1fr; border-radius: 12px; }
    .hero{ padding: 2.2rem 1.8rem; }
    .ticket{ padding: 2.4rem 1.8rem; }
    .ticket::before, .ticket::after{ display:none; }
  }
  @media (prefers-reduced-motion: reduce){
    .barrier-arm{ animation:none; transform: rotate(-72deg); }
  }
</style>
<body>
    <div class="frame">
  <div class="hero">
    <div class="brand"><span class="mark">P</span>ParkIn</div>

    <div class="gate">
      <svg viewBox="0 0 220 130" role="img" aria-label="Ilustrasi palang parkir terbuka">
        <rect x="10" y="78" width="40" height="34" rx="3" fill="#3A3F42"></rect>
        <circle cx="30" cy="78" r="4" fill="#FFC93C"></circle>
        <g class="barrier-arm">
          <rect x="26" y="74" width="150" height="8" rx="4" fill="#FFC93C"></rect>
          <rect x="26" y="74" width="20" height="8" fill="#202325"></rect>
          <rect x="66" y="74" width="20" height="8" fill="#202325"></rect>
          <rect x="106" y="74" width="20" height="8" fill="#202325"></rect>
          <rect x="146" y="74" width="20" height="8" fill="#202325"></rect>
        </g>
        <rect x="6" y="112" width="208" height="6" rx="3" fill="#3A3F42"></rect>
      </svg>
    </div>

    <div class="hero-copy">
      <h1>Parkir tanpa ribet, cukup satu tap.</h1>
      <p>Cari slot kosong, bayar otomatis, dan pantau riwayat parkirmu dalam satu aplikasi.</p>
    </div>
  </div>

  <div class="ticket">
    <div class="perf"></div>
    <h2>Masuk akun</h2>
    <p class="sub">Gunakan akun ParkIn kamu untuk melanjutkan.</p>

    <form id="loginForm" novalidate>
      <div class="field" id="emailField">
        <label for="email">Email atau nomor HP</label>
        <input type="text" id="email" name="email" placeholder="nama@email.com" autocomplete="username">
        <div class="error-msg" id="emailError"></div>
      </div>

      <div class="field" id="passField">
        <label for="password">Kata sandi</label>
        <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password">
        <div class="error-msg" id="passError"></div>
      </div>

      <div class="row">
        <label class="remember">
          <input type="checkbox" id="remember">
          Ingat saya
        </label>
        <a class="link" href="#">Lupa sandi?</a>
      </div>

      <button type="submit" class="submit">Masuk</button>
    </form>

    <p class="footer-note">Belum punya akun? <a class="link" href="#">Daftar sekarang</a></p>
  </div>
</div>

<script>
  const form = document.getElementById('loginForm');
  const emailInput = document.getElementById('email');
  const passInput = document.getElementById('password');
  const emailField = document.getElementById('emailField');
  const passField = document.getElementById('passField');
  const emailError = document.getElementById('emailError');
  const passError = document.getElementById('passError');

  function validIdentity(v){
    return v.trim().length >= 3;
  }

  form.addEventListener('submit', function(e){
    e.preventDefault();
    let ok = true;

    if(!validIdentity(emailInput.value)){
      emailField.classList.add('field-error');
      emailError.textContent = 'Masukkan email atau nomor HP yang valid.';
      ok = false;
    } else {
      emailField.classList.remove('field-error');
      emailError.textContent = '';
    }

    if(!passInput.value){
      passField.classList.add('field-error');
      passError.textContent = 'Kata sandi wajib diisi.';
      ok = false;
    } else if(passInput.value.length < 6){
      passField.classList.add('field-error');
      passError.textContent = 'Minimal 6 karakter.';
      ok = false;
    } else {
      passField.classList.remove('field-error');
      passError.textContent = '';
    }

    if(ok){
      const btn = form.querySelector('.submit');
      btn.textContent = 'Memproses...';
      setTimeout(()=>{ btn.textContent = 'Berhasil masuk'; }, 900);
    }
  });

  emailInput.addEventListener('input', ()=>{
    if(emailField.classList.contains('field-error') && validIdentity(emailInput.value)){
      emailField.classList.remove('field-error');
      emailError.textContent = '';
    }
  });
  passInput.addEventListener('input', ()=>{
    if(passField.classList.contains('field-error') && passInput.value.length >= 6){
      passField.classList.remove('field-error');
      passError.textContent = '';
    }
  });
</script>
</body>
</html>
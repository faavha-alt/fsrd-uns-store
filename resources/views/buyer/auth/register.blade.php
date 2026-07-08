<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar — FSRD UNS Store</title>
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    <style>
        body { margin:0; padding:0; }
        .login-bg {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #0A3D52;
            position: relative;
            overflow: hidden;
        }
        .login-bg::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: rgba(31,171,225,0.08);
            top: -200px; right: -200px;
        }
        .login-bg::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(233,168,40,0.06);
            bottom: -150px; left: -100px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            box-shadow: 0 24px 80px rgba(0,0,0,0.3);
        }
        .login-logo {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #E9A828, #FFDB07);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Montserrat', sans-serif;
            font-weight: 900; color: white; font-size: 26px;
            margin: 0 auto 18px;
            box-shadow: 0 6px 20px rgba(233,168,40,0.4);
        }
        .login-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 22px; font-weight: 800;
            color: #0A3D52; text-align: center;
            margin-bottom: 4px;
        }
        .login-subtitle {
            font-size: 12px; color: #94A3B8;
            text-align: center; margin-bottom: 28px;
        }
        .btn-google {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 12px;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px; background: white;
            text-decoration: none; font-size: 13px;
            font-weight: 600; color: #1A1A2E;
            transition: all 0.2s; margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
        }
        .btn-google:hover {
            border-color: #4285F4;
            background: #F8F9FF;
            box-shadow: 0 2px 8px rgba(66,133,244,0.15);
        }
        .divider {
            display: flex; align-items: center; gap: 12px; margin-bottom: 20px;
        }
        .divider-line { flex: 1; height: 1px; background: #F1F5F9; }
        .divider-text { font-size: 11px; color: #CBD5E1; font-weight: 500; white-space: nowrap; }
        .form-label {
            font-size: 12px; font-weight: 600;
            color: #475569; margin-bottom: 6px; display: block;
        }
        .form-input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px; font-size: 13px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s; outline: none;
            color: #1A1A2E; background: #FAFAFA;
            box-sizing: border-box;
        }
        .form-input:focus {
            border-color: #0E7DA7;
            background: white;
            box-shadow: 0 0 0 3px rgba(14,125,167,0.1);
        }
        .input-wrap { position: relative; }
        .toggle-pw {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #94A3B8; font-size: 16px; padding: 4px;
        }
        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #E9A828, #FFDB07);
            color: white; border: none; border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px; font-weight: 700;
            cursor: pointer; margin-top: 4px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(233,168,40,0.35);
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(233,168,40,0.45);
        }
        .btn-submit:active { transform: translateY(0); }
        .form-group { margin-bottom: 14px; }
        .alert-danger {
            background: #FEE2E2; color: #991B1B;
            border-radius: 8px; padding: 10px 14px;
            font-size: 12px; margin-bottom: 16px;
            border-left: 3px solid #EF4444;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
    </style>
</head>
<body>
<div class="login-bg">
    <div class="login-card">

        {{-- Brand --}}
        <div class="login-logo">F</div>
        <h1 class="login-title">Buat Akun Baru</h1>
        <p class="login-subtitle">Bergabung dengan FSRD UNS Store sekarang</p>

        {{-- Flash Messages --}}
        @if($errors->any())
            <div class="alert-danger">{{ $errors->first() }}</div>
        @endif

        {{-- Google Button --}}
        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="18" height="18" viewBox="0 0 48 48">
                <path fill="#FFC107" d="M43.6 20.1H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.6-.4-3.9z"/>
                <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 16.1 19 13 24 13c3.1 0 5.8 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.4 6.3 14.7z"/>
                <path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.3 35.5 26.8 36.5 24 36.5c-5.2 0-9.6-3.3-11.3-8H6.4C9.9 36.2 16.4 44 24 44z"/>
                <path fill="#1976D2" d="M43.6 20.1H42V20H24v8h11.3c-.8 2.3-2.3 4.2-4.2 5.7l6.2 5.2C37 39 44 34 44 24c0-1.3-.1-2.6-.4-3.9z"/>
            </svg>
            Daftar dengan Google
        </a>

        {{-- Divider --}}
        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">atau daftar dengan email</span>
            <div class="divider-line"></div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('buyer.register.submit') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-input"
                    value="{{ old('name') }}"
                    placeholder="Nama lengkap Anda" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input"
                    value="{{ old('email') }}"
                    placeholder="email@contoh.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-input"
                    value="{{ old('phone') }}"
                    placeholder="081234567890">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <input type="password" name="password" id="pw1"
                            class="form-input" placeholder="Min. 8 karakter"
                            required style="padding-right:42px;">
                        <button type="button" class="toggle-pw"
                            onclick="togglePw('pw1', this)">👁</button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi</label>
                    <div class="input-wrap">
                        <input type="password" name="password_confirmation" id="pw2"
                            class="form-input" placeholder="Ulangi password"
                            required style="padding-right:42px;">
                        <button type="button" class="toggle-pw"
                            onclick="togglePw('pw2', this)">👁</button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">Buat Akun →</button>
        </form>

        {{-- Footer Links --}}
        <div style="text-align:center; margin-top:20px; padding-top:20px; border-top:1px solid #F1F5F9;">
            <p style="font-size:13px; color:#94A3B8; margin-bottom:8px;">
                Sudah punya akun?
                <a href="{{ route('buyer.login') }}"
                   style="color:#0E7DA7; font-weight:700;">Masuk sekarang</a>
            </p>
            <a href="{{ route('home') }}"
               style="font-size:12px; color:#CBD5E1; text-decoration:none;">
                ← Kembali ke Beranda
            </a>
        </div>

    </div>
</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁';
    }
}
</script>
</body>
</html>
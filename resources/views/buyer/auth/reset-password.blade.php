<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password — FSRD UNS Store</title>
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
</head>
<body>
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center;
    background:linear-gradient(135deg, var(--cerulean) 0%, var(--cerulean-dark) 50%, var(--cerulean-deeper) 100%);
    padding:20px;">
    <div style="background:white; border-radius:16px; padding:40px; width:100%; max-width:420px;
        box-shadow:0 20px 60px rgba(0,0,0,0.2);">

        <div style="text-align:center; margin-bottom:28px;">
            <div style="width:56px; height:56px; background:var(--gold); border-radius:12px;
                display:flex; align-items:center; justify-content:center;
                font-size:26px; margin:0 auto 14px;">🔑</div>
            <h1 style="font-family:'Montserrat',sans-serif; font-size:22px; font-weight:800;
                color:var(--cerulean-dark); margin-bottom:4px;">Buat Password Baru</h1>
            <p style="font-size:13px; color:var(--muted);">Masukkan password baru untuk akun Anda.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('buyer.reset-password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control"
                    placeholder="Minimal 8 karakter" required>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="Ulangi password baru" required>
            </div>
            <button type="submit" class="login-btn">
                ✅ Reset Password
            </button>
        </form>

        <p style="text-align:center; font-size:12px; margin-top:16px;">
            <a href="{{ route('home') }}" style="color:var(--muted);">← Kembali ke Beranda</a>
        </p>
    </div>
</div>
</body>
</html>

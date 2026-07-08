<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password — FSRD UNS Store</title>
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
                font-size:26px; margin:0 auto 14px;">🔐</div>
            <h1 style="font-family:'Montserrat',sans-serif; font-size:22px; font-weight:800;
                color:var(--cerulean-dark); margin-bottom:4px;">Lupa Password?</h1>
            <p style="font-size:13px; color:var(--muted);">
                Masukkan email Anda dan kami akan mengirimkan link reset password.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('buyer.forgot-password.send') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email') }}" placeholder="email@contoh.com" required autofocus>
            </div>
            <button type="submit" class="login-btn">
                📤 Kirim Link Reset Password
            </button>
        </form>

        <p style="text-align:center; font-size:13px; color:var(--muted); margin-top:20px;">
            Ingat password?
            <a href="{{ route('buyer.login') }}" style="color:var(--cerulean); font-weight:600;">Login di sini</a>
        </p>
        <p style="text-align:center; font-size:12px; margin-top:8px;">
            <a href="{{ route('home') }}" style="color:var(--muted);">← Kembali ke Beranda</a>
        </p>
    </div>
</div>
</body>
</html>

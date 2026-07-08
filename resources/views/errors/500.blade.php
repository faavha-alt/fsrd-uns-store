<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 — Server Error | FSRD UNS Store</title>
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
</head>
<body>
<nav class="navbar">
    <div class="nav-brand">
        <div class="nav-logo">F</div>
        <div class="nav-title">
            <strong>FSRD UNS Store</strong>
            <small>Seni Rupa & Desain</small>
        </div>
    </div>
</nav>

<div style="min-height:80vh; display:flex; align-items:center; justify-content:center; padding:40px 20px;">
    <div style="text-align:center; max-width:480px;">

        <div style="font-size:72px; margin-bottom:20px;">⚠️</div>

        <div style="font-family:'Montserrat',sans-serif; font-size:80px; font-weight:900;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            line-height:1; margin-bottom:16px;">
            500
        </div>

        <h1 style="font-family:'Montserrat',sans-serif; font-size:24px; font-weight:800;
            color:var(--cerulean-dark); margin-bottom:12px;">
            Terjadi Kesalahan Server
        </h1>
        <p style="font-size:14px; color:var(--muted); line-height:1.7; margin-bottom:32px;">
            Sistem sedang mengalami gangguan sementara. Tim kami sedang bekerja untuk memperbaikinya.
            Silakan coba beberapa saat lagi.
        </p>

        <div style="display:flex; gap:12px; justify-content:center;">
            <a href="{{ url('/') }}" class="btn btn-primary" style="padding:12px 24px;">
                ← Kembali ke Beranda
            </a>
            <button onclick="window.location.reload()" class="btn btn-outline" style="padding:12px 24px;">
                🔄 Coba Lagi
            </button>
        </div>
    </div>
</div>
</body>
</html>

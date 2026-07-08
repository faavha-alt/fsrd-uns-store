<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Halaman Tidak Ditemukan | FSRD UNS Store</title>
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

        {{-- Ilustrasi 404 --}}
        <div style="position:relative; margin-bottom:32px;">
            <div style="font-family:'Montserrat',sans-serif; font-size:120px; font-weight:900;
                background: linear-gradient(135deg, var(--cerulean) 0%, var(--sky) 100%);
                -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                line-height:1; margin-bottom:0;">
                404
            </div>
            <div style="font-size:56px; position:absolute; top:50%; left:50%;
                transform:translate(-50%,-50%); opacity:0.15; font-size:140px;">
                🎨
            </div>
        </div>

        <h1 style="font-family:'Montserrat',sans-serif; font-size:24px; font-weight:800;
            color:var(--cerulean-dark); margin-bottom:12px;">
            Halaman Tidak Ditemukan
        </h1>
        <p style="font-size:14px; color:var(--muted); line-height:1.7; margin-bottom:32px;">
            Sepertinya halaman yang kamu cari sudah dipindahkan, dihapus, atau mungkin tidak pernah ada.
            Tapi jangan khawatir — masih banyak karya indah yang bisa kamu temukan!
        </p>

        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
            <a href="{{ url('/') }}" class="btn btn-primary" style="padding:12px 24px;">
                ← Kembali ke Beranda
            </a>
            <a href="{{ route('lapak.index') }}" class="btn btn-outline" style="padding:12px 24px;">
                Jelajahi Lapak
            </a>
        </div>

        {{-- Saran halaman --}}
        <div style="margin-top:40px; padding-top:28px; border-top:1px solid var(--border);">
            <p style="font-size:12px; color:var(--muted); margin-bottom:14px; text-transform:uppercase; letter-spacing:0.08em; font-weight:600;">
                Mungkin kamu mencari
            </p>
            <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                <a href="{{ route('lapak.index') }}"
                   style="padding:6px 14px; background:var(--sky-pale); color:var(--cerulean); border-radius:100px; font-size:12px; font-weight:600; text-decoration:none;">
                    🏺 Lapak Seni
                </a>
                <a href="{{ route('pelatihan.index') }}"
                   style="padding:6px 14px; background:var(--gold-pale); color:var(--cerulean-dark); border-radius:100px; font-size:12px; font-weight:600; text-decoration:none;">
                    🎓 Pelatihan
                </a>
                <a href="{{ route('tentang') }}"
                   style="padding:6px 14px; background:var(--cream); color:var(--muted); border-radius:100px; font-size:12px; font-weight:600; text-decoration:none; border:1px solid var(--border);">
                    ℹ️ Tentang Kami
                </a>
            </div>
        </div>

    </div>
</div>

<footer class="footer">
    <div class="footer-bottom" style="text-align:center; justify-content:center;">
        <span>© {{ date('Y') }} FSRD UNS Store — Universitas Sebelas Maret</span>
    </div>
</footer>

</body>
</html>

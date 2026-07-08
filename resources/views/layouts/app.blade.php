<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FSRD UNS Store') — Seni Rupa & Desain UNS</title>
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}?v={{ filemtime(public_path('css/frontend.css')) }}">
    @stack('styles')
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">

    {{-- BRAND --}}
    <div class="nav-brand">
        <div class="nav-logo">F</div>
        <div class="nav-title">
            <strong>FSRD UNS Store</strong>
            <small>Seni Rupa & Desain</small>
        </div>
    </div>

    {{-- MENU LINKS --}}
    <div class="nav-links">
    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
    <a href="{{ route('lapak.index') }}" class="nav-link {{ request()->routeIs('lapak.*') ? 'active' : '' }}">Lapak</a>
    <a href="{{ route('pelatihan.index') }}" class="nav-link {{ request()->routeIs('pelatihan.*') ? 'active' : '' }}">Pelatihan</a>
    <a href="{{ route('creator.index') }}" class="nav-link {{ request()->routeIs('creator.*') ? 'active' : '' }}">Kreator</a>
    <a href="{{ route('tentang') }}" class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}">Tentang</a>
    </div>

    {{-- SEARCH BAR --}}
    <form action="{{ route('lapak.index') }}" method="GET" class="nav-search">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari produk atau kreator..."
            class="nav-search-input">
        <button type="submit" class="nav-search-btn">🔍</button>
    </form>

    {{-- ACTIONS --}}
    <div class="nav-actions">
        <a href="{{ route('cart.index') }}" class="nav-cart">
            🛒
            @if(session('cart') && count(session('cart')) > 0)
                <span class="cart-badge">{{ count(session('cart')) }}</span>
            @endif
        </a>
        @auth
            @if(auth()->user()->role->value === 'buyer')
                <a href="{{ route('buyer.account') }}" class="btn-login">Akun Saya</a>
                <form method="POST" action="{{ route('buyer.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-register">Keluar</button>
                </form>
            @else
                <a href="{{ route('admin.dashboard') }}" class="btn-register">Dashboard</a>
            @endif
        @else
            <a href="{{ route('buyer.login') }}" class="btn-login">Masuk</a>
            <a href="{{ route('buyer.register') }}" class="btn-register">Daftar</a>
        @endauth
    </div>

</nav>

@yield('content')

{{-- FOOTER --}}
<footer class="footer">
    <div class="footer-grid">

        <div class="footer-brand">
            <h3>{{ \App\Models\Setting::get('site_name', 'FSRD UNS Store') }}</h3>
            <p>{{ \App\Models\Setting::get('site_description', 'Platform e-commerce resmi Fakultas Seni Rupa dan Desain, Universitas Sebelas Maret.') }}</p>
            <div style="display:flex; gap:12px; margin-top:14px; flex-wrap:wrap;">
                @if(\App\Models\Setting::get('instagram_url'))
                    <a href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank"
                       style="color:rgba(255,255,255,0.6); font-size:13px;">📸 Instagram</a>
                @endif
                @if(\App\Models\Setting::get('youtube_url'))
                    <a href="{{ \App\Models\Setting::get('youtube_url') }}" target="_blank"
                       style="color:rgba(255,255,255,0.6); font-size:13px;">▶️ YouTube</a>
                @endif
                @if(\App\Models\Setting::get('facebook_url'))
                    <a href="{{ \App\Models\Setting::get('facebook_url') }}" target="_blank"
                       style="color:rgba(255,255,255,0.6); font-size:13px;">👍 Facebook</a>
                @endif
            </div>
        </div>

        <div class="footer-col">
            <h4>Lapak</h4>
            <a href="{{ route('lapak.index', ['category' => 'lukisan']) }}">Lukisan</a>
            <a href="{{ route('lapak.index', ['category' => 'patung']) }}">Patung</a>
            <a href="{{ route('lapak.index', ['category' => 'keramik']) }}">Keramik</a>
            <a href="{{ route('lapak.index', ['category' => 'furniture']) }}">Furniture</a>
            <a href="{{ route('lapak.index', ['category' => 'batik']) }}">Batik</a>
        </div>

        <div class="footer-col">
            <h4>Pelatihan</h4>
            <a href="{{ route('pelatihan.index') }}">Semua Kelas</a>
            <a href="{{ route('pelatihan.index', ['category' => 'batik']) }}">Batik</a>
            <a href="{{ route('pelatihan.index', ['category' => 'fotografi']) }}">Fotografi</a>
            <a href="{{ route('pelatihan.index', ['category' => 'komputer-grafis']) }}">Komputer Grafis</a>
        </div>

        <div class="footer-col">
            <h4>Informasi</h4>
            <a href="{{ route('tentang') }}">Tentang Kami</a>
            <a href="{{ route('cara-pembelian') }}">Cara Pembelian</a>
            <a href="{{ route('creator.index') }}">Kreator</a>
            @if(\App\Models\Setting::get('contact_wa'))
                <a href="https://wa.me/{{ \App\Models\Setting::get('contact_wa') }}" target="_blank">WhatsApp Kami</a>
            @endif
            @if(\App\Models\Setting::get('contact_email'))
                <a href="mailto:{{ \App\Models\Setting::get('contact_email') }}">Email Kami</a>
            @endif
        </div>

    </div>
    <div class="footer-bottom">
        <span>© {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'FSRD UNS Store') }} — Universitas Sebelas Maret</span>
        <span>Dikembangkan oleh Hexa Sinergy Studio</span>
    </div>
</footer>
{{-- WhatsApp Floating Button --}}
@php $wa = \App\Models\Setting::get('contact_wa'); @endphp
@if($wa)
<a href="https://wa.me/{{ $wa }}" target="_blank" class="wa-float" title="Chat WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="28" height="28">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
</a>
@endif
@stack('scripts')
</body>
</html>
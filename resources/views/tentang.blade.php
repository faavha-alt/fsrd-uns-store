@extends('layouts.app')

@section('title', \App\Models\Setting::get('about_title', 'Tentang Kami'))

@section('content')

{{-- HERO --}}
<section class="hero" style="padding:48px 40px 40px;">
    <div class="hero-content">
        <div class="hero-eyebrow">
            <div class="hero-dot"></div>
            <span>Fakultas Seni Rupa & Desain UNS</span>
        </div>
        <h1 style="font-size:36px;">{{ \App\Models\Setting::get('about_title', 'Tentang FSRD UNS Store') }}</h1>
        <p>{{ \App\Models\Setting::get('about_description', '') }}</p>
    </div>
</section>

{{-- SEJARAH --}}
<section class="section section-alt">
    <div style="max-width:800px; margin:0 auto;">
        <div style="display:grid; grid-template-columns:1fr 2fr; gap:48px; align-items:center;">
            <div style="text-align:center;">
                <div style="width:120px; height:120px; background:var(--cerulean); border-radius:20px; display:flex; align-items:center; justify-content:center; font-family:'Montserrat',sans-serif; font-weight:900; color:white; font-size:48px; margin:0 auto 16px;">
                    F
                </div>
                <strong style="font-family:'Montserrat',sans-serif; font-size:16px; color:var(--cerulean-dark);">FSRD UNS</strong>
                <p style="font-size:12px; color:var(--muted); margin-top:4px;">Universitas Sebelas Maret</p>
            </div>
            <div>
                <div class="section-title" style="margin-bottom:12px;">Sejarah Singkat</div>
                <p style="font-size:14px; color:var(--muted); line-height:1.8;">
                    {{ \App\Models\Setting::get('about_history', '') }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- VISI MISI --}}
<section class="section">
    <div style="max-width:800px; margin:0 auto;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">

            {{-- Visi --}}
            <div style="background:white; border-radius:14px; border:1px solid var(--border); padding:28px;">
                <div style="width:48px; height:48px; background:var(--gold-pale); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:16px;">
                    🎯
                </div>
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:12px;">Visi</h3>
                <p style="font-size:13px; color:var(--muted); line-height:1.8;">
                    {{ \App\Models\Setting::get('about_vision', '') }}
                </p>
            </div>

            {{-- Misi --}}
            <div style="background:white; border-radius:14px; border:1px solid var(--border); padding:28px;">
                <div style="width:48px; height:48px; background:var(--sky-pale); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:16px;">
                    🚀
                </div>
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:12px;">Misi</h3>
                <div style="font-size:13px; color:var(--muted); line-height:1.8;">
                    @foreach(explode("\n", \App\Models\Setting::get('about_mission', '')) as $misi)
                        @if(trim($misi))
                            <p style="margin-bottom:8px;">{{ trim($misi) }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATISTIK --}}
<section class="section section-alt">
    <div style="max-width:800px; margin:0 auto; text-align:center;">
        <div class="section-title" style="margin-bottom:8px;">Platform Kami dalam Angka</div>
        <div class="section-sub" style="margin-bottom:32px;">Pertumbuhan platform FSRD UNS Store</div>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">
            @php
                $totalProduk  = \App\Models\Product::where('status','approved')->count();
                $totalKreator = \App\Models\Creator::count();
                $totalOrder   = \App\Models\Order::whereIn('status',['confirmed','completed'])->count();
            @endphp
            <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:28px 20px;">
                <div style="font-family:'Montserrat',sans-serif; font-size:40px; font-weight:900; color:var(--cerulean-dark);">{{ $totalProduk }}+</div>
                <div style="font-size:13px; color:var(--muted); margin-top:6px;">Produk Tersedia</div>
            </div>
            <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:28px 20px;">
                <div style="font-family:'Montserrat',sans-serif; font-size:40px; font-weight:900; color:var(--gold);">{{ $totalKreator }}</div>
                <div style="font-size:13px; color:var(--muted); margin-top:6px;">Kreator Aktif</div>
            </div>
            <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:28px 20px;">
                <div style="font-family:'Montserrat',sans-serif; font-size:40px; font-weight:900; color:var(--green);">{{ $totalOrder }}+</div>
                <div style="font-size:13px; color:var(--muted); margin-top:6px;">Transaksi Selesai</div>
            </div>
        </div>
    </div>
</section>

{{-- KONTAK --}}
<section class="section">
    <div style="max-width:800px; margin:0 auto;">
        <div class="section-title" style="margin-bottom:8px; text-align:center;">Hubungi Kami</div>
        <div class="section-sub" style="text-align:center; margin-bottom:32px;">Ada pertanyaan? Kami siap membantu</div>

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
            @if(\App\Models\Setting::get('contact_email'))
            <a href="mailto:{{ \App\Models\Setting::get('contact_email') }}"
               style="background:white; border-radius:12px; border:1px solid var(--border); padding:24px; text-align:center; text-decoration:none; transition:all 0.2s; display:block;">
                <div style="font-size:32px; margin-bottom:12px;">✉️</div>
                <strong style="font-size:13px; color:var(--ink); display:block; margin-bottom:4px;">Email</strong>
                <span style="font-size:12px; color:var(--cerulean);">{{ \App\Models\Setting::get('contact_email') }}</span>
            </a>
            @endif

            @if(\App\Models\Setting::get('contact_wa'))
            <a href="https://wa.me/{{ \App\Models\Setting::get('contact_wa') }}" target="_blank"
               style="background:white; border-radius:12px; border:1px solid var(--border); padding:24px; text-align:center; text-decoration:none; transition:all 0.2s; display:block;">
                <div style="font-size:32px; margin-bottom:12px;">💬</div>
                <strong style="font-size:13px; color:var(--ink); display:block; margin-bottom:4px;">WhatsApp</strong>
                <span style="font-size:12px; color:var(--green);">Chat Sekarang</span>
            </a>
            @endif

            @if(\App\Models\Setting::get('contact_address'))
            <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:24px; text-align:center;">
                <div style="font-size:32px; margin-bottom:12px;">📍</div>
                <strong style="font-size:13px; color:var(--ink); display:block; margin-bottom:4px;">Alamat</strong>
                <span style="font-size:12px; color:var(--muted); line-height:1.6;">{{ \App\Models\Setting::get('contact_address') }}</span>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection

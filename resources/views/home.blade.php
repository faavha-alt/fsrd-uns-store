@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- HERO --}}
<section class="hero" style="{{ \App\Models\Setting::get('hero_image') ? 'background-image: linear-gradient(135deg, rgba(14,125,167,0.92) 0%, rgba(8,74,101,0.92) 100%), url('.asset('storage/'.\App\Models\Setting::get('hero_image')).');  background-size:cover; background-position:center;' : '' }}">
    <div class="hero-content">
        <div class="hero-eyebrow">
            <div class="hero-dot"></div>
            <span>Platform Resmi FSRD UNS</span>
        </div>
        <h1>{!! nl2br(e(\App\Models\Setting::get('hero_title', 'Karya Terbaik, Tersedia untuk Dunia'))) !!}</h1>
        <p>{{ \App\Models\Setting::get('hero_subtitle', 'Temukan koleksi karya dan pelatihan dari civitas akademika Fakultas Seni Rupa & Desain UNS.') }}</p>
        <div class="hero-cta">
            <a href="{{ route('lapak.index') }}" class="btn-hero-primary">Jelajahi Lapak →</a>
            <a href="{{ route('pelatihan.index') }}" class="btn-hero-secondary">Lihat Pelatihan</a>
        </div>
        <div class="hero-stats">
            <div><div class="stat-num">{{ $totalProduk }}+</div><div class="stat-label">Produk</div></div>
            <div><div class="stat-num">{{ $totalKreator }}</div><div class="stat-label">Kreator</div></div>
            <div><div class="stat-num">{{ $totalOrder }}+</div><div class="stat-label">Transaksi</div></div>
        </div>
    </div>
</section>

{{-- CATEGORY BAR --}}
<div class="cat-bar" style="position:static;">
    <a href="{{ route('lapak.index') }}" class="cat-btn active">Semua</a>
    @foreach($kategoris as $kat)
        <a href="{{ route('lapak.index', ['category' => $kat->slug]) }}" class="cat-btn">
            {{ $kat->name }}
        </a>
    @endforeach
</div>

{{-- PRODUK UNGGULAN --}}
@if($produkUnggulan->count() > 0)
<section class="section">
    <div class="section-header">
        <div>
            <div class="section-title">Produk Unggulan</div>
            <div class="section-sub">Karya pilihan dari kreator FSRD UNS</div>
        </div>
        <a href="{{ route('lapak.index') }}" class="link-all">Lihat Semua →</a>
    </div>
    <div class="product-grid">
        @foreach($produkUnggulan as $product)
        <a href="{{ route('lapak.show', $product) }}" class="product-card">
            <div class="card-img">
                @if($product->images && count($product->images) > 0)
                    <img src="{{ asset('storage/'.$product->images[0]) }}"
                         style="width:100%;height:100%;object-fit:cover;"
                         class="card-img-inner">
                @else
                    <span style="font-size:52px;">🎨</span>
                @endif
                <span class="card-badge badge-produk">{{ $product->category->name }}</span>
            </div>
            <div class="card-body">
                <div class="card-seller">
                    <div class="seller-avatar">{{ substr($product->creator->name, 0, 1) }}</div>
                    <span class="seller-name">{{ $product->creator->name }}</span>
                    <span class="seller-tag {{ $product->creator->type === 'dosen' ? 'tag-dosen' : 'tag-mhs' }}">
                        {{ ucfirst($product->creator->type) }}
                    </span>
                </div>
                <div class="card-title">{{ $product->name }}</div>
                <div class="card-footer">
                    <div class="card-price">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                        <small>Stok: {{ $product->stock }}</small>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- BANNER PELATIHAN --}}
<div class="featured-banner">
    <div class="banner-text">
        <h2>Program Pelatihan FSRD UNS</h2>
        <p>Belajar langsung dari dosen & praktisi. 6 kategori kelas tersedia untuk semua tingkat keahlian.</p>
    </div>
    <a href="{{ route('pelatihan.index') }}" class="btn-banner">Lihat Semua Kelas →</a>
</div>

{{-- KELAS PELATIHAN --}}
@if($kelasUnggulan->count() > 0)
<section class="section section-alt">
    <div class="section-header">
        <div>
            <div class="section-title">Kelas Pelatihan Terbaru</div>
            <div class="section-sub">Tingkatkan skill seni & desainmu bersama ahlinya</div>
        </div>
        <a href="{{ route('pelatihan.index') }}" class="link-all">Lihat Semua →</a>
    </div>
    <div class="kelas-grid">
        @foreach($kelasUnggulan as $class)
        <a href="{{ route('pelatihan.show', $class) }}" class="kelas-card" style="text-decoration:none; color:inherit;">
            <div class="kelas-img" style="background:linear-gradient(135deg, var(--sky-pale), #BAE8F8);">
                @if($class->image)
                    <img src="{{ asset('storage/'.$class->image) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <span>🎓</span>
                @endif
            </div>
            <div class="kelas-body">
                <div style="margin-bottom:6px;">
                    <span class="badge badge-blue">{{ $class->category->name }}</span>
                </div>
                <div class="kelas-title">{{ $class->name }}</div>
                <div class="kelas-meta">
                    <span>👤 {{ $class->instructor->name }}</span>
                    <span>📅 {{ $class->schedules->count() }} jadwal tersedia</span>
                </div>
                <div class="kelas-footer">
                    <div class="kelas-price">Rp {{ number_format($class->price, 0, ',', '.') }}</div>
                    @php $totalSlot = $class->schedules->sum(fn($s) => $s->remainingSlots()); @endphp
                    @if($totalSlot <= 0)
                        <span class="slot-badge slot-full">Penuh</span>
                    @elseif($totalSlot <= 5)
                        <span class="slot-badge slot-few">{{ $totalSlot }} slot</span>
                    @else
                        <span class="slot-badge slot-ok">{{ $totalSlot }} slot</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- WHY SECTION --}}
<section class="section">
    <div style="text-align:center; margin-bottom:40px;">
        <div class="section-title">Mengapa FSRD UNS Store?</div>
        <div class="section-sub">Platform terpercaya untuk karya seni & desain akademik</div>
    </div>
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
        <div style="text-align:center; padding:28px 20px; background:white; border-radius:12px; border:1px solid var(--border);">
            <div style="font-size:40px; margin-bottom:14px;">🎓</div>
            <h3 style="font-family:'Montserrat',sans-serif; font-size:16px; font-weight:700; color:var(--cerulean-dark); margin-bottom:8px;">Karya Akademik Terkurasi</h3>
            <p style="font-size:13px; color:var(--muted); line-height:1.7;">Setiap produk diseleksi dan dikurasi langsung oleh tim Fakultas Seni Rupa & Desain UNS.</p>
        </div>
        <div style="text-align:center; padding:28px 20px; background:white; border-radius:12px; border:1px solid var(--border);">
            <div style="font-size:40px; margin-bottom:14px;">🔒</div>
            <h3 style="font-family:'Montserrat',sans-serif; font-size:16px; font-weight:700; color:var(--cerulean-dark); margin-bottom:8px;">Transaksi Aman & Terverifikasi</h3>
            <p style="font-size:13px; color:var(--muted); line-height:1.7;">Setiap pembayaran diverifikasi manual oleh admin — keamanan transaksi terjamin.</p>
        </div>
        <div style="text-align:center; padding:28px 20px; background:white; border-radius:12px; border:1px solid var(--border);">
            <div style="font-size:40px; margin-bottom:14px;">🌟</div>
            <h3 style="font-family:'Montserrat',sans-serif; font-size:16px; font-weight:700; color:var(--cerulean-dark); margin-bottom:8px;">Dukung Karya Lokal</h3>
            <p style="font-size:13px; color:var(--muted); line-height:1.7;">Setiap pembelian berkontribusi langsung mendukung kreativitas dosen & mahasiswa FSRD UNS.</p>
        </div>
    </div>
</section>

@endsection

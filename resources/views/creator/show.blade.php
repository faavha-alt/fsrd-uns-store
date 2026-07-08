@extends('layouts.app')

@section('title', $creator->name . ' — Kreator FSRD UNS')

@section('content')

{{-- HERO PROFIL --}}
<div style="background:linear-gradient(135deg, var(--cerulean) 0%, var(--cerulean-dark) 100%); padding:48px 40px;">
    <div style="max-width:900px; margin:0 auto; display:flex; align-items:center; gap:28px;">

        {{-- Avatar --}}
        <div style="flex-shrink:0;">
            @if($creator->photo)
                <img src="{{ asset('storage/'.$creator->photo) }}"
                    style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid var(--gold);">
            @else
                <div style="width:100px; height:100px; border-radius:50%; background:var(--gold);
                    display:flex; align-items:center; justify-content:center;
                    font-family:'Montserrat',sans-serif; font-weight:900; font-size:40px; color:white; border:3px solid rgba(255,255,255,0.3);">
                    {{ substr($creator->name, 0, 1) }}
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div style="flex:1;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                <h1 style="font-family:'Montserrat',sans-serif; font-size:28px; font-weight:800; color:white; margin:0;">
                    {{ $creator->name }}
                </h1>
                <span style="background:var(--gold); color:white; padding:3px 12px; border-radius:100px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;">
                    {{ ucfirst($creator->type) }}
                </span>
            </div>

            @if($creator->department)
            <p style="font-size:14px; color:rgba(255,255,255,0.7); margin-bottom:10px;">
                📍 {{ $creator->department }} — FSRD UNS
            </p>
            @endif

            @if($creator->bio)
            <p style="font-size:13px; color:rgba(255,255,255,0.65); line-height:1.7; max-width:560px;">
                {{ $creator->bio }}
            </p>
            @endif

            <div style="display:flex; gap:24px; margin-top:16px;">
                <div>
                    <div style="font-family:'Montserrat',sans-serif; font-size:22px; font-weight:800; color:white;">{{ $totalProduk }}</div>
                    <div style="font-size:11px; color:var(--gold-light); text-transform:uppercase; letter-spacing:0.08em;">Karya</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- BREADCRUMB --}}
<div style="background:white; border-bottom:1px solid var(--border); padding:12px 40px;">
    <div class="breadcrumb" style="margin:0;">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('lapak.index') }}">Lapak</a>
        <span class="breadcrumb-sep">›</span>
        <span style="color:var(--ink);">{{ $creator->name }}</span>
    </div>
</div>

{{-- PRODUK KREATOR --}}
<section class="section">
    <div class="section-header">
        <div>
            <div class="section-title">Karya {{ $creator->name }}</div>
            <div class="section-sub">{{ $totalProduk }} karya tersedia</div>
        </div>
    </div>

    @if($products->count() > 0)
    <div class="product-grid col-3">
        @foreach($products as $product)
        <a href="{{ route('lapak.show', $product) }}" class="product-card">
            <div class="card-img">
                @if($product->images && count($product->images) > 0)
                    <img src="{{ asset('storage/'.$product->images[0]) }}"
                         style="width:100%;height:100%;object-fit:cover;" class="card-img-inner">
                @else
                    <span style="font-size:52px;">🎨</span>
                @endif
                <span class="card-badge badge-produk">{{ $product->category->name }}</span>
            </div>
            <div class="card-body">
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

    <div style="margin-top:32px;">
        {{ $products->links() }}
    </div>

    @else
    <div style="text-align:center; padding:60px 0; color:var(--muted);">
        <div style="font-size:48px; margin-bottom:16px;">🎨</div>
        <p style="font-size:15px; font-weight:600;">Belum ada karya tersedia.</p>
    </div>
    @endif
</section>

@endsection

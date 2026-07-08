@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="detail-container">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('lapak.index') }}">Lapak</a>
        <span class="breadcrumb-sep">›</span>
        <span style="color: var(--ink);">{{ $product->name }}</span>
    </div>

    <div class="detail-grid">
        {{-- FOTO --}}
        <div>
            <div class="main-image" id="mainImg">
                @if($product->images && count($product->images) > 0)
                    <img id="mainImgEl" src="{{ asset('storage/'.$product->images[0]) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <span>🎨</span>
                @endif
            </div>
            @if($product->images && count($product->images) > 1)
            <div class="thumb-row">
                @foreach($product->images as $i => $img)
                <div class="thumb {{ $i === 0 ? 'active' : '' }}"
                     onclick="switchImg('{{ asset('storage/'.$img) }}', this)">
                    <img src="{{ asset('storage/'.$img) }}" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- INFO --}}
        <div class="detail-info">
            <span class="badge badge-blue" style="margin-bottom:12px;">{{ $product->category->name }}</span>

            <h1 class="detail-title">{{ $product->name }}</h1>

            <a href="{{ route('creator.show', $product->creator) }}" class="detail-seller-card"
   style="text-decoration:none; transition: all 0.2s;">
    <div class="detail-seller-avatar">{{ substr($product->creator->name, 0, 1) }}</div>
    <div class="detail-seller-info">
        <strong>{{ $product->creator->name }}</strong>
        <span>{{ ucfirst($product->creator->type) }} · {{ $product->creator->department ?? 'FSRD UNS' }}</span>
    </div>
    <span style="margin-left:auto; font-size:12px; color:var(--cerulean);">Lihat profil →</span>
</a>

            <div class="detail-price-box">
                <div class="detail-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <div class="detail-price-note">Stok tersedia: {{ $product->stock }} item</div>
            </div>

            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST">
    @csrf
    <button type="submit" class="btn-beli">🛒 Tambah ke Keranjang</button>
</form>
<a href="{{ route('cart.checkout') }}" class="btn-cart-add" style="display:block; text-align:center;">Langsung Checkout →</a>
            @else
                <button class="btn-beli" disabled style="background: #9CA3AF; cursor:not-allowed;">Stok Habis</button>
            @endif

            @if($product->description)
            <div style="margin-top: 20px;">
                <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--muted); margin-bottom:8px;">Deskripsi</div>
                <p style="font-size:13px; line-height:1.7; color:var(--ink);">{{ $product->description }}</p>
            </div>
            @endif

            {{-- Info Kreator --}}
            @if($product->creator->bio)
            <div style="margin-top: 20px; padding: 16px; background: var(--cream); border-radius: 10px; border: 1px solid var(--border);">
                <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--muted); margin-bottom:10px;">Tentang Kreator</div>
                <div style="display:flex; gap:10px; align-items:flex-start;">
                    <div class="detail-seller-avatar" style="width:36px;height:36px;font-size:14px;flex-shrink:0;">{{ substr($product->creator->name, 0, 1) }}</div>
                    <div>
                        <strong style="font-size:13px;">{{ $product->creator->name }}</strong>
                        <p style="font-size:12px; color:var(--muted); margin-top:4px; line-height:1.6;">{{ $product->creator->bio }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchImg(src, el) {
    document.getElementById('mainImgEl').src = src;
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}
</script>
@endpush
@endsection

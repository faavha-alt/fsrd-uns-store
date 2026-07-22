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
            {{-- Main Image --}}
            <div class="main-image" id="mainImg"
                onclick="openLightbox(currentIdx)"
                style="cursor:zoom-in; position:relative;">
                @if($product->images && count($product->images) > 0)
                    <img id="mainImgEl"
                         src="{{ asset('storage/'.$product->images[0]) }}"
                         style="width:100%;height:100%;object-fit:cover;transition:opacity 0.3s;">
                    @if(count($product->images) > 1)
                        <div class="img-counter" id="imgCounter">1 / {{ count($product->images) }}</div>
                    @endif
                    <div style="position:absolute; bottom:10px; left:10px;
                        background:rgba(0,0,0,0.45); color:white; font-size:11px;
                        padding:3px 10px; border-radius:100px; pointer-events:none;">
                        🔍 Klik untuk zoom
                    </div>
                @else
                    <span style="display:flex;align-items:center;justify-content:center;height:100%;font-size:64px;">🎨</span>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($product->images && count($product->images) > 1)
            <div class="thumb-row">
                @foreach($product->images as $i => $img)
                <div class="thumb {{ $i === 0 ? 'active' : '' }}"
                     onclick="switchImg('{{ asset('storage/'.$img) }}', this, {{ $i }})">
                    <img src="{{ asset('storage/'.$img) }}"
                         style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- INFO --}}
        <div class="detail-info">
            <span class="badge badge-blue" style="margin-bottom:12px;">
                {{ $product->category->name }}
            </span>

            <h1 class="detail-title">{{ $product->name }}</h1>

            <a href="{{ route('creator.show', $product->creator) }}" class="detail-seller-card"
               style="text-decoration:none; transition:all 0.2s;">
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
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="redirect" value="checkout">
                    <button type="submit" class="btn btn-outline" style="width:100%; margin-top:8px;">
                        Langsung Checkout →
                    </button>
                </form>
            @else
                <button class="btn-beli" disabled
                    style="background:#9CA3AF; cursor:not-allowed;">Stok Habis</button>
            @endif

            {{-- Deskripsi --}}
            @if($product->description)
            <div style="margin-top:20px;">
                <div style="font-size:12px; font-weight:700; text-transform:uppercase;
                    letter-spacing:0.06em; color:var(--muted); margin-bottom:8px;">Deskripsi</div>
                <p style="font-size:13px; line-height:1.7; color:var(--ink);">
                    {{ $product->description }}
                </p>
            </div>
            @endif

            {{-- Marketplace Links --}}
            @php $marketplaces = \App\Models\Marketplace::getActive(); @endphp
            @if($marketplaces->count() > 0)
            <div style="margin-top:24px; padding:16px; background:var(--cream);
                border-radius:12px; border:1px solid var(--border);">
                <div style="font-size:11px; font-weight:700; text-transform:uppercase;
                    letter-spacing:0.08em; color:var(--muted); margin-bottom:12px;">
                    🛍️ Temukan Kami Di
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    @foreach($marketplaces as $mp)
                    <a href="{{ $mp->url }}" target="_blank"
                       style="display:flex; align-items:center; gap:8px; padding:8px 14px;
                              background:white; border:1px solid var(--border); border-radius:8px;
                              text-decoration:none; font-size:13px; font-weight:600; color:var(--ink);
                              transition:all 0.2s;"
                       onmouseover="this.style.borderColor='var(--cerulean)';this.style.color='var(--cerulean)'"
                       onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--ink)'">
                        @if($mp->icon)
                            <img src="{{ asset('storage/'.$mp->icon) }}"
                                 style="width:20px;height:20px;object-fit:contain;">
                        @else
                            🛍️
                        @endif
                        {{ $mp->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Info Kreator --}}
            @if($product->creator->bio)
            <div style="margin-top:20px; padding:16px; background:var(--cream);
                border-radius:10px; border:1px solid var(--border);">
                <div style="font-size:12px; font-weight:700; text-transform:uppercase;
                    letter-spacing:0.06em; color:var(--muted); margin-bottom:10px;">Tentang Kreator</div>
                <div style="display:flex; gap:10px; align-items:flex-start;">
                    <div class="detail-seller-avatar"
                         style="width:36px;height:36px;font-size:14px;flex-shrink:0;">
                        {{ substr($product->creator->name, 0, 1) }}
                    </div>
                    <div>
                        <strong style="font-size:13px;">{{ $product->creator->name }}</strong>
                        <p style="font-size:12px; color:var(--muted); margin-top:4px; line-height:1.6;">
                            {{ $product->creator->bio }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ===== LIGHTBOX ===== --}}
<div id="lightbox" onclick="closeLightbox()"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.92);
           z-index:9999; align-items:center; justify-content:center; cursor:zoom-out;">
    <div style="position:relative; max-width:90vw; max-height:90vh;"
         onclick="event.stopPropagation()">

        <img id="lightboxImg" src="" alt=""
             style="max-width:90vw; max-height:85vh; object-fit:contain;
                    border-radius:8px; display:block; margin:0 auto;">

        {{-- Prev --}}
        <button onclick="prevImg()"
            style="position:absolute; left:-50px; top:50%; transform:translateY(-50%);
                   background:rgba(255,255,255,0.15); border:none; color:white; font-size:24px;
                   width:40px; height:40px; border-radius:50%; cursor:pointer;
                   display:flex; align-items:center; justify-content:center;">‹</button>

        {{-- Next --}}
        <button onclick="nextImg()"
            style="position:absolute; right:-50px; top:50%; transform:translateY(-50%);
                   background:rgba(255,255,255,0.15); border:none; color:white; font-size:24px;
                   width:40px; height:40px; border-radius:50%; cursor:pointer;
                   display:flex; align-items:center; justify-content:center;">›</button>

        {{-- Close --}}
        <button onclick="closeLightbox()"
            style="position:absolute; top:-44px; right:0;
                   background:rgba(255,255,255,0.15); border:none; color:white;
                   font-size:20px; width:36px; height:36px; border-radius:50%;
                   cursor:pointer; display:flex; align-items:center; justify-content:center;">✕</button>

        {{-- Counter --}}
        <div id="lightboxCounter"
             style="text-align:center; color:rgba(255,255,255,0.6); font-size:12px; margin-top:12px;">
        </div>
    </div>
</div>

<script>
const galleryImgs = [
    @if($product->images)
        @foreach($product->images as $img)
            '{{ asset('storage/'.$img) }}',
        @endforeach
    @endif
];
let currentIdx = 0;

function switchImg(src, el, idx) {
    currentIdx = idx ?? galleryImgs.indexOf(src);
    const mainEl = document.getElementById('mainImgEl');
    mainEl.style.opacity = '0';
    setTimeout(() => { mainEl.src = src; mainEl.style.opacity = '1'; }, 200);
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    const counter = document.getElementById('imgCounter');
    if (counter) counter.textContent = (currentIdx + 1) + ' / ' + galleryImgs.length;
}

function openLightbox(idx) {
    if (!galleryImgs.length) return;
    currentIdx = idx ?? 0;
    document.getElementById('lightbox').style.display = 'flex';
    document.getElementById('lightboxImg').src = galleryImgs[currentIdx];
    updateLightboxCounter();
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}

function prevImg() {
    currentIdx = (currentIdx - 1 + galleryImgs.length) % galleryImgs.length;
    document.getElementById('lightboxImg').src = galleryImgs[currentIdx];
    updateLightboxCounter();
}

function nextImg() {
    currentIdx = (currentIdx + 1) % galleryImgs.length;
    document.getElementById('lightboxImg').src = galleryImgs[currentIdx];
    updateLightboxCounter();
}

function updateLightboxCounter() {
    document.getElementById('lightboxCounter').textContent =
        (currentIdx + 1) + ' dari ' + galleryImgs.length + ' foto';
}

document.addEventListener('keydown', function(e) {
    if (document.getElementById('lightbox').style.display === 'flex') {
        if (e.key === 'ArrowLeft')  prevImg();
        if (e.key === 'ArrowRight') nextImg();
        if (e.key === 'Escape')     closeLightbox();
    }
});
</script>

@endsection
@extends('layouts.app')

@section('title', 'Lapak Seni & Desain')

@section('content')

{{-- SEARCH + FILTER BAR --}}
<div style="background:white; border-bottom:1px solid var(--border); padding:16px 40px;">
    <form action="{{ route('lapak.index') }}" method="GET">
        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            {{-- Search --}}
            <div style="flex:1; min-width:200px; display:flex; gap:8px;">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari produk, kreator..."
                    style="flex:1; padding:10px 16px; border:1.5px solid var(--border); border-radius:8px; font-size:13px; font-family:'Poppins',sans-serif; outline:none;"
                    onfocus="this.style.borderColor='var(--cerulean)'"
                    onblur="this.style.borderColor='var(--border)'">
                <button type="submit" class="btn btn-primary">🔍 Cari</button>
            </div>

            {{-- Sort --}}
            <select name="sort" onchange="this.form.submit()"
                style="padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:13px; font-family:'Poppins',sans-serif; outline:none; cursor:pointer;">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
            </select>

            {{-- Reset --}}
            @if(request('search') || request('category') || request('sort'))
                <a href="{{ route('lapak.index') }}" class="btn btn-outline" style="white-space:nowrap;">✕ Reset</a>
            @endif

            {{-- Pertahankan category di hidden input --}}
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
        </div>
    </form>
</div>

{{-- CATEGORY BAR --}}
<div class="cat-bar" style="position:static;">
    <a href="{{ route('lapak.index', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}"
       class="cat-btn {{ !request('category') ? 'active' : '' }}">Semua</a>
    @foreach($categories as $kat)
        <a href="{{ route('lapak.index', array_filter(['category' => $kat->slug, 'search' => request('search'), 'sort' => request('sort')])) }}"
           class="cat-btn {{ request('category') === $kat->slug ? 'active' : '' }}">
            {{ $kat->name }}
        </a>
    @endforeach
</div>

<section class="section">
    <div class="section-header">
        <div>
            <div class="section-title">
                @if(request('search'))
                    Hasil pencarian: "{{ request('search') }}"
                @elseif(request('category'))
                    {{ $categories->where('slug', request('category'))->first()?->name ?? 'Produk' }}
                @else
                    Lapak Seni & Desain
                @endif
            </div>
            <div class="section-sub">{{ $products->total() }} produk ditemukan</div>
        </div>
    </div>

    @if($products->count() > 0)
    <div class="product-grid col-3">
        @foreach($products as $product)
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

    <div style="margin-top:32px;">
        {{ $products->withQueryString()->links() }}
    </div>

    @else
    <div style="text-align:center; padding:60px 0; color:var(--muted);">
        <div style="font-size:48px; margin-bottom:16px;">🔍</div>
        <p style="font-size:15px; font-weight:600; margin-bottom:8px;">
            @if(request('search'))
                Tidak ada produk untuk "{{ request('search') }}"
            @else
                Belum ada produk di kategori ini.
            @endif
        </p>
        <p style="font-size:13px; margin-bottom:24px;">Coba kata kunci lain atau lihat semua produk.</p>
        <a href="{{ route('lapak.index') }}" class="btn btn-primary">Lihat Semua Produk</a>
    </div>
    @endif
</section>

@endsection
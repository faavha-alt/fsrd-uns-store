@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="detail-container">
    <h1 style="font-family:'Montserrat',sans-serif; font-size:26px; font-weight:800; color:var(--cerulean-dark); margin-bottom:24px;">
        Keranjang Belanja
    </h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($cart) > 0)
    <div style="display:grid; grid-template-columns:1fr 320px; gap:24px;">

        {{-- DAFTAR PRODUK --}}
        <div>
            @foreach($cart as $id => $item)
            <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:16px; margin-bottom:12px; display:flex; gap:16px; align-items:center;">
                <div style="width:72px; height:72px; border-radius:8px; background:var(--sky-pale); display:flex; align-items:center; justify-content:center; font-size:28px; flex-shrink:0; overflow:hidden;">
                    @if($item['image'])
                        <img src="{{ asset('storage/'.$item['image']) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        🎨
                    @endif
                </div>
                <div style="flex:1;">
                    <strong style="font-size:14px; display:block;">{{ $item['name'] }}</strong>
                    <span style="font-size:13px; color:var(--cerulean-dark); font-weight:700; font-family:'Montserrat',sans-serif;">
                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                    </span>
                </div>
                <div style="display:flex; align-items:center; gap:8px;">
                    <form method="POST" action="{{ route('cart.update', $id) }}">
                        @csrf
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                            style="width:60px; padding:6px; border:1px solid var(--border); border-radius:6px; text-align:center; font-family:'Poppins',sans-serif;"
                            onchange="this.form.submit()">
                    </form>
                    <span style="font-size:13px; font-weight:700; color:var(--cerulean-dark); min-width:90px; text-align:right; font-family:'Montserrat',sans-serif;">
                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                    </span>
                    <form method="POST" action="{{ route('cart.remove', $id) }}">
                        @csrf
                        <button type="submit" style="background:none; border:none; color:var(--red); cursor:pointer; font-size:18px;" title="Hapus">✕</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{-- RINGKASAN --}}
        <div>
            <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:20px; position:sticky; top:80px;">
                <div style="font-size:15px; font-weight:700; color:var(--cerulean-dark); margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid var(--border);">
                    Ringkasan Pesanan
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px; color:var(--muted); margin-bottom:8px;">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px; color:var(--muted); margin-bottom:12px;">
                    <span>Kode unik</span>
                    <span>ditentukan saat checkout</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:800; color:var(--ink); padding-top:12px; border-top:2px solid var(--border); margin-bottom:18px;">
                    <span>Estimasi Total</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}+</span>
                </div>
                <a href="{{ route('cart.checkout') }}" class="btn-beli" style="display:block; text-align:center; text-decoration:none;">
                    Lanjut ke Checkout →
                </a>
                <a href="{{ route('lapak.index') }}" style="display:block; text-align:center; margin-top:10px; font-size:13px; color:var(--muted);">
                    ← Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
    @else
    <div style="text-align:center; padding:60px 0; color:var(--muted);">
        <div style="font-size:56px; margin-bottom:16px;">🛒</div>
        <p style="font-size:16px; font-weight:600; margin-bottom:8px;">Keranjang masih kosong</p>
        <p style="font-size:13px; margin-bottom:24px;">Yuk, temukan karya menarik di Lapak FSRD UNS!</p>
        <a href="{{ route('lapak.index') }}" class="btn btn-primary">Jelajahi Lapak</a>
    </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Riwayat Order')

@section('content')
<div class="detail-container">
    <h1 style="font-family:'Montserrat',sans-serif; font-size:26px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">Riwayat Order</h1>
    <p style="font-size:13px; color:var(--muted); margin-bottom:28px;">Halo, <strong>{{ auth()->user()->name }}</strong> — berikut daftar pesanan Anda.</p>

    @if($orders->count() > 0)
    <div style="display:flex; flex-direction:column; gap:12px;">
        @foreach($orders as $order)
        <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:18px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <div>
                    <strong class="font-mono" style="font-size:14px; color:var(--cerulean-dark);">{{ $order->order_number }}</strong>
                    <span style="font-size:12px; color:var(--muted); display:block; margin-top:2px;">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
                @php
                    $badges = [
                        'pending_payment' => ['class' => 'badge-gray', 'label' => 'Belum Bayar'],
                        'pending_verification' => ['class' => 'badge-yellow', 'label' => 'Menunggu Verifikasi'],
                        'confirmed' => ['class' => 'badge-blue', 'label' => 'Dikonfirmasi'],
                        'rejected' => ['class' => 'badge-red', 'label' => 'Ditolak'],
                        'completed' => ['class' => 'badge-green', 'label' => 'Selesai'],
                    ];
                    $badge = $badges[$order->status] ?? ['class' => 'badge-gray', 'label' => $order->status];
                @endphp
                <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
            </div>

            {{-- Item --}}
            <div style="border-top:1px solid var(--border); padding-top:12px; margin-bottom:12px;">
                @foreach($order->items as $item)
                <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:4px;">
                    <span>{{ $item->product_name }} <span style="color:var(--muted);">×{{ $item->quantity }}</span></span>
                    <span style="font-weight:600;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border); padding-top:12px;">
                <div>
                    <span style="font-size:12px; color:var(--muted);">Total Transfer</span>
                    <strong style="font-family:'Montserrat',sans-serif; font-size:16px; color:var(--cerulean-dark); display:block;">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </strong>
                </div>
                <a href="{{ route('buyer.order.detail', $order->order_number) }}" class="btn btn-outline btn-sm">
                    Lihat Detail →
                </a>
            </div>

            @if($order->status === 'rejected' && $order->rejection_reason)
            <div style="margin-top:12px; padding:10px; background:var(--light-red); border-radius:8px; font-size:12px; color:var(--red);">
                <strong>Alasan penolakan:</strong> {{ $order->rejection_reason }}
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div style="margin-top:24px;">{{ $orders->links() }}</div>
    @else
    <div style="text-align:center; padding:60px 0; color:var(--muted);">
        <div style="font-size:48px; margin-bottom:16px;">🛒</div>
        <p style="font-size:15px; font-weight:600; margin-bottom:8px;">Belum ada order.</p>
        <p style="font-size:13px; margin-bottom:24px;">Yuk mulai belanja di Lapak FSRD UNS!</p>
        <a href="{{ route('lapak.index') }}" class="btn btn-primary">Jelajahi Lapak</a>
    </div>
    @endif
</div>
@endsection

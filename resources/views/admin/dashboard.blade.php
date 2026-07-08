@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- WELCOME BAR --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <div>
        <h1 style="font-family:'Montserrat',sans-serif; font-size:22px; font-weight:800; color:#0A3D52;">
            Selamat Datang, {{ auth()->user()->name }} 👋
        </h1>
        <p style="font-size:13px; color:var(--muted); margin-top:3px;">
            {{ now()->translatedFormat('l, d F Y') }} — Pantau aktivitas platform FSRD UNS
        </p>
    </div>
    <div style="display:flex; gap:8px;">
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="btn btn-outline btn-sm">
            ⏳ Produk Pending
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending_verification']) }}" class="btn btn-primary btn-sm">
            🔔 Order Masuk
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="stats-grid" style="margin-bottom:24px;">

    <div class="stat-card c-navy">
        <div class="stat-card-top">
            <div class="stat-icon-wrap">📦</div>
            @if($stats['produk_pending'] > 0)
                <span class="badge badge-yellow" style="font-size:10px;">{{ $stats['produk_pending'] }} pending</span>
            @endif
        </div>
        <div class="stat-num">{{ $stats['total_produk'] }}</div>
        <div class="stat-label">Produk Aktif</div>
    </div>

    <div class="stat-card c-gold">
        <div class="stat-card-top">
            <div class="stat-icon-wrap">🎓</div>
        </div>
        <div class="stat-num">{{ $stats['total_kelas'] }}</div>
        <div class="stat-label">Kelas Pelatihan</div>
    </div>

    <div class="stat-card c-green">
        <div class="stat-card-top">
            <div class="stat-icon-wrap">💰</div>
            @if($stats['order_pending'] > 0)
                <span class="badge badge-yellow" style="font-size:10px;">{{ $stats['order_pending'] }} pending</span>
            @endif
        </div>
        <div class="stat-num" style="font-size:{{ $stats['total_pendapatan'] >= 1000000 ? '18px' : '24px' }};">
    Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}
</div>
        <div class="stat-label">Total Pendapatan</div>
    </div>

    <div class="stat-card c-purple">
        <div class="stat-card-top">
            <div class="stat-icon-wrap">👥</div>
        </div>
        <div class="stat-num">{{ $stats['total_buyer'] }}</div>
        <div class="stat-label">Total Buyer</div>
    </div>

</div>

{{-- ALERT PENDING --}}
@if($stats['order_pending'] > 0 || $stats['booking_pending'] > 0)
<div style="background:linear-gradient(135deg, #FEF9C3, #FEF3C7); border:1px solid #FCD34D; border-radius:12px; padding:14px 18px; margin-bottom:20px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
    <div style="width:36px; height:36px; background:#F59E0B; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">⚡</div>
    <div style="flex:1;">
        <strong style="font-size:13px; color:#92400E; display:block;">Perlu Tindakan Segera</strong>
        <span style="font-size:12px; color:#B45309;">Ada transaksi yang menunggu verifikasi pembayaran</span>
    </div>
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        @if($stats['order_pending'] > 0)
            <a href="{{ route('admin.orders.index', ['status' => 'pending_verification']) }}"
               style="background:#F59E0B; color:white; padding:7px 14px; border-radius:7px; font-size:12px; font-weight:700; text-decoration:none;">
                {{ $stats['order_pending'] }} Order →
            </a>
        @endif
        @if($stats['booking_pending'] > 0)
            <a href="{{ route('admin.bookings.index', ['status' => 'pending_verification']) }}"
               style="background:var(--cerulean); color:white; padding:7px 14px; border-radius:7px; font-size:12px; font-weight:700; text-decoration:none;">
                {{ $stats['booking_pending'] }} Booking →
            </a>
        @endif
    </div>
</div>
@endif

{{-- TABEL ORDER & BOOKING --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">

    {{-- ORDER TERBARU --}}
    <div class="panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">Order Terbaru</div>
                <div style="font-size:11px; color:var(--muted); margin-top:2px;">{{ $recentOrders->count() }} transaksi terakhir</div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="panel-body" style="padding:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Pembeli</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr onclick="window.location='{{ route('admin.orders.show', $order) }}'" style="cursor:pointer;">
                        <td>
                            <span class="font-mono" style="font-size:11px; color:var(--cerulean);">
                                {{ $order->order_number }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:26px; height:26px; border-radius:50%; background:var(--cerulean); color:white; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0;">
                                    {{ substr($order->buyer_name, 0, 1) }}
                                </div>
                                <span style="font-size:12px;">{{ $order->buyer_name }}</span>
                            </div>
                        </td>
                        <td style="font-weight:700; font-size:12px;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $badges = [
                                    'pending_payment'      => ['class' => 'badge-gray',   'label' => 'Belum Bayar'],
                                    'pending_verification' => ['class' => 'badge-yellow', 'label' => 'Pending'],
                                    'confirmed'            => ['class' => 'badge-blue',   'label' => 'Konfirmasi'],
                                    'rejected'             => ['class' => 'badge-red',    'label' => 'Ditolak'],
                                    'completed'            => ['class' => 'badge-green',  'label' => 'Selesai'],
                                ];
                                $b = $badges[$order->status] ?? ['class' => 'badge-gray', 'label' => '-'];
                            @endphp
                            <span class="badge {{ $b['class'] }}" style="font-size:10px;">{{ $b['label'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:28px; color:var(--muted); font-size:13px;">
                            Belum ada order
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- BOOKING TERBARU --}}
    <div class="panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">Booking Terbaru</div>
                <div style="font-size:11px; color:var(--muted); margin-top:2px;">{{ $recentBookings->count() }} booking terakhir</div>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="panel-body" style="padding:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Booking</th>
                        <th>Peserta</th>
                        <th>Kelas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr onclick="window.location='{{ route('admin.bookings.show', $booking) }}'" style="cursor:pointer;">
                        <td>
                            <span class="font-mono" style="font-size:11px; color:var(--cerulean);">
                                {{ $booking->booking_number }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:26px; height:26px; border-radius:50%; background:var(--gold); color:white; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0;">
                                    {{ substr($booking->participant_name, 0, 1) }}
                                </div>
                                <span style="font-size:12px;">{{ $booking->participant_name }}</span>
                            </div>
                        </td>
                        <td style="font-size:12px;">{{ Str::limit($booking->schedule->trainingClass->name ?? '-', 18) }}</td>
                        <td>
                            @php $b = $badges[$booking->status] ?? ['class' => 'badge-gray', 'label' => '-']; @endphp
                            <span class="badge {{ $b['class'] }}" style="font-size:10px;">{{ $b['label'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:28px; color:var(--muted); font-size:13px;">
                            Belum ada booking
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- PRODUK PENDING APPROVAL --}}
@if($pendingProducts->count() > 0)
<div class="panel">
    <div class="panel-header">
        <div>
            <div class="panel-title">⏳ Produk Menunggu Persetujuan</div>
            <div style="font-size:11px; color:var(--muted); margin-top:2px;">{{ $pendingProducts->count() }} produk perlu direview</div>
        </div>
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <div class="panel-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Kreator</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingProducts as $product)
                <tr>
                    <td><strong style="font-size:13px;">{{ $product->name }}</strong></td>
                    <td style="font-size:12px; color:var(--muted);">{{ $product->category->name ?? '-' }}</td>
                    <td style="font-size:12px;">{{ $product->creator->name ?? '-' }}</td>
                    <td style="font-size:12px; font-weight:700;">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('admin.products.approve', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm"
                                onclick="return confirm('Setujui produk ini?')">✓ Setujui</button>
                        </form>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-sm">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
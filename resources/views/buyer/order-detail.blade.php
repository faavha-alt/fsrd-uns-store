@extends('layouts.app')

@section('title', 'Detail Order #' . $order->order_number)

@section('content')
<div class="detail-container">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('buyer.account', ['tab' => 'orders']) }}">Riwayat Order</a>
        <span class="breadcrumb-sep">›</span>
        <span style="color:var(--ink);">{{ $order->order_number }}</span>
    </div>

    <div style="display:grid; grid-template-columns:1fr 300px; gap:24px;">

        {{-- Detail --}}
        <div>
            <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:20px; margin-bottom:16px;">
                <div style="font-size:14px; font-weight:700; color:var(--cerulean-dark); margin-bottom:14px; padding-bottom:10px; border-bottom:1px solid var(--border);">
                    Informasi Pesanan
                </div>
                <table style="width:100%; font-size:13px;">
                    <tr><td style="color:var(--muted); padding:5px 0; width:130px;">No. Order</td><td><strong class="font-mono">{{ $order->order_number }}</strong></td></tr>
                    <tr><td style="color:var(--muted); padding:5px 0;">Tanggal</td><td>{{ $order->created_at->format('d M Y, H:i') }} WIB</td></tr>
                    <tr><td style="color:var(--muted); padding:5px 0;">Status</td>
                        <td>
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
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Item --}}
            <div style="background:white; border-radius:12px; border:1px solid var(--border); overflow:hidden;">
                <div style="padding:16px 20px; border-bottom:1px solid var(--border); font-size:14px; font-weight:700; color:var(--cerulean-dark);">
                    Item Pesanan
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:right;">Harga</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td><strong>{{ $item->product_name }}</strong></td>
                            <td style="text-align:center;">{{ $item->quantity }}</td>
                            <td style="text-align:right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td style="text-align:right;"><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:var(--cream);">
                            <td colspan="3" style="text-align:right; padding:10px 16px; color:var(--muted);">Subtotal</td>
                            <td style="text-align:right; padding:10px 16px;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="background:var(--cream);">
                            <td colspan="3" style="text-align:right; padding:4px 16px; color:var(--muted); font-size:12px;">Kode Unik</td>
                            <td style="text-align:right; padding:4px 16px; font-size:12px; color:var(--muted);">+{{ $order->unique_code }}</td>
                        </tr>
                        <tr style="background:var(--cream);">
                            <td colspan="3" style="text-align:right; padding:10px 16px; font-weight:800; font-size:15px;">Total Transfer</td>
                            <td style="text-align:right; padding:10px 16px; font-weight:800; font-size:15px; color:var(--cerulean-dark);">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            {{-- Rekening --}}
            @if($order->bankAccount)
            <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:18px; margin-bottom:14px;">
                <div style="font-size:13px; font-weight:700; color:var(--cerulean-dark); margin-bottom:12px;">Rekening Tujuan Transfer</div>
                <strong style="font-size:14px; display:block;">{{ $order->bankAccount->bank_name }}</strong>
                <span class="font-mono" style="font-size:16px;">{{ $order->bankAccount->account_number }}</span>
                <span style="font-size:12px; color:var(--muted); display:block; margin-top:4px;">a.n. {{ $order->bankAccount->account_holder }}</span>
            </div>
            @endif

            {{-- Total --}}
            <div style="background:var(--gold-pale); border:1px solid rgba(233,168,40,0.3); border-radius:12px; padding:18px; margin-bottom:14px; text-align:center;">
                <div style="font-size:11px; color:var(--muted); margin-bottom:4px;">Transfer tepat sebesar</div>
                <strong class="font-mono" style="font-size:22px; color:var(--cerulean-dark);">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
            </div>

            @if($order->status === 'rejected' && $order->rejection_reason)
            <div style="background:var(--light-red); border-radius:10px; padding:14px; font-size:13px; color:var(--red);">
                <strong style="display:block; margin-bottom:4px;">Alasan Penolakan:</strong>
                {{ $order->rejection_reason }}
            </div>
            @endif
        </div>
    </div>

    <div style="margin-top:24px;">
        <a href="{{ route('buyer.account', ['tab' => 'orders']) }}" class="btn btn-outline">← Kembali ke Riwayat Order</a>
    </div>
</div>
@endsection

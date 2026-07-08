@extends('layouts.admin')

@section('title', 'Detail Order #' . $order->order_number)

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">

    {{-- KIRI: Detail order --}}
    <div>
        {{-- Info Pembeli --}}
        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header">
                <span class="panel-title">Informasi Pembeli</span>
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
            <div class="panel-body">
                <table style="width:100%; font-size:13px;">
                    <tr>
                        <td style="color:var(--muted); padding:6px 0; width:140px;">No. Order</td>
                        <td><strong class="font-mono">{{ $order->order_number }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color:var(--muted); padding:6px 0;">Nama Pembeli</td>
                        <td><strong>{{ $order->buyer_name }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color:var(--muted); padding:6px 0;">Email</td>
                        <td>{{ $order->buyer_email }}</td>
                    </tr>
                    <tr>
                        <td style="color:var(--muted); padding:6px 0;">Telepon</td>
                        <td>{{ $order->buyer_phone }}</td>
                    </tr>
                    @if($order->buyer_address)
                    <tr>
                        <td style="color:var(--muted); padding:6px 0;">Alamat</td>
                        <td>{{ $order->buyer_address }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color:var(--muted); padding:6px 0;">Tanggal Order</td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Item Produk --}}
        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header">
                <span class="panel-title">Item Produk</span>
            </div>
            <div class="panel-body" style="padding:0;">
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
                            <td colspan="3" style="text-align:right; padding:12px 16px; font-weight:600; color:var(--muted);">Subtotal</td>
                            <td style="text-align:right; padding:12px 16px; font-weight:600;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="background:var(--cream);">
                            <td colspan="3" style="text-align:right; padding:4px 16px; color:var(--muted); font-size:12px;">Kode Unik</td>
                            <td style="text-align:right; padding:4px 16px; font-size:12px; color:var(--muted);">+{{ $order->unique_code }}</td>
                        </tr>
                        <tr style="background:var(--cream);">
                            <td colspan="3" style="text-align:right; padding:12px 16px; font-weight:800; font-size:15px;">Total Transfer</td>
                            <td style="text-align:right; padding:12px 16px; font-weight:800; font-size:15px; color:var(--cerulean, #0E7DA7);">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Bukti Transfer --}}
        @if($order->payment_proof)
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Bukti Transfer</span>
            </div>
            <div class="panel-body">
                @php $ext = pathinfo($order->payment_proof, PATHINFO_EXTENSION); @endphp
                @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                    <img src="{{ asset('storage/'.$order->payment_proof) }}"
                         style="max-width:100%; border-radius:8px; border:1px solid var(--border);">
                @else
                    <a href="{{ asset('storage/'.$order->payment_proof) }}" target="_blank" class="btn btn-outline">
                        📄 Lihat Bukti Transfer (PDF)
                    </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- KANAN: Aksi verifikasi --}}
    <div>
        {{-- Rekening Tujuan --}}
        @if($order->bankAccount)
        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header"><span class="panel-title">Rekening Tujuan</span></div>
            <div class="panel-body">
                <strong style="font-size:14px; display:block;">{{ $order->bankAccount->bank_name }}</strong>
                <span class="font-mono" style="font-size:16px;">{{ $order->bankAccount->account_number }}</span>
                <span style="font-size:12px; color:var(--muted); display:block; margin-top:4px;">a.n. {{ $order->bankAccount->account_holder }}</span>
            </div>
        </div>
        @endif

        {{-- Aksi --}}
        @if($order->status === 'pending_verification')
        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header"><span class="panel-title">Verifikasi Pembayaran</span></div>
            <div class="panel-body">
                <p style="font-size:13px; color:var(--muted); margin-bottom:16px;">
                    Pastikan nominal transfer sudah sesuai sebelum mengkonfirmasi.
                </p>
                <div style="background:var(--gold-pale, #FEF7E7); border:1px solid rgba(233,168,40,0.3); border-radius:8px; padding:14px; text-align:center; margin-bottom:16px;">
                    <div style="font-size:11px; color:var(--muted);">Total yang harus ditransfer</div>
                    <strong class="font-mono" style="font-size:22px; color:#0A5F80;">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                </div>

                {{-- Konfirmasi --}}
                <form action="{{ route('admin.orders.confirm', $order) }}" method="POST" style="margin-bottom:10px;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width:100%;"
                        onclick="return confirm('Konfirmasi pembayaran order ini?')">
                        ✓ Konfirmasi Pembayaran
                    </button>
                </form>

                {{-- Tolak --}}
                <button type="button" class="btn btn-outline" style="width:100%; color:var(--red); border-color:var(--red);"
                    onclick="document.getElementById('rejectForm').style.display='block'; this.style.display='none'">
                    ✗ Tolak Pembayaran
                </button>

                <div id="rejectForm" style="display:none; margin-top:12px;">
                    <form action="{{ route('admin.orders.reject', $order) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Alasan Penolakan</label>
                            <textarea name="rejection_reason" class="form-control" rows="3" required
                                placeholder="Contoh: Nominal tidak sesuai, bukti tidak terbaca..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger" style="width:100%;">Tolak & Kirim Alasan</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @if($order->status === 'confirmed')
        <div class="panel">
            <div class="panel-header"><span class="panel-title">Tandai Selesai</span></div>
            <div class="panel-body">
                <p style="font-size:13px; color:var(--muted); margin-bottom:14px;">
                    Tandai order sebagai selesai setelah produk terkirim ke pembeli.
                </p>
                <form action="{{ route('admin.orders.complete', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width:100%;"
                        onclick="return confirm('Tandai order ini sebagai selesai?')">
                        ✓ Tandai Selesai
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($order->status === 'rejected')
        <div class="panel">
            <div class="panel-header"><span class="panel-title">Alasan Penolakan</span></div>
            <div class="panel-body">
                <div style="background:var(--light-red, #FEE2E2); border-radius:8px; padding:12px; font-size:13px; color:var(--red, #DC2626);">
                    {{ $order->rejection_reason }}
                </div>
            </div>
        </div>
        @endif

        {{-- Verifikator --}}
        @if($order->verifier)
        <div class="panel" style="margin-top:16px;">
            <div class="panel-header"><span class="panel-title">Diverifikasi Oleh</span></div>
            <div class="panel-body">
                <strong style="font-size:13px;">{{ $order->verifier->name }}</strong>
                <span style="font-size:12px; color:var(--muted); display:block;">{{ $order->updated_at->format('d M Y, H:i') }} WIB</span>
            </div>
        </div>
        @endif
    </div>
</div>

<div style="margin-top:20px;">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">← Kembali ke Daftar Order</a>
</div>
@endsection

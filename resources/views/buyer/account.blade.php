@extends('layouts.app')

@section('title', 'Akun Saya')

@section('content')

{{-- Modal Sukses Order --}}
@if(session('order_success'))
<div id="successModal" style="position:fixed; inset:0; background:rgba(0,0,0,0.5);
    display:flex; align-items:center; justify-content:center; z-index:9999; padding:20px;">
    <div style="background:white; border-radius:20px; padding:40px; max-width:440px; width:100%;
        text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.2); animation:fadeInUp 0.4s ease;">

        <div style="width:72px; height:72px; background:#D1FAE5; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:36px; margin:0 auto 20px;">✅</div>

        <h2 style="font-family:'Montserrat',sans-serif; font-size:22px; font-weight:800;
            color:var(--cerulean-dark); margin-bottom:10px;">Pesanan Berhasil!</h2>

        <p style="font-size:14px; color:var(--muted); line-height:1.6; margin-bottom:16px;">
            Terima kasih! Pesanan Anda sedang menunggu verifikasi pembayaran oleh admin.
            Maksimal <strong>1×24 jam</strong> setelah transfer dikonfirmasi.
        </p>

        <div style="background:var(--cream); border:1px solid var(--border); border-radius:10px;
            padding:14px; margin-bottom:24px;">
            <div style="font-size:11px; color:var(--muted); margin-bottom:4px;">Nomor Order</div>
            <strong style="font-family:'Courier New',monospace; font-size:18px; color:var(--cerulean-dark);">
                {{ session('order_success') }}
            </strong>
        </div>

        <button onclick="document.getElementById('successModal').style.display='none'"
            class="btn btn-primary" style="width:100%; padding:13px; font-size:14px;">
            Lihat Riwayat Pesanan →
        </button>
    </div>
</div>
@endif

<div class="detail-container">

    {{-- Header Akun --}}
    <div style="display:flex; align-items:center; gap:16px; margin-bottom:28px;">
        <div style="width:56px; height:56px; border-radius:50%; background:var(--cerulean); color:white; display:flex; align-items:center; justify-content:center; font-family:'Montserrat',sans-serif; font-weight:800; font-size:22px; flex-shrink:0;">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div>
            <h1 style="font-family:'Montserrat',sans-serif; font-size:22px; font-weight:800; color:var(--cerulean-dark); margin-bottom:2px;">
                {{ auth()->user()->name }}
            </h1>
            <p style="font-size:13px; color:var(--muted);">{{ auth()->user()->email }}</p>
        </div>
    </div>

    {{-- TAB NAVIGATION --}}
    <div style="display:flex; gap:4px; border-bottom:2px solid var(--border); margin-bottom:28px;">
        <a href="{{ route('buyer.account', ['tab' => 'orders']) }}"
           style="padding:10px 20px; font-size:14px; font-weight:600; border-bottom:2px solid transparent; margin-bottom:-2px; transition:all 0.2s;
                  {{ $tab === 'orders' ? 'color:var(--cerulean); border-bottom-color:var(--cerulean);' : 'color:var(--muted);' }}">
            🛒 Pesanan
            <span style="background:var(--border); color:var(--muted); font-size:11px; padding:1px 8px; border-radius:100px; margin-left:4px;">
                {{ $orders->total() }}
            </span>
        </a>
        <a href="{{ route('buyer.account', ['tab' => 'bookings']) }}"
           style="padding:10px 20px; font-size:14px; font-weight:600; border-bottom:2px solid transparent; margin-bottom:-2px; transition:all 0.2s;
                  {{ $tab === 'bookings' ? 'color:var(--cerulean); border-bottom-color:var(--cerulean);' : 'color:var(--muted);' }}">
            🎓 Booking Pelatihan
            <span style="background:var(--border); color:var(--muted); font-size:11px; padding:1px 8px; border-radius:100px; margin-left:4px;">
                {{ $bookings->total() }}
            </span>
        </a>
    </div>

    {{-- TAB ORDERS --}}
    @if($tab === 'orders')
        @if($orders->count() > 0)
        <div style="display:flex; flex-direction:column; gap:12px;">
            @foreach($orders as $order)
            <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:18px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                    <div>
                        <strong class="font-mono" style="font-size:14px; color:var(--cerulean-dark);">{{ $order->order_number }}</strong>
                        <span style="font-size:12px; color:var(--muted); display:block; margin-top:2px;">
                            {{ $order->created_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    @php
                        $badges = [
                            'pending_payment'      => ['class' => 'badge-gray',   'label' => 'Belum Bayar'],
                            'pending_verification' => ['class' => 'badge-yellow', 'label' => 'Menunggu Verifikasi'],
                            'confirmed'            => ['class' => 'badge-blue',   'label' => 'Dikonfirmasi'],
                            'rejected'             => ['class' => 'badge-red',    'label' => 'Ditolak'],
                            'completed'            => ['class' => 'badge-green',  'label' => 'Selesai'],
                        ];
                        $badge = $badges[$order->status] ?? ['class' => 'badge-gray', 'label' => $order->status];
                    @endphp
                    <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                </div>

                {{-- Items --}}
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
        <div style="margin-top:24px;">{{ $orders->appends(['tab' => 'orders'])->links() }}</div>
        @else
        <div style="text-align:center; padding:60px 0; color:var(--muted);">
            <div style="font-size:48px; margin-bottom:16px;">🛒</div>
            <p style="font-size:15px; font-weight:600; margin-bottom:8px;">Belum ada pesanan.</p>
            <p style="font-size:13px; margin-bottom:24px;">Yuk mulai belanja di Lapak FSRD UNS!</p>
            <a href="{{ route('lapak.index') }}" class="btn btn-primary">Jelajahi Lapak</a>
        </div>
        @endif
    @endif

    {{-- TAB BOOKINGS --}}
    @if($tab === 'bookings')
        @if($bookings->count() > 0)
        <div style="display:flex; flex-direction:column; gap:12px;">
            @foreach($bookings as $booking)
            <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:18px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                    <div>
                        <strong class="font-mono" style="font-size:14px; color:var(--cerulean-dark);">{{ $booking->booking_number }}</strong>
                        <span style="font-size:12px; color:var(--muted); display:block; margin-top:2px;">
                            {{ $booking->created_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    @php
                        $badge = $badges[$booking->status] ?? ['class' => 'badge-gray', 'label' => $booking->status];
                    @endphp
                    <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                </div>

                {{-- Detail Kelas --}}
                <div style="border-top:1px solid var(--border); padding-top:12px; margin-bottom:12px;">
                    <strong style="font-size:14px; display:block; margin-bottom:6px;">
                        {{ $booking->schedule->trainingClass->name }}
                    </strong>
                    <div style="display:flex; flex-wrap:wrap; gap:12px; font-size:12px; color:var(--muted);">
                        <span>📅 {{ \Carbon\Carbon::parse($booking->schedule->date)->translatedFormat('d F Y') }}</span>
                        <span>⏰ {{ substr($booking->schedule->start_time,0,5) }} – {{ substr($booking->schedule->end_time,0,5) }} WIB</span>
                        <span>📍 {{ $booking->schedule->location }}</span>
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border); padding-top:12px;">
                    <div>
                        <span style="font-size:12px; color:var(--muted);">Total Pembayaran</span>
                        <strong style="font-family:'Montserrat',sans-serif; font-size:16px; color:var(--cerulean-dark); display:block;">
                            Rp {{ number_format($booking->total, 0, ',', '.') }}
                        </strong>
                    </div>
                    @if($booking->status === 'confirmed')
                        <a href="{{ route('booking.pdf', $booking->booking_number) }}"
                           class="btn btn-primary btn-sm" target="_blank">
                            📄 Download Bukti
                        </a>
                    @endif
                </div>

                @if($booking->status === 'rejected' && $booking->rejection_reason)
                <div style="margin-top:12px; padding:10px; background:var(--light-red); border-radius:8px; font-size:12px; color:var(--red);">
                    <strong>Alasan penolakan:</strong> {{ $booking->rejection_reason }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        <div style="margin-top:24px;">{{ $bookings->appends(['tab' => 'bookings'])->links() }}</div>
        @else
        <div style="text-align:center; padding:60px 0; color:var(--muted);">
            <div style="font-size:48px; margin-bottom:16px;">🎓</div>
            <p style="font-size:15px; font-weight:600; margin-bottom:8px;">Belum ada booking pelatihan.</p>
            <p style="font-size:13px; margin-bottom:24px;">Yuk ikuti kelas pelatihan FSRD UNS!</p>
            <a href="{{ route('pelatihan.index') }}" class="btn btn-primary">Lihat Pelatihan</a>
        </div>
        @endif
    @endif

</div>
@endsection

@extends('layouts.admin')

@section('title', 'Manajemen Order')
@section('subtitle', 'Kelola dan verifikasi pembayaran order')

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Daftar Order</span>
    </div>

    {{-- Filter Status --}}
    <div style="padding: 12px 20px; border-bottom:1px solid var(--border); display:flex; gap:6px; flex-wrap:wrap;">
        @php
            $statuses = [
                '' => 'Semua',
                'pending_payment' => 'Belum Bayar',
                'pending_verification' => 'Menunggu Verifikasi',
                'confirmed' => 'Dikonfirmasi',
                'rejected' => 'Ditolak',
                'completed' => 'Selesai',
            ];
        @endphp
        @foreach($statuses as $val => $label)
            <a href="{{ route('admin.orders.index', $val ? ['status' => $val] : []) }}"
               class="btn btn-sm {{ request('status') === $val || (!request('status') && $val === '') ? 'btn-primary' : 'btn-outline' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="panel-body" style="padding:0;">
        @if(session('success'))
            <div class="alert alert-success" style="margin:16px;">{{ session('success') }}</div>
        @endif

        <table class="data-table">
            <thead>
                <tr>
                    <th>No. Order</th>
                    <th>Pembeli</th>
                    <th>Item</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <strong class="font-mono" style="font-size:13px;">{{ $order->order_number }}</strong>
                    </td>
                    <td>
                        <strong style="display:block;">{{ $order->buyer_name }}</strong>
                        <span style="font-size:11px; color:var(--muted);">{{ $order->buyer_email }}</span>
                    </td>
                    <td>{{ $order->items->count() }} item</td>
                    <td>
                        <strong class="text-price" style="font-size:14px;">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </strong>
                    </td>
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
                    <td style="font-size:12px; color:var(--muted);">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-sm">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:var(--muted);">
                        Belum ada order.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="padding:16px 20px;">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

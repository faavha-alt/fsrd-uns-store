@extends('layouts.admin')

@section('title', 'Manajemen Booking')
@section('subtitle', 'Kelola dan verifikasi booking pelatihan')

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Daftar Booking Pelatihan</span>
    </div>

    <div style="padding:12px 20px; border-bottom:1px solid var(--border); display:flex; gap:6px; flex-wrap:wrap;">
        @php
            $statuses = [
                '' => 'Semua',
                'pending_verification' => 'Menunggu Verifikasi',
                'confirmed' => 'Dikonfirmasi',
                'rejected' => 'Ditolak',
                'completed' => 'Selesai',
            ];
        @endphp
        @foreach($statuses as $val => $label)
            <a href="{{ route('admin.bookings.index', $val ? ['status' => $val] : []) }}"
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
                    <th>No. Booking</th>
                    <th>Peserta</th>
                    <th>Kelas</th>
                    <th>Jadwal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td><strong class="font-mono" style="font-size:12px;">{{ $booking->booking_number }}</strong></td>
                    <td>
                        <strong style="display:block;">{{ $booking->participant_name }}</strong>
                        <span style="font-size:11px; color:var(--muted);">{{ $booking->participant_email }}</span>
                    </td>
                    <td>{{ $booking->schedule->trainingClass->name ?? '-' }}</td>
                    <td style="font-size:12px;">
                        {{ $booking->schedule ? \Carbon\Carbon::parse($booking->schedule->date)->format('d M Y') : '-' }}
                    </td>
                    <td><strong>Rp {{ number_format($booking->total, 0, ',', '.') }}</strong></td>
                    <td>
                        @php
                            $badges = [
                                'pending_verification' => ['class' => 'badge-yellow', 'label' => 'Menunggu'],
                                'confirmed' => ['class' => 'badge-blue', 'label' => 'Dikonfirmasi'],
                                'rejected' => ['class' => 'badge-red', 'label' => 'Ditolak'],
                                'completed' => ['class' => 'badge-green', 'label' => 'Selesai'],
                            ];
                            $badge = $badges[$booking->status] ?? ['class' => 'badge-gray', 'label' => $booking->status];
                        @endphp
                        <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-outline btn-sm">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:var(--muted);">Belum ada booking.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding:16px 20px;">{{ $bookings->withQueryString()->links() }}</div>
    </div>
</div>
@endsection

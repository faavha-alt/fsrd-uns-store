@extends('layouts.admin')

@section('title', 'Laporan Transaksi')
@section('subtitle', 'Export data order dan booking ke Excel')

@section('content')

<div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card c-navy">
        <div class="stat-card-top"><div class="stat-icon-wrap">🛒</div></div>
        <div class="stat-num">{{ $totalOrder }}</div>
        <div class="stat-label">Total Order</div>
    </div>
    <div class="stat-card c-gold">
        <div class="stat-card-top"><div class="stat-icon-wrap">🎓</div></div>
        <div class="stat-num">{{ $totalBooking }}</div>
        <div class="stat-label">Total Booking</div>
    </div>
    <div class="stat-card c-green">
        <div class="stat-card-top"><div class="stat-icon-wrap">💰</div></div>
        <div class="stat-num" style="font-size:18px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="stat-label">Pendapatan Order</div>
    </div>
    <div class="stat-card c-purple">
        <div class="stat-card-top"><div class="stat-icon-wrap">📋</div></div>
        <div class="stat-num" style="font-size:18px;">Rp {{ number_format($totalBookingRevenue, 0, ',', '.') }}</div>
        <div class="stat-label">Pendapatan Booking</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">📊 Export Laporan Order</span>
        </div>
        <div class="panel-body">
            <p style="font-size:13px; color:var(--muted); margin-bottom:16px; line-height:1.6;">
                Export data order pembelian produk ke Excel. Kosongkan tanggal untuk export semua data.
            </p>
            <form action="{{ route('admin.reports.orders.export') }}" method="GET">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    📥 Download Excel Order
                </button>
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">📊 Export Laporan Booking</span>
        </div>
        <div class="panel-body">
            <p style="font-size:13px; color:var(--muted); margin-bottom:16px; line-height:1.6;">
                Export data booking pelatihan ke Excel. Kosongkan tanggal untuk export semua data.
            </p>
            <form action="{{ route('admin.reports.bookings.export') }}" method="GET">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    📥 Download Excel Booking
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

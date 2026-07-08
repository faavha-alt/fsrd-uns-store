@extends('layouts.admin')

@section('title', 'Dashboard Kurator')
@section('subtitle', 'Selamat datang, ' . auth()->user()->name . ' — ' . now()->translatedFormat('l, d F Y'))

@section('content')

@php
$badges = [
    'pending'  => ['class' => 'badge-yellow', 'label' => 'Pending'],
    'approved' => ['class' => 'badge-green',  'label' => 'Disetujui'],
    'rejected' => ['class' => 'badge-red',    'label' => 'Ditolak'],
];
@endphp

{{-- STAT CARDS --}}
<div class="stats-grid">
    <div class="stat-card c-navy">
        <div class="stat-card-top"><div class="stat-icon-wrap">📦</div></div>
        <div class="stat-num">{{ $stats['total_produk'] }}</div>
        <div class="stat-label">Total Produk Saya</div>
    </div>
    <div class="stat-card c-gold">
        <div class="stat-card-top"><div class="stat-icon-wrap">✅</div></div>
        <div class="stat-num">{{ $stats['produk_approved'] }}</div>
        <div class="stat-label">Produk Disetujui</div>
    </div>
    <div class="stat-card c-green">
        <div class="stat-card-top"><div class="stat-icon-wrap">🎓</div></div>
        <div class="stat-num">{{ $stats['total_kelas'] }}</div>
        <div class="stat-label">Total Kelas Saya</div>
    </div>
    <div class="stat-card c-purple">
        <div class="stat-card-top">
            <div class="stat-icon-wrap">⏳</div>
            @if($stats['produk_pending'] > 0)
                <span class="badge badge-yellow" style="font-size:10px;">Perlu review</span>
            @endif
        </div>
        <div class="stat-num">{{ $stats['produk_pending'] }}</div>
        <div class="stat-label">Menunggu Approval</div>
    </div>
</div>

{{-- ALERT PENDING --}}
@if($stats['produk_pending'] > 0)
<div style="background:#FEF9C3; border:1px solid #FCD34D; border-radius:10px; padding:14px 18px; margin-bottom:20px; display:flex; align-items:center; gap:12px;">
    <div style="width:36px; height:36px; background:#F59E0B; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">⚡</div>
    <div>
        <strong style="font-size:13px; color:#92400E; display:block;">{{ $stats['produk_pending'] }} produk menunggu persetujuan Admin</strong>
        <span style="font-size:12px; color:#B45309;">Produk tidak akan tampil di publik sampai disetujui Admin.</span>
    </div>
</div>
@endif

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    {{-- PRODUK SAYA --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Produk Saya</span>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Tambah</a>
        </div>
        <div class="panel-body" style="padding:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myProducts as $product)
                    <tr onclick="window.location='{{ route('admin.products.edit', $product) }}'" style="cursor:pointer;">
                        <td><strong>{{ Str::limit($product->name, 25) }}</strong></td>
                        <td style="font-size:12px;">{{ $product->category->name ?? '-' }}</td>
                        <td>
                            @php $b = $badges[$product->status] ?? ['class' => 'badge-gray', 'label' => '-']; @endphp
                            <span class="badge {{ $b['class'] }}" style="font-size:10px;">{{ $b['label'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:24px; color:var(--muted);">
                            Belum ada produk. <a href="{{ route('admin.products.create') }}" style="color:var(--cerulean);">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- KELAS SAYA --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Kelas Pelatihan Saya</span>
            <a href="{{ route('admin.training-classes.create') }}" class="btn btn-primary btn-sm">+ Tambah</a>
        </div>
        <div class="panel-body" style="padding:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Instruktur</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myClasses as $class)
                    <tr onclick="window.location='{{ route('admin.training-classes.edit', $class) }}'" style="cursor:pointer;">
                        <td><strong>{{ Str::limit($class->name, 25) }}</strong></td>
                        <td style="font-size:12px;">{{ $class->instructor->name ?? '-' }}</td>
                        <td>
                            @php $b = $badges[$class->status] ?? ['class' => 'badge-gray', 'label' => '-']; @endphp
                            <span class="badge {{ $b['class'] }}" style="font-size:10px;">{{ $b['label'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:24px; color:var(--muted);">
                            Belum ada kelas. <a href="{{ route('admin.training-classes.create') }}" style="color:var(--cerulean);">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
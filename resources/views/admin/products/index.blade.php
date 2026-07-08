@extends('layouts.admin')

@section('title', 'Produk (Lapak)')
@section('subtitle', 'Kelola produk Lapak Seni & Desain')

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Daftar Produk</span>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Tambah Produk</a>
    </div>

    <div style="padding: 16px 20px; border-bottom:1px solid var(--border); display:flex; gap:8px;">
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline' }}">Semua</a>
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-primary' : 'btn-outline' }}">Menunggu</a>
        <a href="{{ route('admin.products.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') === 'approved' ? 'btn-primary' : 'btn-outline' }}">Disetujui</a>
        <a href="{{ route('admin.products.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-primary' : 'btn-outline' }}">Ditolak</a>
    </div>

    <div class="panel-body" style="padding: 0;">
        @if(session('success'))
            <div class="alert alert-success" style="margin: 20px;">{{ session('success') }}</div>
        @endif

        <table class="data-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Kreator</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->creator->name ?? '-' }}</td>
                    <td class="text-price">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        @if($product->status === 'pending')
                            <span class="badge badge-yellow">Menunggu</span>
                        @elseif($product->status === 'approved')
                            <span class="badge badge-green">Disetujui</span>
                        @else
                            <span class="badge badge-red">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($product->status === 'pending')
                            <form action="{{ route('admin.products.approve', $product) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-icon confirm" title="Setujui">✓</button>
                            </form>
                            <button type="button" class="btn-icon reject" title="Tolak" onclick="document.getElementById('reject-modal-{{ $product->id }}').style.display='flex'">✗</button>
                        @endif
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>

                {{-- Modal tolak produk --}}
                <tr>
                    <td colspan="7" style="padding:0;">
                        <div id="reject-modal-{{ $product->id }}" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:999;">
                            <div style="background:white; border-radius:12px; padding:24px; width:100%; max-width:420px;">
                                <h3 style="font-size:16px; margin-bottom:12px; color:var(--navy);">Tolak Produk: {{ $product->name }}</h3>
                                <form action="{{ route('admin.products.reject', $product) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label">Alasan Penolakan</label>
                                        <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger">Tolak Produk</button>
                                    <button type="button" class="btn btn-outline" onclick="document.getElementById('reject-modal-{{ $product->id }}').style.display='none'">Batal</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-muted" style="text-align:center; padding: 30px;">Belum ada produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

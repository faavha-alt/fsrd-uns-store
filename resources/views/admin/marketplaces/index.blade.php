@extends('layouts.admin')

@section('title', 'Marketplace Links')
@section('subtitle', 'Kelola link marketplace & toko online')

@section('content')

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
@endif

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    {{-- Form Tambah --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">➕ Tambah Marketplace</span>
        </div>
        <div class="panel-body">
            <form action="{{ route('admin.marketplaces.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Marketplace</label>
                    <input type="text" name="name" class="form-control"
                        placeholder="Contoh: Tokopedia, Shopee, Bukalapak"
                        value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">URL Link</label>
                    <input type="url" name="url" class="form-control"
                        placeholder="https://tokopedia.com/toko-anda"
                        value="{{ old('url') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Icon / Logo</label>
                    <input type="file" name="icon" class="form-control"
                        accept="image/*" onchange="previewIcon(event, 'addPreview')">
                    <small style="color:var(--muted); font-size:11px;">PNG/SVG transparan direkomendasikan. Maks 1MB.</small>
                    <div id="addPreview" style="margin-top:8px;"></div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Urutan Tampil</label>
                        <input type="number" name="sort_order" class="form-control"
                            placeholder="0" value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                    <div class="form-group" style="display:flex; align-items:center; gap:8px; padding-top:24px;">
                        <input type="checkbox" name="is_active" value="1" checked
                            style="width:16px; height:16px; accent-color:var(--cerulean);">
                        <label class="form-label" style="margin:0;">Aktifkan</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    ➕ Tambah Marketplace
                </button>
            </form>
        </div>
    </div>

    {{-- Daftar Marketplace --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">📋 Daftar Marketplace</span>
            <span style="font-size:12px; color:var(--muted);">{{ $marketplaces->count() }} marketplace</span>
        </div>
        <div class="panel-body" style="padding:0;">
            @forelse($marketplaces as $mp)
            <div style="padding:14px 16px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:12px;">

                {{-- Icon --}}
                <div style="width:44px; height:44px; border-radius:8px; border:1px solid var(--border);
                    display:flex; align-items:center; justify-content:center; background:white; flex-shrink:0; overflow:hidden;">
                    @if($mp->icon)
                        <img src="{{ asset('storage/'.$mp->icon) }}"
                             style="width:36px; height:36px; object-fit:contain;">
                    @else
                        <span style="font-size:20px;">🛍️</span>
                    @endif
                </div>

                {{-- Info --}}
                <div style="flex:1; min-width:0;">
                    <strong style="font-size:13px; display:block;">{{ $mp->name }}</strong>
                    <a href="{{ $mp->url }}" target="_blank"
                       style="font-size:11px; color:var(--cerulean); display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $mp->url }}
                    </a>
                    <span style="font-size:10px; color:var(--muted);">Urutan: {{ $mp->sort_order }}</span>
                </div>

                {{-- Status & Aksi --}}
                <div style="display:flex; flex-direction:column; gap:4px; align-items:flex-end; flex-shrink:0;">
                    <span class="badge {{ $mp->is_active ? 'badge-green' : 'badge-gray' }}">
                        {{ $mp->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <div style="display:flex; gap:4px;">
                        {{-- Toggle --}}
                        <form action="{{ route('admin.marketplaces.toggle', $mp) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm"
                                title="{{ $mp->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                {{ $mp->is_active ? '⏸' : '▶' }}
                            </button>
                        </form>
                        {{-- Edit --}}
                        <button type="button" class="btn btn-outline btn-sm"
                            onclick="openEdit({{ $mp->id }}, '{{ $mp->name }}', '{{ $mp->url }}', '{{ $mp->sort_order }}', '{{ $mp->icon ? asset('storage/'.$mp->icon) : '' }}')"
                            title="Edit">✏️</button>
                        {{-- Hapus --}}
                        <form action="{{ route('admin.marketplaces.destroy', $mp) }}" method="POST"
                            onsubmit="return confirm('Hapus {{ $mp->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">🗑</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="padding:32px; text-align:center; color:var(--muted); font-size:13px;">
                Belum ada marketplace. Tambahkan di form sebelah kiri.
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- Modal Edit --}}
<div id="editModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
    align-items:center; justify-content:center; z-index:999;">
    <div style="background:white; border-radius:14px; padding:24px; width:100%; max-width:420px; margin:20px;">
        <h3 style="font-size:16px; font-weight:700; color:var(--cerulean-dark); margin-bottom:16px;">
            ✏️ Edit Marketplace
        </h3>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Marketplace</label>
                <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">URL Link</label>
                <input type="url" name="url" id="editUrl" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Icon Baru (opsional)</label>
                <input type="file" name="icon" class="form-control" accept="image/*"
                    onchange="previewIcon(event, 'editPreview')">
                <div id="editCurrentIcon" style="margin-top:8px;"></div>
                <div id="editPreview" style="margin-top:4px;"></div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort_order" id="editOrder" class="form-control" min="0">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:8px; padding-top:24px;">
                    <input type="checkbox" name="is_active" id="editActive" value="1"
                        style="width:16px; height:16px; accent-color:var(--cerulean);">
                    <label class="form-label" style="margin:0;">Aktifkan</label>
                </div>
            </div>
            <div style="display:flex; gap:8px; margin-top:8px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">💾 Simpan</button>
                <button type="button" class="btn btn-outline"
                    onclick="document.getElementById('editModal').style.display='none'">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewIcon(event, targetId) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById(targetId).innerHTML =
            `<img src="${e.target.result}" style="width:48px;height:48px;object-fit:contain;border:1px solid var(--border);border-radius:8px;padding:4px;">`;
    };
    reader.readAsDataURL(file);
}

function openEdit(id, name, url, order, iconUrl) {
    const modal = document.getElementById('editModal');
    document.getElementById('editForm').action = `/admin/marketplaces/${id}`;
    document.getElementById('editName').value  = name;
    document.getElementById('editUrl').value   = url;
    document.getElementById('editOrder').value = order;

    const currentIcon = document.getElementById('editCurrentIcon');
    currentIcon.innerHTML = iconUrl
        ? `<img src="${iconUrl}" style="width:48px;height:48px;object-fit:contain;border:1px solid var(--border);border-radius:8px;padding:4px;" title="Icon saat ini">`
        : '';

    document.getElementById('editPreview').innerHTML = '';
    modal.style.display = 'flex';
}

document.addEventListener('click', function(e) {
    const modal = document.getElementById('editModal');
    if (e.target === modal) modal.style.display = 'none';
});
</script>

@endsection

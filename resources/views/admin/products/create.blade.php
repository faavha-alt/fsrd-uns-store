@extends('layouts.admin')

@section('title', 'Tambah Produk')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.ql-container { border-radius: 0 0 8px 8px !important; border-color: #E5E7EB !important; font-family: 'Poppins', sans-serif !important; }
.ql-toolbar  { border-radius: 8px 8px 0 0 !important; border-color: #E5E7EB !important; background: #F8FAFC !important; }
.ql-editor   { font-size: 13px !important; min-height: 150px; }
.ql-editor.ql-blank::before { font-style: normal !important; color: #9CA3AF !important; }
</style>
@endpush

@section('content')

@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom:20px;">
        @foreach($errors->all() as $error)
            <div>• {{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="panel">
    <div class="panel-header">
        <span class="panel-title">➕ Tambah Produk Baru</span>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>
    <div class="panel-body">
        <form action="{{ route('admin.products.store') }}" method="POST"
              enctype="multipart/form-data" id="productForm">
            @csrf

            {{-- Nama --}}
            <div class="form-group">
                <label class="form-label">Nama Produk <span style="color:red">*</span></label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name') }}" required placeholder="Nama produk">
            </div>

            {{-- Kategori & Kreator --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">Kategori <span style="color:red">*</span></label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kreator <span style="color:red">*</span></label>
                    <select name="creator_id" class="form-control" required>
                        <option value="">-- Pilih Kreator --</option>
                        @foreach($creators as $creator)
                            <option value="{{ $creator->id }}"
                                {{ old('creator_id') == $creator->id ? 'selected' : '' }}>
                                {{ $creator->name }} ({{ ucfirst($creator->type) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Harga & Stok --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">Harga (Rp) <span style="color:red">*</span></label>
                    <input type="number" name="price" class="form-control"
                        value="{{ old('price') }}" min="0" required placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Stok <span style="color:red">*</span></label>
                    <input type="number" name="stock" class="form-control"
                        value="{{ old('stock', 1) }}" min="0" required>
                </div>
            </div>

            {{-- Deskripsi dengan Quill --}}
            <div class="form-group">
                <label class="form-label">Deskripsi Produk</label>
                <div id="quillEditor" style="height:200px; background:white;"></div>
                <input type="hidden" name="description" id="descriptionInput"
                    value="{{ old('description') }}">
                <small style="color:var(--muted); font-size:11px; margin-top:4px; display:block;">
                    Gunakan toolbar untuk format teks: tebal, miring, list, dll.
                </small>
            </div>

            {{-- Foto --}}
            <div class="form-group">
                <label class="form-label">Foto Produk (Maks. 5 foto)</label>
                <input type="file" name="images[]" id="imageInput" class="form-control"
                    accept="image/jpeg,image/jpg,image/png,image/webp"
                    multiple onchange="previewImages(event)">
                <small style="color:var(--muted); font-size:11px; margin-top:4px; display:block;">
                    Format: JPG, PNG, WebP · Maks. <strong>5MB per foto</strong> · Foto pertama jadi foto utama
                </small>
                <div id="imagePreview" style="display:flex; gap:8px; flex-wrap:wrap; margin-top:10px;"></div>
                <div id="imageError" style="display:none; margin-top:8px; padding:10px 14px;
                    background:#FEE2E2; color:#991B1B; border-radius:8px; font-size:12px;
                    border-left:3px solid #EF4444;"></div>
            </div>

            <div style="display:flex; gap:8px; margin-top:8px;">
                <button type="submit" class="btn btn-primary">🚀 Ajukan Produk</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
// ── Quill Editor ──
var quill = new Quill('#quillEditor', {
    theme: 'snow',
    placeholder: 'Tuliskan deskripsi produk secara lengkap...',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'header': [2, 3, false] }],
            ['link'],
            ['clean']
        ]
    }
});

// Isi nilai lama jika validasi gagal
@if(old('description'))
    quill.root.innerHTML = {!! json_encode(old('description')) !!};
@endif

// Submit form — salin konten Quill ke hidden input
document.getElementById('productForm').addEventListener('submit', function(e) {
    var content = quill.root.innerHTML;
    // Kalau cuma <p><br></p> berarti kosong
    if (content === '<p><br></p>') content = '';
    document.getElementById('descriptionInput').value = content;
});

// ── Image Preview ──
function previewImages(event) {
    const preview  = document.getElementById('imagePreview');
    const errorBox = document.getElementById('imageError');
    const input    = event.target;
    preview.innerHTML  = '';
    errorBox.style.display = 'none';

    const files    = Array.from(input.files);
    const maxSize  = 5 * 1024 * 1024;
    const maxFiles = 5;
    const errors   = [];

    if (files.length > maxFiles) {
        errors.push('Maksimal ' + maxFiles + ' foto. Kamu memilih ' + files.length + ' foto.');
        input.value = '';
        showImgError(errors);
        return;
    }

    files.forEach((file, i) => {
        if (file.size > maxSize) {
            errors.push('"' + file.name + '" (' + (file.size/1024/1024).toFixed(1) + 'MB) melebihi batas 5MB.');
        }
    });

    if (errors.length > 0) {
        input.value = '';
        showImgError(errors);
        return;
    }

    files.forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrap  = document.createElement('div');
            wrap.style.cssText = 'position:relative;width:80px;height:80px;flex-shrink:0;';
            const img   = document.createElement('img');
            img.src     = e.target.result;
            img.style.cssText = 'width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--cerulean);';
            const badge = document.createElement('span');
            badge.style.cssText = 'position:absolute;top:4px;left:4px;background:var(--cerulean);color:white;font-size:9px;padding:1px 6px;border-radius:100px;font-weight:700;';
            badge.textContent = i === 0 ? 'Utama' : (i + 1);
            const size  = document.createElement('span');
            size.style.cssText = 'position:absolute;bottom:0;left:0;right:0;text-align:center;background:rgba(0,0,0,0.5);color:white;font-size:9px;padding:2px 0;border-radius:0 0 6px 6px;';
            size.textContent = (file.size/1024/1024).toFixed(1) + 'MB';
            wrap.appendChild(img);
            wrap.appendChild(badge);
            wrap.appendChild(size);
            preview.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
}

function showImgError(errors) {
    const errorBox = document.getElementById('imageError');
    errorBox.innerHTML = '⚠️ <strong>Gagal upload:</strong><br>' + errors.map(e => '• ' + e).join('<br>');
    errorBox.style.display = 'block';
}
</script>
@endpush

@endsection
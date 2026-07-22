@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Kreator (Dosen/Mahasiswa)</label>
                <select name="creator_id" class="form-control" required>
                    <option value="">-- Pilih Kreator --</option>
                    @foreach($creators as $creator)
                        <option value="{{ $creator->id }}" {{ old('creator_id') == $creator->id ? 'selected' : '' }}>{{ $creator->name }} ({{ ucfirst($creator->type) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-group" style="display:flex; gap:16px;">
                <div style="flex:1;">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price') }}" min="0" required>
                </div>
                <div style="flex:1;">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" min="0" required>
                </div>
            </div>

            <div class="form-group">
    <label class="form-label">Upload Foto Baru (opsional, maks. 5 foto)</label>
<input type="file" name="images[]" id="imageInput" class="form-control"
    accept="image/jpeg,image/jpg,image/png,image/webp,image/gif"
    multiple onchange="previewImages(event)">
<small style="color:var(--muted); font-size:11px; margin-top:4px; display:block;">
    Format: JPG, PNG, WebP, GIF · Maks. <strong>5MB per foto</strong> · Maks. 5 foto · Foto pertama jadi foto utama
</small>
<div id="imagePreview" style="display:flex; gap:8px; flex-wrap:wrap; margin-top:10px;"></div>
<div id="imageError" style="display:none; margin-top:8px; padding:10px 14px; background:#FEE2E2; color:#991B1B; border-radius:8px; font-size:12px; border-left:3px solid #EF4444;"></div>

            <button type="submit" class="btn btn-primary">Ajukan Produk</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Batal</a>
        </form>
        @push('scripts')
<script>
function previewImages(event) {
    const preview  = document.getElementById('imagePreview');
    const errorBox = document.getElementById('imageError');
    const input    = event.target;
    preview.innerHTML  = '';
    errorBox.style.display = 'none';
    errorBox.innerHTML = '';

    const files   = Array.from(input.files);
    const maxSize = 5 * 1024 * 1024; // 5MB
    const maxFiles = 5;
    const errors  = [];

    // Validasi jumlah
    if (files.length > maxFiles) {
        errors.push(`Maksimal ${maxFiles} foto. Kamu memilih ${files.length} foto.`);
        input.value = '';
        showError(errors);
        return;
    }

    // Validasi per file
    files.forEach((file, i) => {
        if (file.size > maxSize) {
            const sizeMB = (file.size / 1024 / 1024).toFixed(1);
            errors.push(`"${file.name}" (${sizeMB}MB) melebihi batas 5MB.`);
        }
    });

    if (errors.length > 0) {
        input.value = '';
        showError(errors);
        return;
    }

    // Preview
    files.forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;width:80px;height:80px;flex-shrink:0;';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--cerulean);';

            const badge = document.createElement('span');
            badge.style.cssText = 'position:absolute;top:4px;left:4px;background:var(--cerulean);color:white;font-size:9px;padding:1px 6px;border-radius:100px;font-weight:700;';
            badge.textContent = i === 0 ? 'Utama' : (i + 1);

            // Size info
            const size = document.createElement('span');
            size.style.cssText = 'position:absolute;bottom:4px;left:0;right:0;text-align:center;background:rgba(0,0,0,0.55);color:white;font-size:9px;padding:2px 0;';
            size.textContent = (file.size / 1024 / 1024).toFixed(1) + 'MB';

            wrap.appendChild(img);
            wrap.appendChild(badge);
            wrap.appendChild(size);
            preview.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
}

function showError(errors) {
    const errorBox = document.getElementById('imageError');
    errorBox.innerHTML = '⚠️ <strong>Gagal upload:</strong><br>' + errors.map(e => '• ' + e).join('<br>');
    errorBox.style.display = 'block';
}
</script>
@endpush
    </div>
</div>
@endsection

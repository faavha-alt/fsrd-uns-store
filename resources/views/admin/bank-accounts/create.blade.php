@extends('layouts.admin')

@section('title', 'Tambah Rekening')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.bank-accounts.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Bank</label>
                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" placeholder="Contoh: BCA, BRI, Mandiri" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Rekening</label>
                <input type="text" name="account_number" class="form-control" value="{{ old('account_number') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Atas Nama</label>
                <input type="text" name="account_holder" class="form-control" value="{{ old('account_holder') }}" placeholder="FSRD UNS" required>
            </div>

            <div class="form-group">
                <label class="form-label" style="display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" name="is_active" value="1" checked style="width:auto;">
                    Aktifkan rekening ini
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.bank-accounts.index') }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection

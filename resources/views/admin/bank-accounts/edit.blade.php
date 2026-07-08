@extends('layouts.admin')

@section('title', 'Edit Rekening')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.bank-accounts.update', $bankAccount) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Bank</label>
                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $bankAccount->bank_name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Rekening</label>
                <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $bankAccount->account_number) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Atas Nama</label>
                <input type="text" name="account_holder" class="form-control" value="{{ old('account_holder', $bankAccount->account_holder) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" style="display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" name="is_active" value="1" {{ $bankAccount->is_active ? 'checked' : '' }} style="width:auto;">
                    Aktifkan rekening ini
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.bank-accounts.index') }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection

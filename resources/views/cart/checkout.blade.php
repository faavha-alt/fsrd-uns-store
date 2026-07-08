@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="checkout-container">
    <div class="checkout-title">Selesaikan Pembelian</div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('cart.placeOrder') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="checkout-grid">

            {{-- FORM --}}
            <div>
                {{-- Data Pemesan --}}
                <div class="checkout-section">
                    <div class="checkout-section-title">
                        <div class="step-num">1</div> Data Pemesan
                    </div>
                    <div class="form-2col">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="buyer_name" class="form-control" value="{{ old('buyer_name', auth()->user()->name ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="buyer_phone" class="form-control" value="{{ old('buyer_phone', auth()->user()->phone ?? '') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="buyer_email" class="form-control" value="{{ old('buyer_email', auth()->user()->email ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Pengiriman (untuk produk fisik)</label>
                        <textarea name="buyer_address" class="form-control" rows="3" placeholder="Isi jika membeli produk fisik">{{ old('buyer_address') }}</textarea>
                    </div>
                </div>

                {{-- Pilih Rekening --}}
                <div class="checkout-section">
                    <div class="checkout-section-title">
                        <div class="step-num">2</div> Pilih Rekening Tujuan Transfer
                    </div>
                    <div class="bank-options">
                        @foreach($bankAccounts as $bank)
                        <label class="bank-option {{ $loop->first ? 'selected' : '' }}" onclick="selectBank(this)">
                            <div class="bank-logo" style="background:var(--cerulean); color:white;">
                                {{ strtoupper(substr($bank->bank_name, 0, 3)) }}
                            </div>
                            <div class="bank-detail">
                                <strong>{{ $bank->bank_name }}</strong>
                                <span>{{ $bank->account_number }} — a.n. {{ $bank->account_holder }}</span>
                            </div>
                            <input type="radio" name="bank_account_id" value="{{ $bank->id }}" class="bank-radio" {{ $loop->first ? 'checked' : '' }}>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Upload Bukti --}}
                <div class="checkout-section">
                    <div class="checkout-section-title">
                        <div class="step-num">3</div> Upload Bukti Transfer
                    </div>
                    <div style="background:var(--gold-pale); border:1px solid rgba(233,168,40,0.3); border-radius:8px; padding:12px; margin-bottom:14px; font-size:13px; color:var(--muted);">
                        ⚠️ Pastikan transfer tepat sesuai nominal di bawah termasuk kode unik.
                    </div>
                    <div class="upload-box" onclick="document.getElementById('proofFile').click()">
                        <div class="upload-icon">📤</div>
                        <div class="upload-text">
                            <strong>Klik untuk upload bukti transfer</strong>
                            Format: JPG, PNG, PDF • Maks. 5 MB
                        </div>
                        <input type="file" id="proofFile" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="showFileName(this)" required>
                    </div>
                    <div id="fileName" style="margin-top:8px; font-size:12px; color:var(--cerulean);"></div>
                </div>
            </div>

            {{-- RINGKASAN --}}
            <div class="order-summary-card">
                <div class="summary-title">Ringkasan Pesanan</div>

                @foreach($cart as $item)
                <div class="summary-item">
                    <div class="summary-item-img">
                        @if($item['image'])
                            <img src="{{ asset('storage/'.$item['image']) }}" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
                        @else
                            🎨
                        @endif
                    </div>
                    <div class="summary-item-info">
                        <strong>{{ $item['name'] }}</strong>
                        <span>{{ $item['quantity'] }}x</span>
                    </div>
                    <div class="summary-item-price">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                </div>
                @endforeach

                <div class="summary-row"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <div class="summary-row"><span>Kode unik</span><span>+{{ $uniqueCode }}</span></div>
                <div class="summary-total">
                    <span>Total Transfer</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <div class="kode-unik">
                    <small>Transfer TEPAT sebesar:</small>
                    <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    <div style="font-size:11px; color:var(--muted); margin-top:4px;">
                        Kode unik <strong style="color:var(--gold);">+{{ $uniqueCode }}</strong> membantu verifikasi
                    </div>
                </div>

                <button type="submit" class="btn-submit">✓ Kirim Pesanan</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function selectBank(el) {
    document.querySelectorAll('.bank-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input[type=radio]').checked = true;
}
function showFileName(input) {
    if (input.files.length > 0) {
        document.getElementById('fileName').textContent = '✓ ' + input.files[0].name;
    }
}
</script>
@endpush
@endsection
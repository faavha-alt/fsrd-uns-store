@extends('layouts.app')

@section('title', 'Booking: ' . $schedule->trainingClass->name)

@section('content')
<div class="checkout-container">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('pelatihan.index') }}">Pelatihan</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('pelatihan.show', $schedule->trainingClass) }}">{{ $schedule->trainingClass->name }}</a>
        <span class="breadcrumb-sep">›</span>
        <span style="color:var(--ink);">Booking</span>
    </div>

    <div class="checkout-title">Form Booking Pelatihan</div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('booking.store', $schedule) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="checkout-grid">

            {{-- FORM KIRI --}}
            <div>
                {{-- Info Kelas --}}
                <div class="checkout-section" style="margin-bottom:16px;">
                    <div class="checkout-section-title">
                        <div class="step-num">i</div> Informasi Kelas
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; font-size:13px;">
                        <div>
                            <span style="color:var(--muted); display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:3px;">Nama Kelas</span>
                            <strong>{{ $schedule->trainingClass->name }}</strong>
                        </div>
                        <div>
                            <span style="color:var(--muted); display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:3px;">Instruktur</span>
                            <strong>{{ $schedule->trainingClass->instructor->name }}</strong>
                        </div>
                        <div>
                            <span style="color:var(--muted); display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:3px;">Tanggal</span>
                            <strong>{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d F Y') }}</strong>
                        </div>
                        <div>
                            <span style="color:var(--muted); display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:3px;">Waktu</span>
                            <strong>{{ substr($schedule->start_time,0,5) }} – {{ substr($schedule->end_time,0,5) }} WIB</strong>
                        </div>
                        <div>
                            <span style="color:var(--muted); display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:3px;">Lokasi</span>
                            <strong>{{ $schedule->location }}</strong>
                        </div>
                        <div>
                            <span style="color:var(--muted); display:block; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:3px;">Sisa Slot</span>
                            <strong style="color:var(--green);">{{ $schedule->remainingSlots() }} slot tersisa</strong>
                        </div>
                    </div>
                </div>

                {{-- Data Peserta --}}
                <div class="checkout-section" style="margin-bottom:16px;">
                    <div class="checkout-section-title">
                        <div class="step-num">1</div> Data Peserta
                    </div>
                    <div class="form-2col">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap Peserta</label>
                            <input type="text" name="participant_name" class="form-control"
                                value="{{ old('participant_name', auth()->user()->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="participant_phone" class="form-control"
                                value="{{ old('participant_phone', auth()->user()->phone ?? '') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="participant_email" class="form-control"
                            value="{{ old('participant_email', auth()->user()->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Asal Instansi / Sekolah / Kampus (opsional)</label>
                        <input type="text" name="institution" class="form-control"
                            value="{{ old('institution') }}" placeholder="Contoh: Universitas Sebelas Maret">
                    </div>
                </div>

                {{-- Pilih Rekening --}}
                <div class="checkout-section" style="margin-bottom:16px;">
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
                            <input type="radio" name="bank_account_id" value="{{ $bank->id }}"
                                class="bank-radio" {{ $loop->first ? 'checked' : '' }}>
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
                        ⚠️ Transfer tepat sesuai nominal di ringkasan termasuk kode unik — untuk memudahkan verifikasi.
                    </div>
                    <div class="upload-box" onclick="document.getElementById('proofFile').click()">
                        <div class="upload-icon">📤</div>
                        <div class="upload-text">
                            <strong>Klik untuk upload bukti transfer</strong>
                            Format: JPG, PNG, PDF • Maks. 5 MB
                        </div>
                        <input type="file" id="proofFile" name="payment_proof"
                            accept=".jpg,.jpeg,.png,.pdf" style="display:none;"
                            onchange="showFileName(this)" required>
                    </div>
                    <div id="fileName" style="margin-top:8px; font-size:12px; color:var(--cerulean);"></div>
                </div>
            </div>

            {{-- RINGKASAN KANAN --}}
<div class="order-summary-card">
    <div class="summary-title">Ringkasan Booking</div>

    <div style="margin-bottom:16px; padding-bottom:16px; border-bottom:1px solid var(--border);">
        <strong style="font-size:14px; display:block; margin-bottom:4px;">{{ $schedule->trainingClass->name }}</strong>
        <span style="font-size:12px; color:var(--muted); display:block;">📅 {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d F Y') }}</span>
        <span style="font-size:12px; color:var(--muted); display:block;">⏰ {{ substr($schedule->start_time,0,5) }} – {{ substr($schedule->end_time,0,5) }} WIB</span>
        <span style="font-size:12px; color:var(--muted); display:block;">📍 {{ $schedule->location }}</span>
    </div>

    <div class="summary-row">
        <span>Biaya Pelatihan</span>
        <span>Rp {{ number_format($schedule->trainingClass->price, 0, ',', '.') }}</span>
    </div>
    <div class="summary-row">
        <span>Kode Unik</span>
        <span style="color:var(--cerulean); font-weight:700;">+{{ $uniqueCode }}</span>
    </div>

    <div class="summary-total">
        <span>Total Transfer</span>
        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
    </div>

    {{-- Info Rekening Terpilih --}}
    <div id="selectedBankInfo" style="background:var(--sky-pale); border:1px solid rgba(31,171,225,0.2);
        border-radius:8px; padding:12px; font-size:12px; color:var(--cerulean-dark); margin-bottom:12px;">
    </div>

    {{-- Peringatan kode unik --}}
    <div style="background:var(--gold-pale); border:1px solid rgba(233,168,40,0.4);
        border-radius:8px; padding:12px; font-size:12px; color:#92400E; margin-bottom:16px; line-height:1.6;">
        ⚠️ <strong>Penting!</strong> Transfer TEPAT
        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
        termasuk kode unik <strong>{{ $uniqueCode }}</strong> di akhir nominal.
        Nominal berbeda akan memperlambat verifikasi.
    </div>

    <button type="submit" class="btn-submit">✓ Kirim Booking</button>

    <a href="{{ route('pelatihan.show', $schedule->trainingClass) }}"
        style="display:block; text-align:center; margin-top:12px; font-size:13px; color:var(--muted);">
        ← Kembali ke Detail Kelas
    </a>
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
    updateBankDetail(el);
}

function updateBankDetail(el) {
    const bankName   = el.querySelector('strong').textContent;
    const bankDetail = el.querySelector('span').textContent;
    document.getElementById('selectedBankInfo').innerHTML =
        '🏦 Transfer ke: <strong>' + bankName + '</strong> — ' + bankDetail;
}

function showFileName(input) {
    if (input.files.length > 0) {
        document.getElementById('fileName').textContent = '✓ ' + input.files[0].name;
    }
}

// Init bank info pertama
document.addEventListener('DOMContentLoaded', function() {
    const firstBank = document.querySelector('.bank-option');
    if (firstBank) updateBankDetail(firstBank);
});
</script>
@endpush
@endsection

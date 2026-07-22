<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Booking;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\MailHelper;
use App\Mail\BookingPlaced;
use Illuminate\Support\Facades\Mail;
use App\Helpers\NotificationHelper;


class BookingController extends Controller
{
    // Form booking per jadwal
    public function create(ClassSchedule $schedule)
{
    if ($schedule->remainingSlots() <= 0) {
        return redirect()->route('pelatihan.show', $schedule->trainingClass)
            ->with('error', 'Maaf, slot untuk jadwal ini sudah penuh.');
    }

    $bankAccounts = \App\Models\BankAccount::where('is_active', true)->get();
    
    // Generate kode unik dan simpan di session
    $uniqueCode = rand(100, 999);
    session(['booking_unique_code_' . $schedule->id => $uniqueCode]);
    
    $total = $schedule->trainingClass->price + $uniqueCode;

    return view('booking.create', compact('schedule', 'bankAccounts', 'uniqueCode', 'total'));
}

    // Proses booking
    public function store(Request $request, ClassSchedule $schedule)
    {
        if ($schedule->isFull()) {
            return back()->with('error', 'Maaf, jadwal ini sudah penuh.');
        }

        $request->validate([
            'participant_name' => 'required|string|max:255',
            'participant_email' => 'required|email',
            'participant_phone' => 'required|string|max:20',
            'institution' => 'nullable|string|max:255',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Ambil kode unik dari session
$uniqueCode = session('booking_unique_code_' . $schedule->id, rand(100, 999));
$total = $schedule->trainingClass->price + $uniqueCode;

        $proofPath = $request->file('payment_proof')->store('booking-proofs', 'public');

        $booking = Booking::create([
            'booking_number' => 'BKG-' . strtoupper(Str::random(8)),
            'class_schedule_id' => $schedule->id,
            'user_id' => auth()->id(),
            'participant_name' => $request->participant_name,
            'participant_email' => $request->participant_email,
            'participant_phone' => $request->participant_phone,
            'institution' => $request->institution,
            'bank_account_id' => $request->bank_account_id,
            'unique_code' => $uniqueCode,
            'total' => $total,
            'payment_proof' => $proofPath,
            'status' => 'pending_verification',
            'expires_at' => now()->addHours(24),
        ]);

        // Update booked count
        $schedule->increment('booked_count');
        // Kirim email notifikasi
try {
    MailHelper::configure();
    $booking->load(['schedule.trainingClass', 'bankAccount']);

    if (MailHelper::isEnabled('notif_admin_booking_enabled') && MailHelper::adminEmail()) {
        Mail::to(MailHelper::adminEmail())->send(new BookingPlaced($booking));
    }
} catch (\Exception $e) {
    \Log::error('Email gagal: ' . $e->getMessage());
}

        NotificationHelper::add(
    'booking',
    'Booking baru: ' . $booking->booking_number . ' dari ' . $booking->participant_name,
    route('admin.bookings.show', $booking)
);

        return redirect()->route('booking.success', $booking->booking_number);
    }

    // Halaman sukses
    public function success($bookingNumber)
    {
        $booking = Booking::with(['schedule.trainingClass', 'bankAccount'])
            ->where('booking_number', $bookingNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('booking.success', compact('booking'));
    }

    // Riwayat booking buyer
    public function myBookings()
    {
        $bookings = Booking::with(['schedule.trainingClass'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('booking.my-bookings', compact('bookings'));


    }

    public function downloadPdf($bookingNumber)
{
    $booking = Booking::with([
        'schedule.trainingClass.instructor',
        'schedule.trainingClass.category',
        'bankAccount',
        'user'
    ])
        ->where('booking_number', $bookingNumber)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    if ($booking->status !== 'confirmed') {
        return back()->with('error', 'Bukti hanya tersedia setelah booking dikonfirmasi.');
    }

    $pdf = Pdf::loadView('booking.pdf', compact('booking'))
        ->setPaper('a4', 'portrait');

    return $pdf->download('Bukti-Booking-'.$booking->booking_number.'.pdf');
}
}



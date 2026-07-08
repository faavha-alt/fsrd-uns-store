<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Helpers\MailHelper;
use App\Mail\BookingConfirmed;
use App\Mail\BookingRejected;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['schedule.trainingClass', 'bankAccount'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(20);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['schedule.trainingClass', 'bankAccount', 'verifier']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function confirm(Booking $booking)
{
    $booking->update([
        'status' => 'confirmed',
        'verified_by' => auth()->id(),
    ]);

    try {
        MailHelper::configure();
        $booking->load(['schedule.trainingClass.instructor', 'bankAccount']);
        if (MailHelper::isEnabled('notif_buyer_booking_confirmed') && $booking->participant_email) {
            Mail::to($booking->participant_email)->send(new BookingConfirmed($booking));
        }
    } catch (\Exception $e) {
        \Log::error('Email gagal: ' . $e->getMessage());
    }

    return back()->with('success', 'Booking #'.$booking->booking_number.' berhasil dikonfirmasi.');
}

    public function reject(Request $request, Booking $booking)
{
    $request->validate([
        'rejection_reason' => 'required|string|max:500',
    ]);

    $booking->schedule->decrement('booked_count');

    $booking->update([
        'status' => 'rejected',
        'rejection_reason' => $request->rejection_reason,
        'verified_by' => auth()->id(),
    ]);

    try {
        MailHelper::configure();
        $booking->load(['schedule.trainingClass']);
        if (MailHelper::isEnabled('notif_buyer_booking_rejected') && $booking->participant_email) {
            Mail::to($booking->participant_email)->send(new BookingRejected($booking));
        }
    } catch (\Exception $e) {
        \Log::error('Email gagal: ' . $e->getMessage());
    }

    return back()->with('success', 'Booking #'.$booking->booking_number.' ditolak.');
}
}

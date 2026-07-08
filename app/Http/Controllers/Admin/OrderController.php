<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\MailHelper;
use App\Mail\OrderConfirmed;
use App\Mail\OrderRejected;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items', 'bankAccount'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'bankAccount', 'verifier']);
        return view('admin.orders.show', compact('order'));
    }

    public function confirm(Order $order)
{
    $order->update([
        'status' => 'confirmed',
        'verified_by' => auth()->id(),
    ]);

    // Kirim email ke buyer
    try {
        MailHelper::configure();
        $order->load(['items', 'bankAccount']);
        if (MailHelper::isEnabled('notif_buyer_order_confirmed') && $order->buyer_email) {
            Mail::to($order->buyer_email)->send(new OrderConfirmed($order));
        }
    } catch (\Exception $e) {
        \Log::error('Email gagal: ' . $e->getMessage());
    }

    return back()->with('success', 'Pembayaran dikonfirmasi. Order #'.$order->order_number.' berhasil diverifikasi.');
}

    public function reject(Request $request, Order $order)
{
    $request->validate([
        'rejection_reason' => 'required|string|max:500',
    ]);

    $order->update([
        'status' => 'rejected',
        'rejection_reason' => $request->rejection_reason,
        'verified_by' => auth()->id(),
    ]);

    // Kirim email ke buyer
    try {
        MailHelper::configure();
        if (MailHelper::isEnabled('notif_buyer_order_rejected') && $order->buyer_email) {
            Mail::to($order->buyer_email)->send(new OrderRejected($order));
        }
    } catch (\Exception $e) {
        \Log::error('Email gagal: ' . $e->getMessage());
    }

    return back()->with('success', 'Order #'.$order->order_number.' ditolak.');
}

    public function complete(Order $order)
    {
        $order->update(['status' => 'completed']);
        return back()->with('success', 'Order #'.$order->order_number.' selesai.');
    }
}
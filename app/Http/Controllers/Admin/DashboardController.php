<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TrainingClass;
use App\Models\Order;
use App\Models\Booking;
use App\Models\Creator;
use App\Models\User;
use App\Enums\UserRole;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === UserRole::Admin) {
            return $this->adminDashboard();
        }

        return $this->kuratorDashboard($user);
    }

    private function adminDashboard()
    {
        $stats = [
            'total_produk'     => Product::where('status', 'approved')->count(),
            'total_kelas'      => TrainingClass::where('status', 'approved')->count(),
            'order_pending'    => Order::where('status', 'pending_verification')->count(),
            'booking_pending'  => Booking::where('status', 'pending_verification')->count(),
            'total_pendapatan' => Order::where('status', 'completed')->sum('subtotal'),
            'total_kreator'    => Creator::count(),
            'total_buyer'      => User::where('role', 'buyer')->count(),
            'produk_pending'   => Product::where('status', 'pending')->count(),
        ];

        $recentOrders = Order::with(['items'])
            ->latest()->take(5)->get();

        $recentBookings = Booking::with(['schedule.trainingClass'])
            ->latest()->take(5)->get();

        $pendingProducts = Product::with(['category', 'creator'])
            ->where('status', 'pending')
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'recentBookings',
            'pendingProducts'
        ));
    }

    private function kuratorDashboard($user)
    {
        $stats = [
            'total_produk'    => Product::where('curator_id', $user->id)->count(),
            'produk_approved' => Product::where('curator_id', $user->id)->where('status', 'approved')->count(),
            'produk_pending'  => Product::where('curator_id', $user->id)->where('status', 'pending')->count(),
            'total_kelas'     => TrainingClass::where('curator_id', $user->id)->count(),
            'kelas_approved'  => TrainingClass::where('curator_id', $user->id)->where('status', 'approved')->count(),
        ];

        $myProducts = Product::with(['category', 'creator'])
            ->where('curator_id', $user->id)
            ->latest()->take(5)->get();

        $myClasses = TrainingClass::with(['category', 'instructor'])
            ->where('curator_id', $user->id)
            ->latest()->take(5)->get();

        return view('admin.dashboard-kurator', compact(
            'stats',
            'myProducts',
            'myClasses'
        ));
    }
}
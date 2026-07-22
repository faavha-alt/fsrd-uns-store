<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/lapak', [\App\Http\Controllers\LapakController::class, 'index'])->name('lapak.index');
Route::get('/lapak/{product:slug}', [\App\Http\Controllers\LapakController::class, 'show'])->name('lapak.show');
Route::get('/pelatihan', [\App\Http\Controllers\PelatihanController::class, 'index'])->name('pelatihan.index');
Route::get('/pelatihan/{trainingClass:slug}', [\App\Http\Controllers\PelatihanController::class, 'show'])->name('pelatihan.show');
Route::get('/kreator', [\App\Http\Controllers\CreatorController::class, 'index'])->name('creator.index');
Route::get('/kreator/{creator}', [\App\Http\Controllers\CreatorController::class, 'show'])->name('creator.show');
Route::get('/tentang', function () { return view('tentang'); })->name('tentang');

// Keranjang (tanpa login)
Route::get('/keranjang', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/keranjang/update/{productId}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/keranjang/hapus/{productId}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

// Forgot & Reset Password Buyer
Route::get('/lupa-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgotForm'])->name('buyer.forgot-password');
Route::post('/lupa-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLink'])->name('buyer.forgot-password.send');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('buyer.reset-password');
Route::post('/reset-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('buyer.reset-password.update');

// Order success
Route::get('/order/sukses/{orderNumber}', function ($orderNumber) {
    $order = \App\Models\Order::with('items', 'bankAccount')
        ->where('order_number', $orderNumber)->firstOrFail();
    return view('cart.success', compact('order'));
})->name('order.success');

// Tutorial cara pembelian
Route::get('/cara-pembelian', function () {
    return view('cara-pembelian');
})->name('cara-pembelian');

// Google OAuth
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'callback'])->name('auth.google.callback');


// ============================================================
// AUTH ADMIN
// ============================================================

Route::get('/management-fsrd/masuk', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/management-fsrd/masuk', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// ============================================================
// AUTH BUYER
// ============================================================

Route::get('/login-buyer', [\App\Http\Controllers\Auth\BuyerAuthController::class, 'showLogin'])->name('buyer.login');
Route::post('/login-buyer', [\App\Http\Controllers\Auth\BuyerAuthController::class, 'login'])->name('buyer.login.submit');
Route::get('/register', [\App\Http\Controllers\Auth\BuyerAuthController::class, 'showRegister'])->name('buyer.register');
Route::post('/register', [\App\Http\Controllers\Auth\BuyerAuthController::class, 'register'])->name('buyer.register.submit');
Route::post('/logout-buyer', [\App\Http\Controllers\Auth\BuyerAuthController::class, 'logout'])->name('buyer.logout');


// ============================================================
// BUYER ROUTES (wajib login buyer)
// ============================================================

Route::middleware('buyer')->group(function () {
    // Checkout
    Route::get('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [\App\Http\Controllers\CartController::class, 'placeOrder'])->name('cart.placeOrder');

    // Booking — urutan penting: sukses & bukti sebelum /{schedule}
    Route::get('/booking/sukses/{bookingNumber}', [\App\Http\Controllers\BookingController::class, 'success'])->name('booking.success');
    Route::get('/booking/bukti/{bookingNumber}', [\App\Http\Controllers\BookingController::class, 'downloadPdf'])->name('booking.pdf');
    Route::get('/booking/{schedule}', [\App\Http\Controllers\BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{schedule}', [\App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');

    // Akun Buyer
    Route::prefix('akun')->name('buyer.')->group(function () {
        Route::get('/', [\App\Http\Controllers\BuyerAccountController::class, 'index'])->name('account');
        Route::get('/orders/{orderNumber}', [\App\Http\Controllers\BuyerAccountController::class, 'orderDetail'])->name('order.detail');
    });
});


// ============================================================
// ADMIN ROUTES
// ============================================================

Route::middleware(['auth', 'admin.timeout'])->prefix('admin')->name('admin.')->group(function () {

    // ----- ADMIN + KURATOR -----
    Route::middleware('admin.or.kurator')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('creators', \App\Http\Controllers\Admin\CreatorController::class);
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        Route::resource('training-classes', \App\Http\Controllers\Admin\TrainingClassController::class);

        // Jadwal kelas (nested)
        Route::prefix('training-classes/{trainingClass}/schedules')
            ->name('training-classes.schedules.')
            ->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\ClassScheduleController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\Admin\ClassScheduleController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\ClassScheduleController::class, 'store'])->name('store');
                Route::delete('/{schedule}', [\App\Http\Controllers\Admin\ClassScheduleController::class, 'destroy'])->name('destroy');
            });
    });

    // ----- ADMIN ONLY -----
    Route::middleware('admin')->group(function () {

        // Approve / Reject Produk
        Route::post('products/{product}/approve', [\App\Http\Controllers\Admin\ProductController::class, 'approve'])->name('products.approve');
        Route::post('products/{product}/reject', [\App\Http\Controllers\Admin\ProductController::class, 'reject'])->name('products.reject');

        // Approve / Reject Pelatihan
        Route::post('training-classes/{trainingClass}/approve', [\App\Http\Controllers\Admin\TrainingClassController::class, 'approve'])->name('training-classes.approve');
        Route::post('training-classes/{trainingClass}/reject', [\App\Http\Controllers\Admin\TrainingClassController::class, 'reject'])->name('training-classes.reject');

        // Order
        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/confirm', [\App\Http\Controllers\Admin\OrderController::class, 'confirm'])->name('orders.confirm');
        Route::post('orders/{order}/reject', [\App\Http\Controllers\Admin\OrderController::class, 'reject'])->name('orders.reject');
        Route::post('orders/{order}/complete', [\App\Http\Controllers\Admin\OrderController::class, 'complete'])->name('orders.complete');

        // Booking
        Route::get('bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
        Route::post('bookings/{booking}/confirm', [\App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('bookings/{booking}/reject', [\App\Http\Controllers\Admin\BookingController::class, 'reject'])->name('bookings.reject');

        // Rekening Bank
        Route::resource('bank-accounts', \App\Http\Controllers\Admin\BankAccountController::class);

        // Management User
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
        Route::post('users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');

        // Site Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        // Email Settings
        Route::get('email-settings', [\App\Http\Controllers\Admin\EmailSettingController::class, 'index'])->name('email-settings.index');
        Route::post('email-settings', [\App\Http\Controllers\Admin\EmailSettingController::class, 'update'])->name('email-settings.update');
        Route::post('email-settings/test', [\App\Http\Controllers\Admin\EmailSettingController::class, 'test'])->name('email-settings.test');

        // Activity Log
        Route::get('activity-log', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-log.index');

	// Marketplace
	Route::resource('marketplaces', \App\Http\Controllers\Admin\MarketplaceController::class)->except(['show', 'edit', 'create']);
	Route::post('marketplaces/{marketplace}/toggle', [\App\Http\Controllers\Admin\MarketplaceController::class, 'toggleActive'])->name('marketplaces.toggle');

	// Laporan
	Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
	Route::get('reports/orders/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportOrders'])->name('reports.orders.export');
	Route::get('reports/bookings/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportBookings'])->name('reports.bookings.export');

        Route::get('notifications/read-all', function() {
        \App\Helpers\NotificationHelper::markAllRead();
            return back();
        })->name('notifications.read-all');


        Route::post('email-settings/google', [\App\Http\Controllers\Admin\EmailSettingController::class, 'updateGoogle'])->name('email-settings.google');
    });
});

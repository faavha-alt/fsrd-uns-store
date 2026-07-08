<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BankAccount;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\MailHelper;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;
use App\Helpers\NotificationHelper;




class CartController extends Controller
{
    // Tampilkan keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        $bankAccounts = BankAccount::where('is_active', true)->get();
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('cart.index', compact('cart', 'bankAccounts', 'subtotal'));
    }

    // Tambah ke keranjang
    public function add(Request $request, Product $product)
    {
        if ($product->stock <= 0) {
            return back()->with('error', 'Stok produk habis.');
        }

        $cart = session()->get('cart', []);
        $key = $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += 1;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->images[0] ?? null,
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    // Update quantity
    public function update(Request $request, $productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $qty = max(1, (int) $request->quantity);
            $cart[$productId]['quantity'] = $qty;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    // Hapus dari keranjang
    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    // Halaman checkout
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('lapak.index')->with('error', 'Keranjang kosong.');
        }

        $bankAccounts = BankAccount::where('is_active', true)->get();
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $uniqueCode = rand(100, 999);
        $total = $subtotal + $uniqueCode;

        session()->put('unique_code', $uniqueCode);

        return view('cart.checkout', compact('cart', 'bankAccounts', 'subtotal', 'uniqueCode', 'total'));
    }

    // Proses order
    public function placeOrder(Request $request)
    {
        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email',
            'buyer_phone' => 'required|string|max:20',
            'buyer_address' => 'nullable|string',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('lapak.index')->with('error', 'Keranjang kosong.');
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $uniqueCode = session()->get('unique_code', rand(100, 999));
        $total = $subtotal + $uniqueCode;

        // Upload bukti
        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

        // Buat order
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => auth()->id(),
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $request->buyer_email,
            'buyer_phone' => $request->buyer_phone,
            'buyer_address' => $request->buyer_address,
            'bank_account_id' => $request->bank_account_id,
            'subtotal' => $subtotal,
            'unique_code' => $uniqueCode,
            'total' => $total,
            'payment_proof' => $proofPath,
            'status' => 'pending_verification',
            'expires_at' => now()->addHours(24),
        ]);

        // Buat order items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        // Kosongkan keranjang
        session()->forget(['cart', 'unique_code']);
        // Kirim email notifikasi
        try {
            MailHelper::configure();
            $order->load(['items', 'bankAccount']);

        if (MailHelper::isEnabled('notif_admin_order_enabled') && MailHelper::adminEmail()) {
        Mail::to(MailHelper::adminEmail())->send(new OrderPlaced($order));
            }
        } catch (\Exception $e) {
        // Gagal kirim email tidak menghentikan proses
        \Log::error('Email gagal: ' . $e->getMessage());
        }

        // Notifikasi admin
NotificationHelper::add(
    'order',
    'Order baru: ' . $order->order_number . ' dari ' . $order->buyer_name,
    route('admin.orders.show', $order)
);

        return redirect()->route('buyer.account', ['tab' => 'orders'])
            ->with('order_success', $order->order_number);
    }
}

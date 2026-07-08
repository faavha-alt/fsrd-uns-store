<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Creator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'creator'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('type', 'produk')->orderBy('name')->get();
        $creators = Creator::orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'creators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'creator_id' => 'required|exists:creators,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['curator_id'] = auth()->id();
        $validated['status'] = 'pending';

        if ($request->hasFile('images')) {
    // Hapus gambar lama
    if ($product->images) {
        foreach ($product->images as $img) {
            ImageHelper::delete($img);
        }
    }
    $validated['images'] = ImageHelper::uploadMultiple(
        $request->file('images'),
        'products',
        800, 600
    );
}

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diajukan, menunggu persetujuan Admin.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('type', 'produk')->orderBy('name')->get();
        $creators = Creator::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'creators'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'creator_id' => 'required|exists:creators,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images.*' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('products', 'public');
            }
            $validated['images'] = $paths;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    // ===== APPROVAL ACTIONS =====

    public function approve(Product $product)
    {
        $product->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Produk berhasil disetujui.');
    }

    public function reject(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $product->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Produk ditolak.');
    }
}
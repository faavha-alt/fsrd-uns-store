<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarketplaceController extends Controller
{
    public function index()
    {
        $marketplaces = Marketplace::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.marketplaces.index', compact('marketplaces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'url'        => 'required|url',
            'icon'       => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:1024',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only(['name', 'url', 'sort_order']);
        $data['is_active']   = $request->has('is_active');
        $data['sort_order']  = $request->sort_order ?? 0;

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('marketplaces', 'public');
            $data['icon'] = $path;
        }

        Marketplace::create($data);

        return back()->with('success', 'Marketplace berhasil ditambahkan!');
    }

    public function update(Request $request, Marketplace $marketplace)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'url'        => 'required|url',
            'icon'       => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:1024',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only(['name', 'url', 'sort_order']);
        $data['is_active']  = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('icon')) {
            // Hapus icon lama
            if ($marketplace->icon) {
                Storage::disk('public')->delete($marketplace->icon);
            }
            $data['icon'] = $request->file('icon')->store('marketplaces', 'public');
        }

        $marketplace->update($data);

        return back()->with('success', 'Marketplace berhasil diperbarui!');
    }

    public function destroy(Marketplace $marketplace)
    {
        if ($marketplace->icon) {
            Storage::disk('public')->delete($marketplace->icon);
        }
        $marketplace->delete();

        return back()->with('success', 'Marketplace berhasil dihapus!');
    }

    public function toggleActive(Marketplace $marketplace)
    {
        $marketplace->update(['is_active' => !$marketplace->is_active]);
        return back()->with('success', 'Status marketplace diperbarui!');
    }
}

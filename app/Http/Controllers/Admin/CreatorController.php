<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Creator;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;

class CreatorController extends Controller
{
    public function index()
    {
        $creators = Creator::orderBy('name')->get();
        return view('admin.creators.index', compact('creators'));
    }

    public function create()
    {
        return view('admin.creators.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:dosen,mahasiswa',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
    ImageHelper::delete($creator->photo);
    $validated['photo'] = ImageHelper::upload(
        $request->file('photo'),
        'creators',
        400, 400
    );
}

        Creator::create($validated);

        return redirect()->route('admin.creators.index')->with('success', 'Kreator berhasil ditambahkan.');
    }

    public function edit(Creator $creator)
    {
        return view('admin.creators.edit', compact('creator'));
    }

    public function update(Request $request, Creator $creator)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:dosen,mahasiswa',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('creators', 'public');
        }

        $creator->update($validated);

        return redirect()->route('admin.creators.index')->with('success', 'Kreator berhasil diperbarui.');
    }

    public function destroy(Creator $creator)
    {
        $creator->delete();

        return redirect()->route('admin.creators.index')->with('success', 'Kreator berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_wa' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'site_logo' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:5120',
        ]);

        // Upload logo
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path);
        }

        // Upload hero image
        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('settings', 'public');
            Setting::set('hero_image', $path);
        }

        // Save semua setting teks
        $keys = [
    'site_name', 'site_tagline', 'site_description',
    'hero_title', 'hero_subtitle',
    'contact_email', 'contact_wa', 'contact_address',
    'instagram_url', 'youtube_url', 'facebook_url', 'twitter_url',
    'about_title', 'about_description', 'about_history',
    'about_vision', 'about_mission',
];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key));
        }

        // Clear semua cache setting
        Cache::flush();

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}

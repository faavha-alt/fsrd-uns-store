<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class EmailSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.email-settings.index', compact('settings'));
    }

    public function updateGoogle(Request $request)
{
    $request->validate([
        'google_client_id'     => 'required|string',
        'google_client_secret' => 'required|string',
        'google_redirect_uri'  => 'required|url',
    ]);

    Setting::set('google_client_id',     $request->google_client_id);
    Setting::set('google_client_secret', $request->google_client_secret);
    Setting::set('google_redirect_uri',  $request->google_redirect_uri);
    Setting::set('google_oauth_enabled', $request->has('google_oauth_enabled') ? '1' : '0');

    Cache::flush();

    return back()->with('success', 'Pengaturan Google OAuth berhasil disimpan!')->withFragment('tab-google');
}

    public function update(Request $request)
    {
        $request->validate([
            'mail_host'        => 'required|string',
            'mail_port'        => 'required|numeric',
            'mail_username'    => 'required|email',
            'mail_password'    => 'nullable|string',
            'mail_encryption'  => 'required|in:tls,ssl,none',
            'mail_from_name'   => 'required|string',
            'mail_from_address'=> 'required|email',
            'notif_admin_email'=> 'required|email',
        ]);

        $keys = [
            'mail_host', 'mail_port', 'mail_username',
            'mail_encryption', 'mail_from_name', 'mail_from_address',
            'notif_admin_email',
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key));
        }

        // Password hanya update kalau diisi
        if ($request->filled('mail_password')) {
            Setting::set('mail_password', $request->mail_password);
        }

        // Toggle notifikasi
        $toggles = [
            'notif_admin_order_enabled',
            'notif_admin_booking_enabled',
            'notif_buyer_order_confirmed',
            'notif_buyer_order_rejected',
            'notif_buyer_booking_confirmed',
            'notif_buyer_booking_rejected',
        ];

        foreach ($toggles as $key) {
            Setting::set($key, $request->has($key) ? '1' : '0');
        }

        Cache::flush();

        return back()->with('success', 'Pengaturan email berhasil disimpan!');
    }

    public function test(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $this->applyMailConfig();

            Mail::raw('Ini adalah email percobaan dari FSRD UNS Store. Konfigurasi SMTP Anda berfungsi dengan baik! 🎉', function ($m) use ($request) {
                $m->to($request->test_email)
                  ->subject('✅ Test Email — FSRD UNS Store');
            });

            return back()->with('success', 'Email test berhasil dikirim ke ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal kirim email: ' . $e->getMessage());
        }
    }

    public static function applyMailConfig()
    {
        config([
            'mail.default'                         => 'smtp',
            'mail.mailers.smtp.host'               => Setting::get('mail_host', 'smtp.gmail.com'),
            'mail.mailers.smtp.port'               => Setting::get('mail_port', 587),
            'mail.mailers.smtp.username'           => Setting::get('mail_username'),
            'mail.mailers.smtp.password'           => Setting::get('mail_password'),
            'mail.mailers.smtp.encryption'         => Setting::get('mail_encryption', 'tls'),
            'mail.from.address'                    => Setting::get('mail_from_address'),
            'mail.from.name'                       => Setting::get('mail_from_name', 'FSRD UNS Store'),
        ]);
    }
}

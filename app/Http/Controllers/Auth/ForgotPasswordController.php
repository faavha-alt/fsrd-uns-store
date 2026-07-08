<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\MailHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    // Form lupa password
    public function showForgotForm()
    {
        return view('buyer.auth.forgot-password');
    }

    // Kirim link reset
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak terdaftar di sistem kami.',
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'buyer')
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar sebagai buyer.',
            ]);
        }

        // Generate token
        $token = Str::random(64);

        // Hapus token lama
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Simpan token baru
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        // Kirim email
        try {
            MailHelper::configure();
            Mail::send('emails.reset-password', [
                'user'  => $user,
                'token' => $token,
                'email' => $request->email,
            ], function ($m) use ($user) {
                $m->to($user->email)
                  ->subject('🔐 Reset Password — FSRD UNS Store');
            });
        } catch (\Exception $e) {
            \Log::error('Reset password email gagal: ' . $e->getMessage());
        }

        return back()->with('success', 'Link reset password telah dikirim ke email Anda. Cek inbox atau folder spam.');
    }

    // Form reset password
    public function showResetForm(Request $request, $token)
    {
        return view('buyer.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Proses reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'token'                 => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        // Cek token expired (60 menit)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Link reset password sudah kadaluarsa. Minta link baru.']);
        }

        // Verifikasi token
        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Token tidak valid.']);
        }

        // Update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('buyer.login')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}

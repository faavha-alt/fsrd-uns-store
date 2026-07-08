<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

class MailHelper
{
    public static function configure(): void
    {
        $host = Setting::get('mail_host');
        $username = Setting::get('mail_username');

        if (!$host || !$username) return;

        config([
            'mail.default'                 => 'smtp',
            'mail.mailers.smtp.host'       => $host,
            'mail.mailers.smtp.port'       => Setting::get('mail_port', 587),
            'mail.mailers.smtp.username'   => $username,
            'mail.mailers.smtp.password'   => Setting::get('mail_password'),
            'mail.mailers.smtp.encryption' => Setting::get('mail_encryption', 'tls'),
            'mail.from.address'            => Setting::get('mail_from_address', $username),
            'mail.from.name'               => Setting::get('mail_from_name', 'FSRD UNS Store'),
        ]);
    }

    public static function isEnabled(string $key): bool
    {
        return Setting::get($key, '1') === '1';
    }

    public static function adminEmail(): string
    {
        return Setting::get('notif_admin_email', '');
    }
}

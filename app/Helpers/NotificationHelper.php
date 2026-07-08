<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class NotificationHelper
{
    public static function add(string $type, string $message, string $url = ''): void
    {
        $notifications = Cache::get('admin_notifications', []);
        array_unshift($notifications, [
            'id'      => uniqid(),
            'type'    => $type,
            'message' => $message,
            'url'     => $url,
            'time'    => now()->toDateTimeString(),
            'read'    => false,
        ]);
        Cache::put('admin_notifications', array_slice($notifications, 0, 20), now()->addDays(7));
    }

    public static function getAll(): array
    {
        return Cache::get('admin_notifications', []);
    }

    public static function getUnreadCount(): int
    {
        return count(array_filter(Cache::get('admin_notifications', []), fn($n) => !$n['read']));
    }

    public static function markAllRead(): void
    {
        $notifications = Cache::get('admin_notifications', []);
        foreach ($notifications as &$n) $n['read'] = true;
        Cache::put('admin_notifications', $notifications, now()->addDays(7));
    }
}

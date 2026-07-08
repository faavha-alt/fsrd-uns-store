<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = [];
        $logDir = storage_path('logs');

        // Ambil semua file log admin-activity
        $files = glob($logDir . '/admin-activity-*.log');
        rsort($files); // Urutkan terbaru dulu

        foreach (array_slice($files, 0, 3) as $file) {
            $lines = file($file);
            foreach (array_reverse($lines) as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // Parse format: [2026-07-08 00:41:55] production.INFO: Admin login {"user_id":1,...}
                preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.INFO: (.+?) (\{.+\})/', $line, $matches);

                if (count($matches) >= 4) {
                    $data = json_decode($matches[3], true);
                    $logs[] = [
                        'time'   => $matches[1],
                        'action' => trim($matches[2]),
                        'name'   => $data['name'] ?? '-',
                        'email'  => $data['email'] ?? '-',
                        'ip'     => $data['ip'] ?? '-',
                    ];
                }

                if (count($logs) >= 100) break 2;
            }
        }

        return view('admin.activity-log.index', compact('logs'));
    }
}
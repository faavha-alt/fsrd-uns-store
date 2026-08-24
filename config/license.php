<?php

return [

    'key' => env('LICENSE_KEY'),

    'server_url' => env('LICENSE_SERVER_URL'),

    'api_secret' => env('LICENSE_API_SECRET'),

    // Berapa hari sistem masih boleh jalan normal kalau tidak bisa menghubungi
    // license server (mati internet, server lisensi down, dll) sebelum dikunci.
    'grace_days' => (int) env('LICENSE_GRACE_DAYS', 3),

];

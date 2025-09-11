<?php

return [
    'client_id'     => env('PHONEPE_UAT_CLIENT_ID'),
    'client_secret' => env('PHONEPE_UAT_CLIENT_SECRET'),
    'redirect_url'  => env('PHONEPE_REDIRECT_URL'),
    'env'           => env('PHONEPE_ENV', 'UAT'), // Default sandbox
];

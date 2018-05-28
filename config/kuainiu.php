<?php

return [

    'test_mode' => env('MOLLIE_TEST_MODE', true),
    'keys' => [
        'live' => env('MOLLIE_KEY_LIVE', 'live_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
        'test' => env('MOLLIE_KEY_TEST', 'test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
    ],

    // 复制以下代码到 'config/services.php'
    /*
    'kuainiu' => [
         'domain'       => env('KUAINIU_DOMAIN'),
         'client_id'    => env('KUAINIU_CLIENT_ID'),
         'client_secret'=> env('KUAINIU_CLIENT_SECRET'),
         'redirect'     => env('KUAINIU_REDIRECT_URI'),
     ],
    */
];

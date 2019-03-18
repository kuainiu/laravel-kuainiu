<?php

return [

    'test_mode' => env('MOLLIE_TEST_MODE', true),
    'keys'      => [
        'live' => env('MOLLIE_KEY_LIVE', 'live_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
        'test' => env('MOLLIE_KEY_TEST', 'test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
    ],

    'profileMap' => [ // Optional, but if defined, all must be declared
        'id_field'           => 'id', // The unique identifier for the user
        'name_field'         => 'name',
        'nickname_field'     => 'nickname',
        'chinese_name_field' => 'name',
        'email_field'        => 'email',
        'avatar_field'       => 'avatar',
        'position_field'     => 'position',
        'english_name_field' => 'english_name',
        'departments_field'  => 'departments',
        'certificate_field'  => 'certificate',
    ],

    // 复制以下代码到 'config/services.php'
    /*
    'kuainiu' => [
        'oauthServerDomain' => 'http://passport.kuainiu.io', // OAuth 2.0 Server Domain, Passport Domain
        'client_id'         => 1, // 在 Passport 申请应用的应用 ID，请添加数字，而不是字符串
        'client_secret'     => 'xxxxxx',// 在 Passport 申请应用的应用 secret
        'redirect'          => 'http://your.domain/kuainiu/user/auth?authclient=kuainiu',
    ],
    */
];

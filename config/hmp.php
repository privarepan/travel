<?php

return [
    'account' => 'default',
    'base_uri' => env('HM_GATEWAY_URL'),
    'accounts' => [
        'default' => [
            'app_id' => env('HM_APP_ID'),
            'sub_app_id' => env('HM_SUB_APP_ID',''),
            'plat_rsa_public_key' => env('HM_PLAT_RSA_PUBLIC_KEY'),
            'rsa_private_key' => env('HM_RSA_PRIVATE_KEY'),
        ],
    ],

];

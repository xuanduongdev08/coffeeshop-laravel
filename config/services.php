<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Google OAuth (Socialite)
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI'),
    ],

    // Facebook OAuth (Socialite) — đã xóa, không dùng

    // Gemini AI (CaféAI Chatbox)
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model'   => env('GEMINI_MODEL', 'gemini-2.0-flash'),
    ],

    // OpenWeather (CaféAI)
    'openweather' => [
        'api_key' => env('OPENWEATHER_API_KEY'),
        'city'    => env('SHOP_CITY', 'Ho Chi Minh City'),
    ],

    // VNPay
    'vnpay' => [
        'tmn_code'   => env('VNPAY_TMN_CODE'),
        'hash_secret'=> env('VNPAY_HASH_SECRET'),
        'url'        => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL'),
    ],

    // MoMo
    'momo' => [
        'partner_code' => env('MOMO_PARTNER_CODE'),
        'access_key'   => env('MOMO_ACCESS_KEY'),
        'secret_key'   => env('MOMO_SECRET_KEY'),
        'endpoint'     => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
    ],

    // VietQR
    'vietqr' => [
        'client_id'    => env('VIETQR_CLIENT_ID'),
        'api_key'      => env('VIETQR_API_KEY'),
        'bank_id'      => env('VIETQR_BANK_ID'),
        'account_no'   => env('VIETQR_ACCOUNT_NO'),
        'account_name' => env('VIETQR_ACCOUNT_NAME'),
    ],

    // SePay Webhook
    'sepay' => [
        'api_key' => env('SEPAY_API_KEY'),
    ],

];

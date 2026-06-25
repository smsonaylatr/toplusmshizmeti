<?php

return [

    /*
    |--------------------------------------------------------------------------
    | iyzico API Kimlik Bilgileri
    |--------------------------------------------------------------------------
    | iyzico merchant center'dan alınan API Key ve Secret Key.
    */
    'api_key'    => env('IYZICO_API_KEY', ''),
    'secret_key' => env('IYZICO_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    | Sandbox: https://sandbox.iyzipay.com
    | Canlı:   https://api.iyzipay.com
    */
    'base_url' => env('IYZICO_BASE_URL', 'https://sandbox.iyzipay.com'),

    /*
    |--------------------------------------------------------------------------
    | Para Birimi & KDV
    |--------------------------------------------------------------------------
    */
    'currency' => 'TRY',
    'locale'   => 'tr',
    'vat_rate' => 20,
];

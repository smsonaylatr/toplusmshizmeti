<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PayTR iFrame API Ayarları
    |--------------------------------------------------------------------------
    | PayTR mağaza panelinizden (Destek & Kurulum > Entegrasyon Bilgileri)
    | alınan bilgileri buraya girin veya .env dosyasına ekleyin.
    */

    'merchant_id'   => env('PAYTR_MERCHANT_ID', ''),
    'merchant_key'  => env('PAYTR_MERCHANT_KEY', ''),
    'merchant_salt' => env('PAYTR_MERCHANT_SALT', ''),

    /*
    |--------------------------------------------------------------------------
    | Test Modu
    |--------------------------------------------------------------------------
    | 1 = Test modu (gerçek para çekilmez), 0 = Canlı mod
    */
    'test_mode'  => (int) env('PAYTR_TEST_MODE', 1),

    /*
    |--------------------------------------------------------------------------
    | PayTR Endpoint'leri
    |--------------------------------------------------------------------------
    */
    'iframe_url'  => 'https://www.paytr.com/odeme/api/get-token',
    'iframe_base' => 'https://www.paytr.com/odeme/guvenli/',

    /*
    |--------------------------------------------------------------------------
    | Para Birimi & KDV
    |--------------------------------------------------------------------------
    */
    'currency'  => 'TL',
    'vat_rate'  => 20, // %20 KDV

    /*
    |--------------------------------------------------------------------------
    | Curl Timeout
    |--------------------------------------------------------------------------
    */
    'timeout' => 30,
];

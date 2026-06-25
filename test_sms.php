<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->boot();

$apiId  = \App\Models\SystemSetting::get('vatansms_api_id');
$apiKey = \App\Models\SystemSetting::get('vatansms_api_key');
$sender = \App\Models\SystemSetting::get('vatansms_sender');

echo "API ID : " . $apiId . PHP_EOL;
echo "API KEY: " . (str_repeat('*', max(0, strlen($apiKey)-4)) . substr($apiKey, -4)) . PHP_EOL;
echo "SENDER : " . $sender . PHP_EOL;
echo PHP_EOL;

// Test farklı formatlarda
$testPhone = '5321230234'; // son 4: 0234

$formats = [
    $testPhone,             // 5321230234
    '0' . $testPhone,       // 05321230234
    '+90' . $testPhone,     // +905321230234
    '90' . $testPhone,      // 905321230234
];

foreach ($formats as $phone) {
    $response = \Illuminate\Support\Facades\Http::timeout(10)->post('https://api.vatansms.net/api/v1/1toN', [
        'api_id'               => $apiId,
        'api_key'              => $apiKey,
        'sender'               => $sender,
        'message_type'         => 'normal',
        'message'              => 'Test mesaji - lutfen gormezden gelin',
        'message_content_type' => 'bilgi',
        'phones'               => [$phone],
    ]);
    echo sprintf("[%s] HTTP %d => %s\n", $phone, $response->status(), $response->body());
}

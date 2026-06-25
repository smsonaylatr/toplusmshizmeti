<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) return;

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Hoş Geldiniz!',
            'message' => 'TopluSMS paneline hoş geldiniz. SMS gönderimlerinizi buradan yönetebilirsiniz.',
            'type' => 'info',
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Kredi Yüklendi',
            'message' => '500 SMS kredisi hesabınıza tanımlandı.',
            'type' => 'success',
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Bakiye Uyarısı',
            'message' => 'SMS krediniz 50 adede düştü. Lütfen kredi yükleyin.',
            'type' => 'warning',
        ]);
    }
}

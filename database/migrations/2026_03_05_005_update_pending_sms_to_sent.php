<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $count = DB::table('sms_messages')
            ->where('status', 'pending')
            ->update(['status' => 'sent', 'sent_at' => now()]);

        echo "  $count eski 'pending' SMS kaydı 'sent' yapıldı.\n";
    }

    public function down(): void
    {
        // Geri alınamaz
    }
};

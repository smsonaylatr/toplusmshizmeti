<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // SMS kısa kodu (ör: SAR, FIRMA) - admin tarafından atanır
            $table->string('sms_short_code', 20)->nullable()->after('sms_credits');
            // İptal linki numarası (ör: 4609)
            $table->string('sms_cancel_number', 10)->nullable()->after('sms_short_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sms_short_code', 'sms_cancel_number']);
        });
    }
};

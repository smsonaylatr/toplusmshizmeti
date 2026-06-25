<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // VatanSMS hesap bilgileri (encrypted)
            $table->text('vatansms_api_key')->nullable()->after('whatsapp_connected_at');
            $table->text('vatansms_api_secret')->nullable()->after('vatansms_api_key');
            $table->string('vatansms_sender', 50)->nullable()->after('vatansms_api_secret');
            $table->string('vatansms_account_id', 100)->nullable()->after('vatansms_sender');

            // Evrak onay durumu (hız için cache)
            $table->boolean('document_approved')->default(false)->after('vatansms_account_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'vatansms_api_key',
                'vatansms_api_secret',
                'vatansms_sender',
                'vatansms_account_id',
                'document_approved',
            ]);
        });
    }
};

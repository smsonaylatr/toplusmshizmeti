<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp_api_key')->nullable()->after('whatsapp_credits');
            $table->string('whatsapp_phone_id')->nullable()->after('whatsapp_api_key');
            $table->string('whatsapp_business_id')->nullable()->after('whatsapp_phone_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_api_key', 'whatsapp_phone_id', 'whatsapp_business_id']);
        });
    }
};

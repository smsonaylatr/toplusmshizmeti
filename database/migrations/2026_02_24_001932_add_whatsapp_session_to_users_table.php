<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('whatsapp_session_active')->default(false)->after('whatsapp_business_id');
            $table->string('whatsapp_phone_number')->nullable()->after('whatsapp_session_active');
            $table->string('whatsapp_display_name')->nullable()->after('whatsapp_phone_number');
            $table->timestamp('whatsapp_connected_at')->nullable()->after('whatsapp_display_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_session_active', 'whatsapp_phone_number', 'whatsapp_display_name', 'whatsapp_connected_at']);
        });
    }
};

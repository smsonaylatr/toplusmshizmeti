<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->foreignId('whatsapp_session_id')->nullable()->constrained('whatsapp_sessions')->onDelete('cascade');
            $table->string('send_speed')->default('orta'); // yavas, orta, hizli
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropForeign(['whatsapp_session_id']);
            $table->dropColumn(['whatsapp_session_id', 'send_speed']);
        });
    }
};

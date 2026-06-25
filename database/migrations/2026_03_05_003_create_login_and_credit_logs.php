<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // IP / Giriş logları
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->string('reason')->nullable(); // başarısız giriş sebebi
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });

        // Kredi hareketleri
        Schema::create('credit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['sms', 'whatsapp']);
            $table->enum('action', ['add', 'use', 'refund', 'deduct']); // ekle/kullan/iade/düş
            $table->integer('amount'); // pozitif = ekle/iade, negatif = kullan/düş
            $table->integer('balance_after')->nullable(); // işlem sonrası bakiye
            $table->string('description')->nullable();
            $table->string('reference')->nullable(); // işlem referansı (sms_id, kampanya_id vb)
            $table->timestamps();
            $table->index(['user_id', 'type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_logs');
        Schema::dropIfExists('login_logs');
    }
};

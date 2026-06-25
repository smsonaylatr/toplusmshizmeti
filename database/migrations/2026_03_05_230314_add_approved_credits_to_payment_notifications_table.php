<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) `approved_credits` kolonu ekle
        Schema::table('payment_notifications', function (Blueprint $table) {
            $table->unsignedInteger('approved_credits')->nullable()->after('status');
        });

        // 2) Mevcut enum'u genişlet: 'confirmed' → ['pending','approved','confirmed','rejected']
        //    MariaDB/MySQL için direkt ALTER
        DB::statement("
            ALTER TABLE payment_notifications
            MODIFY COLUMN status ENUM('pending','approved','confirmed','rejected') NOT NULL DEFAULT 'pending'
        ");

        // 3) Eski 'confirmed' değerlerini 'approved' olarak güncelle
        DB::table('payment_notifications')
            ->where('status', 'confirmed')
            ->update(['status' => 'approved']);

        // 4) Sonunda sadece istediğimiz enum değerlerini bırak
        DB::statement("
            ALTER TABLE payment_notifications
            MODIFY COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        Schema::table('payment_notifications', function (Blueprint $table) {
            $table->dropColumn('approved_credits');
        });

        DB::statement("
            ALTER TABLE payment_notifications
            MODIFY COLUMN status ENUM('pending','confirmed','rejected') NOT NULL DEFAULT 'pending'
        ");
    }
};

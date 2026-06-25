<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_risk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('risk_score')->default(0);       // 0-100
            $table->unsignedTinyInteger('spam_score')->default(0);       // 0-100
            $table->unsignedTinyInteger('compliance_score')->default(0); // 0-100
            $table->unsignedTinyInteger('behavior_score')->default(0);   // 0-100
            $table->unsignedInteger('total_flags')->default(0);
            $table->timestamp('last_flag_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_risk_scores');
    }
};

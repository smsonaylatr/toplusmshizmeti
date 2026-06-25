<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blacklisted_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone_number');
            $table->string('reason')->nullable();
            $table->enum('source', ['manual', 'auto_reject'])->default('manual');
            $table->timestamps();

            $table->unique(['user_id', 'phone_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklisted_numbers');
    }
};

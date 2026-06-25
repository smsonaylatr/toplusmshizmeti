<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('virtual_pos_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('package_name');          // "5000 SMS"
            $table->unsignedInteger('sms_amount');   // 5000
            $table->decimal('price', 10, 2);         // 1250.00 (KDV hariç)
            $table->decimal('vat_amount', 10, 2);    // 250.00
            $table->decimal('total_amount', 10, 2);  // 1500.00 (KDV dahil)
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->string('merchant_oid')->unique(); // SMS2_1741234567
            $table->unsignedInteger('paytr_payment_amount'); // 150000 (kuruş)
            $table->string('card_last_four', 4)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('failure_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_pos_orders');
    }
};

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
        Schema::table('virtual_pos_orders', function (Blueprint $table) {
            $table->string('package_type')->default('sms')->after('package_name');
        });
    }

    public function down(): void
    {
        Schema::table('virtual_pos_orders', function (Blueprint $table) {
            $table->dropColumn('package_type');
        });
    }
};

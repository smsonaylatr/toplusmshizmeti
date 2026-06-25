<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('account_type', ['individual', 'corporate'])->default('individual')->after('customer_code');
            $table->string('company_name')->nullable()->after('account_type');
            $table->string('tc_no', 11)->nullable()->after('company_name');
            $table->string('tax_no', 11)->nullable()->after('tc_no');
            $table->string('tax_office')->nullable()->after('tax_no');
            $table->string('mersis_no')->nullable()->after('tax_office');
            $table->string('contact_person')->nullable()->after('mersis_no');   // Yetkili kişi (tüzel için)
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_type', 'company_name', 'tc_no', 'tax_no', 'tax_office', 'mersis_no', 'contact_person']);
        });
    }
};

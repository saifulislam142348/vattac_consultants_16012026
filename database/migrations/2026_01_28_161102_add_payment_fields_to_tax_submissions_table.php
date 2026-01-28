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
        Schema::table('tax_submissions', function (Blueprint $table) {
            $table->decimal('service_charge', 10, 2)->default(0)->after('status');
            $table->enum('payment_status', ['pending', 'paid', 'partial'])->default('pending')->after('service_charge');
            $table->string('payment_method')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_submissions', function (Blueprint $table) {
            $table->dropColumn(['service_charge', 'payment_status', 'payment_method']);
        });
    }
};

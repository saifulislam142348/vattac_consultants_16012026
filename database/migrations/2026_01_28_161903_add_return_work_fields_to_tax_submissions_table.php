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
            $table->string('vat_month')->nullable()->after('payment_method');
            $table->text('return_data')->nullable()->after('vat_month');
            $table->text('calculation_notes')->nullable()->after('return_data');
            $table->date('submission_date')->nullable()->after('calculation_notes');
            $table->string('nbr_ref_no')->nullable()->after('submission_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_submissions', function (Blueprint $table) {
            $table->dropColumn(['vat_month', 'return_data', 'calculation_notes', 'submission_date', 'nbr_ref_no']);
        });
    }
};

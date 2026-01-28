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
            $table->text('admin_reply')->nullable()->after('message');
            $table->boolean('is_phone_verified')->default(false)->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_submissions', function (Blueprint $table) {
            $table->dropColumn(['admin_reply', 'is_phone_verified']);
        });
    }
};

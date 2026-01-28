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
        Schema::create('client_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nid_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('bin_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->text('address')->nullable();
            $table->text('business_address')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_type')->nullable();
            $table->string('vat_circle')->nullable();
            $table->string('vat_zone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_profiles');
    }
};

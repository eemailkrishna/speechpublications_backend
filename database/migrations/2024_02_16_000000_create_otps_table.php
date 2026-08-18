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
        if (!Schema::hasTable('otps')) {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('country_code')->nullable();
            $table->string('otp');
            $table->dateTime('expires_at');
            $table->boolean('is_verified')->default(false);
            $table->integer('attempts')->default(0);
            $table->timestamps();

            // Index for faster lookups
            $table->index('email');
            $table->index('phone_number');
            $table->index('expires_at');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};

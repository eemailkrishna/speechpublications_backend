<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number')->unique();
            $table->string('country_code')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->rememberToken();
            $table->timestamps();

            $table->index('email');
            $table->index('username');
            $table->index('phone_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

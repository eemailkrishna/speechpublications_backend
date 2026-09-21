<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');

        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_one_id');
            $table->unsignedBigInteger('user_two_id');
            $table->text('last_message')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            $table->unique(['user_one_id', 'user_two_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};

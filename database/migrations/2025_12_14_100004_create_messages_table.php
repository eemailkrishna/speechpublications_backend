<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->string('conversation_id');
            $table->longText('content');
            $table->enum('type', ['text', 'image', 'video', 'audio'])->default('text');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('sender_id');
            $table->index('receiver_id');
            $table->index('conversation_id');
            $table->index('is_read');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

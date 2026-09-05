<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->text('comment');
            $table->tinyInteger('rating')->default(5);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_comments');
    }
};

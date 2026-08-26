<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('parent_comment_id')->nullable();
            $table->longText('content');
            $table->integer('likes_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->index('post_id');
            $table->index('user_id');
            $table->index('parent_comment_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

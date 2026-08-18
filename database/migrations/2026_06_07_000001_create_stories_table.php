<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['image','video'])->default('image');
            $table->string('media_url', 500);
            $table->string('thumbnail_url', 500)->nullable();
            $table->text('caption')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stories');
    }
};

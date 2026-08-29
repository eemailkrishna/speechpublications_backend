<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('story_views')) {
        Schema::create('story_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('story_id')->constrained('stories')->onDelete('cascade');
            $table->foreignId('viewer_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();
            $table->unique(['story_id','viewer_id']);
        });
        }
    }

    public function down()
    {
        Schema::dropIfExists('story_views');
    }
};

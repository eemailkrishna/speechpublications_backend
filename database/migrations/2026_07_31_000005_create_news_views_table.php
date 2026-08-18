<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('news_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('news_id')->constrained('news')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('news_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('news_views');
    }
};

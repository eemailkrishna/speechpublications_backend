<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('news_authors')) {
            Schema::create('news_authors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name');
            $table->string('slug')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('profile_image')->nullable();
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->string('language')->nullable();
            $table->string('designation')->nullable();
            $table->text('bio')->nullable();
            $table->string('specialization')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('news_authors');
    }
};

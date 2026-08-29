<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('news')) {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('author_id')->nullable()->constrained('news_authors')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('news_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->timestamp('publish_date')->nullable();
            $table->boolean('featured')->default(false);
            $table->integer('reading_time')->default(0);
            $table->bigInteger('view_count')->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->index('publish_date');
            $table->index(['status', 'publish_date']);
            $table->index('category_id');
            $table->index('author_id');
        });
        }
    }

    public function down()
    {
        Schema::dropIfExists('news');
    }
};

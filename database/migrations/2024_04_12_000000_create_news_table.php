<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('image', 255)->nullable();
            $table->string('title', 255);
            $table->string('description', 255);
            $table->string('writer_name', 100);
            $table->timestamp('publish_date')->useCurrent();
            $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

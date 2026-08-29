<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop tables in correct order due to foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('bookmarks');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('follows');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // No rollback needed
    }
};

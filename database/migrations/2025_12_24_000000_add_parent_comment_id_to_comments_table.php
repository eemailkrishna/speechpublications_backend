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
        if (! Schema::hasColumn('comments', 'parent_comment_id')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_comment_id')->nullable()->after('post_id');
                $table->foreign('parent_comment_id')->references('id')->on('comments')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('comments', 'parent_comment_id')) {
            try {
                Schema::table('comments', function (Blueprint $table) {
                    $table->dropForeign(['parent_comment_id']);
                });
            } catch (\Exception $e) {
                // ignore if foreign key doesn't exist or has a different name
            }

            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('parent_comment_id');
            });
        }
    }
};

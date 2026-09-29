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
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'media_type')) {
                $table->string('media_type')->nullable()->after('media_urls');
            }
            if (! Schema::hasColumn('posts', 'media_types')) {
                $table->json('media_types')->nullable()->after('media_type');
            }
            if (! Schema::hasColumn('posts', 'media_thumbnails')) {
                $table->json('media_thumbnails')->nullable()->after('media_types');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            foreach (['media_types', 'media_thumbnails', 'media_type'] as $column) {
                if (Schema::hasColumn('posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('news', 'is_sitemap')) {
            Schema::table('news', function (Blueprint $table) {
                $table->boolean('is_sitemap')->default(true)->after('is_highlight');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('news', 'is_sitemap')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropColumn('is_sitemap');
            });
        }
    }
};

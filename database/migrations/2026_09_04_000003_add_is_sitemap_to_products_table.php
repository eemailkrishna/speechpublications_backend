<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix invalid updated_at default first (0000-00-00 blocks ALTER TABLE in strict mode)
        if (Schema::hasColumn('products', 'updated_at')) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE products MODIFY updated_at TIMESTAMP NULL DEFAULT NULL");
        }

        if (! Schema::hasColumn('products', 'is_sitemap')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_sitemap')->default(true)->after('is_popular');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'is_sitemap')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_sitemap');
            });
        }
    }
};

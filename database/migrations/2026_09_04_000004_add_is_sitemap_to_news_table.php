<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM news LIKE 'is_sitemap'");
        if (empty($columns)) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE news ADD is_sitemap TINYINT(1) NOT NULL DEFAULT 1 AFTER is_highlight");
        }
    }

    public function down(): void
    {
        $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM news LIKE 'is_sitemap'");
        if (!empty($columns)) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE news DROP COLUMN is_sitemap");
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'is_popular')) {
            // temporarily relax sql_mode to avoid invalid default timestamp errors during ALTER TABLE
            $prev = DB::select("SELECT @@SESSION.sql_mode as mode");
            $prevMode = $prev[0]->mode ?? '';
            DB::statement("SET SESSION sql_mode=''");
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_popular')->default(false);
            });
            // restore previous sql_mode
            DB::statement("SET SESSION sql_mode='{$prevMode}'");
        }
    }

    public function down(): void
    {
        // Avoid dropping columns by default. To allow destructive rollback set ALLOW_DESTRUCTIVE_MIGRATIONS=true
        if (env('ALLOW_DESTRUCTIVE_MIGRATIONS') === 'true' && Schema::hasColumn('products', 'is_popular')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_popular');
            });
        }
    }
};

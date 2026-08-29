<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'is_popular')) {
            $originalModes = DB::select("SELECT @@SESSION.sql_mode as m");
            $orig = $originalModes[0]->m ?? '';
            try {
                DB::statement("SET SESSION sql_mode=''");
                Schema::table('products', function (Blueprint $table) {
                    $table->boolean('is_popular')->default(false)->after('status');
                });
            } finally {
                // restore original sql_mode
                $safe = str_replace("'", "\\'", $orig);
                DB::statement("SET SESSION sql_mode='" . $safe . "'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'is_popular')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_popular');
            });
        }
    }
};

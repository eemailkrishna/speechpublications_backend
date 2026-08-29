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
        if (! Schema::hasColumn('news', 'is_highlight')) {
            Schema::table('news', function (Blueprint $table) {
                $table->boolean('is_highlight')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('news', 'is_highlight')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropColumn('is_highlight');
            });
        }
    }
};

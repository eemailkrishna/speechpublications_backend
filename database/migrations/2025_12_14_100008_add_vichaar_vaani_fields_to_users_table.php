<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add Vichaar Vaani specific fields only if they don't exist
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }
            if (! Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 20)->unique()->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'country_code')) {
                $table->string('country_code', 5)->nullable()->after('phone_number');
            }
            if (! Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('country_code');
            }
            if (! Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('bio');
            }
            if (! Schema::hasColumn('users', 'followers_count')) {
                $table->integer('followers_count')->default(0)->after('profile_photo');
            }
            if (! Schema::hasColumn('users', 'following_count')) {
                $table->integer('following_count')->default(0)->after('followers_count');
            }
            if (! Schema::hasColumn('users', 'posts_count')) {
                $table->integer('posts_count')->default(0)->after('following_count');
            }
            if (! Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('posts_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $drops = [];
            foreach ([
                'username',
                'phone_number',
                'country_code',
                'bio',
                'profile_photo',
                'followers_count',
                'following_count',
                'posts_count',
                'is_verified',
            ] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $drops[] = $col;
                }
            }
            if (! empty($drops)) {
                $table->dropColumn($drops);
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add Vichaar Vaani specific fields
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('phone_number', 20)->unique()->nullable()->after('email');
            $table->string('country_code', 5)->nullable()->after('phone_number');
            $table->text('bio')->nullable()->after('country_code');
            $table->string('profile_photo')->nullable()->after('bio');
            $table->integer('followers_count')->default(0)->after('profile_photo');
            $table->integer('following_count')->default(0)->after('followers_count');
            $table->integer('posts_count')->default(0)->after('following_count');
            $table->boolean('is_verified')->default(false)->after('posts_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'phone_number',
                'country_code',
                'bio',
                'profile_photo',
                'followers_count',
                'following_count',
                'posts_count',
                'is_verified',
            ]);
        });
    }
};

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
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->string('instagram_url')->nullable()->default('https://www.instagram.com/official_mtsn32_jakarta?stkn=bmNjczQ0cGsyaTJt');
            $table->string('tiktok_url')->nullable()->default('https://www.tiktok.com/@mtsn32jkt?_r=1&_t=ZS-9A1zRv1txZe');
            $table->string('youtube_url')->nullable()->default('https://www.youtube.com/@mtsn32jkt?si=SToEPJsg7pJjxySB');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->dropColumn(['instagram_url', 'tiktok_url', 'youtube_url']);
        });
    }
};

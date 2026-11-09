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
            $table->boolean('is_video_profile_active')->default(true)->after('youtube_url');
            $table->string('video_profile_badge')->default('JELAJAHI SEKOLAH')->after('is_video_profile_active');
            $table->string('video_profile_location')->default('Pesanggrahan, Jaksel')->after('video_profile_badge');
            $table->string('video_profile_title')->default('KEHIDUPAN SEKOLAH & SISWA')->after('video_profile_location');
            $table->string('video_profile_subtitle')->default('Sistem Informasi Terpadu')->after('video_profile_title');
            $table->string('video_profile_link_text')->default('Lihat Fasilitas →')->after('video_profile_subtitle');
            $table->string('video_profile_link_url')->default('/profil/sarana-prasarana')->after('video_profile_link_text');
            $table->string('video_profile_url')->nullable()->default('https://www.youtube.com/watch?v=dQw4w9WgXcQ')->after('video_profile_link_url');
            $table->string('video_profile_cover')->nullable()->after('video_profile_url');
            $table->text('video_profile_description')->nullable()->after('video_profile_cover');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'is_video_profile_active',
                'video_profile_badge',
                'video_profile_location',
                'video_profile_title',
                'video_profile_subtitle',
                'video_profile_link_text',
                'video_profile_link_url',
                'video_profile_url',
                'video_profile_cover',
                'video_profile_description',
            ]);
        });
    }
};

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
            $table->boolean('is_teachers_active')->default(true)->after('headmaster_welcome');
            $table->boolean('is_ppdb_active')->default(true)->after('is_teachers_active');
            $table->string('ppdb_academic_year')->nullable()->default('2026/2027')->after('is_ppdb_active');
            $table->text('ppdb_status_note')->nullable()->after('ppdb_academic_year');
            $table->string('ppdb_external_url')->nullable()->default('https://ppdb-madrasahdki.kemenag.go.id')->after('ppdb_status_note');
            $table->string('ppdb_guide_url')->nullable()->after('ppdb_external_url');
            $table->string('ppdb_whatsapp_helpdesk')->nullable()->after('ppdb_guide_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'is_teachers_active',
                'is_ppdb_active',
                'ppdb_academic_year',
                'ppdb_status_note',
                'ppdb_external_url',
                'ppdb_guide_url',
                'ppdb_whatsapp_helpdesk',
            ]);
        });
    }
};

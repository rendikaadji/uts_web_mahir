<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('admin_level', ['utama', 'biasa'])->nullable()->default(null)->after('role');
        });

        // Set existing admin_madrasah to 'utama'
        DB::table('users')->where('role', 'admin_madrasah')->whereNull('admin_level')->update(['admin_level' => 'utama']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin_level');
        });
    }
};

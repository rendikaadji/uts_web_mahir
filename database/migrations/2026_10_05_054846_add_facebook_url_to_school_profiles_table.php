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
            if (! Schema::hasColumn('school_profiles', 'facebook_url')) {
                $table->string('facebook_url', 500)
                    ->nullable()
                    ->default('https://www.facebook.com/MTsN32?rdid=sYBd4mjz784GCLt3&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F1D9gaWjQTm%2F#')
                    ->after('maps_embed_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('school_profiles', 'facebook_url')) {
                $table->dropColumn('facebook_url');
            }
        });
    }
};

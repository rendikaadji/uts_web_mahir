<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->foreignId('qr_token_id')->nullable()->after('member_id')->constrained('qr_tokens')->nullOnDelete();
            $table->enum('method', ['qr', 'manual'])->default('manual')->after('purpose');
            $table->foreignId('recorded_by')->nullable()->after('method')->constrained('users')->nullOnDelete();
            $table->string('device_note')->nullable()->after('recorded_by');
            $table->string('guest_phone')->nullable()->after('guest_institution');
            $table->index('visited_at');
        });
    }

    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign(['qr_token_id']);
            $table->dropForeign(['recorded_by']);
            $table->dropIndex(['visited_at']);
            $table->dropColumn(['qr_token_id', 'method', 'recorded_by', 'device_note', 'guest_phone']);
        });
    }
};

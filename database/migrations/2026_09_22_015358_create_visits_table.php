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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_type');
            $table->unsignedBigInteger('visitor_id');
            $table->foreignId('qr_token_id')->nullable()->constrained('qr_tokens')->nullOnDelete();
            $table->timestamp('checked_in_at');
            $table->enum('method', ['qr', 'manual'])->default('qr');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('device_note')->nullable();
            $table->timestamps();

            $table->index('checked_in_at');
            $table->index(['visitor_type', 'visitor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};

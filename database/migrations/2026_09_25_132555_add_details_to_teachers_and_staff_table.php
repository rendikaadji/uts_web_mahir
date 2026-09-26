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
        Schema::table('teachers_and_staff', function (Blueprint $table) {
            $table->string('active_since')->nullable()->after('position_or_subject'); // Aktif mulai
            $table->string('employment_status')->nullable()->default('PNS')->after('active_since'); // Status (PNS, PPPK, Honorer, dll.)
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable()->after('employment_status'); // Jenis Kelamin
            $table->string('birth_place')->nullable()->after('gender'); // Tempat Lahir
            $table->date('birth_date')->nullable()->after('birth_place'); // Tanggal Lahir
            $table->string('religion')->nullable()->default('Islam')->after('birth_date'); // Agama
            $table->string('nik')->nullable()->after('religion'); // NIK
            $table->string('nuptk')->nullable()->after('nip'); // NUPTK
            $table->text('address')->nullable()->after('education'); // Alamat
            $table->string('phone')->nullable()->after('address'); // Kontak
            $table->string('email')->nullable()->after('phone'); // Email
            $table->text('bio')->nullable()->after('email'); // Motto / Profil Singkat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers_and_staff', function (Blueprint $table) {
            $table->dropColumn([
                'active_since',
                'employment_status',
                'gender',
                'birth_place',
                'birth_date',
                'religion',
                'nik',
                'nuptk',
                'address',
                'phone',
                'email',
                'bio',
            ]);
        });
    }
};

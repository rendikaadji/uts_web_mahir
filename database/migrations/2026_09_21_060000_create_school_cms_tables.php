<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->default('MTsN 32 Jakarta');
            $table->string('npsn')->default('20178264');
            $table->string('accreditation')->default('A (Unggul)');

            // Pimpinan Madrasah (Bisa diganti dinamis oleh Admin)
            $table->string('headmaster_name')->default('H. Fahrurozi, M.Pd.');
            $table->string('headmaster_nip')->nullable()->default('197005121997031002');
            $table->string('headmaster_photo')->nullable();
            $table->text('headmaster_welcome')->nullable();

            // Susunan Wakil Kepala Madrasah & Koordinator (dengan Foto)
            $table->string('vice_curriculum')->nullable()->default('Dra. Hj. Siti Rohmah, M.Pd.');
            $table->string('vice_curriculum_photo')->nullable();
            $table->string('vice_student_affairs')->nullable()->default('Ahmad Syarifuddin, S.Pd.I.');
            $table->string('vice_student_affairs_photo')->nullable();
            $table->string('vice_facilities')->nullable()->default('Mochamad Ridwan, S.T.');
            $table->string('vice_facilities_photo')->nullable();
            $table->string('vice_public_relations')->nullable()->default('Nurul Hidayati, M.Si.');
            $table->string('vice_public_relations_photo')->nullable();
            $table->string('head_administration')->nullable()->default('Bambang Sutrisno, S.Sos.');
            $table->string('head_administration_photo')->nullable();
            $table->string('cambridge_coordinator')->nullable()->default('Rizki Ramadhan, M.Ed.');
            $table->string('cambridge_coordinator_photo')->nullable();
            $table->string('head_library')->nullable()->default('Siti Nurbaeti, S.Hum.');
            $table->string('head_library_photo')->nullable();

            // Informasi Kontak & Lokasi
            $table->string('phone')->nullable()->default('(021) 5851234');
            $table->string('whatsapp')->nullable()->default('081298765432');
            $table->string('email')->nullable()->default('info@mtsn32jkt.sch.id');
            $table->text('address')->nullable()->default('Jl. H. Muchtar Raya No. 32, RT.005/RW.01, Petukangan Utara, Kec. Pesanggrahan, Kota Jakarta Selatan, DKI Jakarta 12260');
            $table->text('maps_embed_url')->nullable();

            // Informasi Kurikulum Cambridge
            $table->text('cambridge_description')->nullable();
            $table->json('cambridge_subjects')->nullable();

            $table->timestamps();
        });

        Schema::create('teachers_and_staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nip')->nullable();
            $table->enum('role_type', ['guru', 'staf'])->default('guru');
            $table->string('position_or_subject'); // e.g. Guru Matematika Cambridge, Guru Fiqih, Staf TU
            $table->string('education')->nullable(); // S1 Pendidikan Matematika UNJ, dsb.
            $table->string('photo')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Robotik & AI, Tahfidz, Pramuka
            $table->enum('category', ['akademik', 'olahraga', 'seni_budaya', 'keagamaan', 'kepanduan'])->default('akademik');
            $table->string('mentor_name')->nullable();
            $table->string('schedule_day')->nullable(); // e.g. Sabtu, 08.00 - 10.30 WIB
            $table->string('icon_or_photo')->nullable();
            $table->text('description')->nullable();
            $table->text('achievements')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Laboratorium IPA, Masjid Baitul Ilmi
            $table->enum('category', ['akademik', 'olahraga', 'penunjang', 'ibadah'])->default('akademik');
            $table->text('description')->nullable();
            $table->string('capacity')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('kegiatan');
            $table->date('event_date')->nullable();
            $table->string('photo_url')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('extracurriculars');
        Schema::dropIfExists('teachers_and_staff');
        Schema::dropIfExists('school_profiles');
    }
};

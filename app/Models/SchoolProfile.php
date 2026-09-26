<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    protected $fillable = [
        'school_name',
        'npsn',
        'accreditation',
        'headmaster_name',
        'headmaster_nip',
        'headmaster_photo',
        'headmaster_welcome',
        'vice_curriculum',
        'vice_curriculum_photo',
        'vice_student_affairs',
        'vice_student_affairs_photo',
        'vice_facilities',
        'vice_facilities_photo',
        'vice_public_relations',
        'vice_public_relations_photo',
        'head_administration',
        'head_administration_photo',
        'cambridge_coordinator',
        'cambridge_coordinator_photo',
        'head_library',
        'head_library_photo',
        'phone',
        'whatsapp',
        'email',
        'address',
        'maps_embed_url',
        'instagram_url',
        'tiktok_url',
        'youtube_url',
        'cambridge_description',
        'cambridge_subjects',
    ];

    protected function casts(): array
    {
        return [
            'cambridge_subjects' => 'array',
        ];
    }

    public static function getActiveProfile(): self
    {
        return static::firstOrCreate([], [
            'school_name' => 'MTsN 32 Jakarta',
            'npsn' => '20178264',
            'accreditation' => 'A (Unggul)',
            'headmaster_name' => 'H. Fahrurozi, M.Pd.',
            'headmaster_nip' => '197005121997031002',
            'headmaster_welcome' => 'Assalamu\'alaikum Wr. Wb. Selamat datang di portal resmi MTsN 32 Jakarta. Kami bertekad mewujudkan madrasah maju, bermutu, dan berdaya saing global melalui perpaduan Kurikulum Nasional, Program Internasional Cambridge, serta nilai-nilai luhur Al-Qur\'an.',
            'vice_curriculum' => 'Dra. Hj. Siti Rohmah, M.Pd.',
            'vice_student_affairs' => 'Ahmad Syarifuddin, S.Pd.I.',
            'vice_facilities' => 'Mochamad Ridwan, S.T.',
            'vice_public_relations' => 'Nurul Hidayati, M.Si.',
            'head_administration' => 'Bambang Sutrisno, S.Sos.',
            'cambridge_coordinator' => 'Rizki Ramadhan, M.Ed.',
            'head_library' => 'Siti Nurbaeti, S.Hum.',
            'phone' => '(021) 58906414',
            'whatsapp' => '081298765432',
            'email' => 'info@mtsn32jkt.sch.id',
            'address' => 'Jl. H. Liun, RT.13/RW.11, Petukangan Utara, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12260',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.195328224534!2d106.7571343749905!3d-6.237970893750244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f0e137c35f0f%3A0x6280b2a8d672957e!2sMTsN%2032%20Jakarta!5e0!3m2!1sid!2sid!4v1711000000000!5m2!1sid!2sid',
            'instagram_url' => 'https://www.instagram.com/official_mtsn32_jakarta?stkn=bmNjczQ0cGsyaTJt',
            'tiktok_url' => 'https://www.tiktok.com/@mtsn32jkt?_r=1&_t=ZS-9A1zRv1txZe',
            'youtube_url' => 'https://www.youtube.com/@mtsn32jkt?si=SToEPJsg7pJjxySB',
            'cambridge_description' => 'Program Pengayaan Kurikulum Cambridge di MTsN 32 Jakarta dirancang untuk membekali peserta didik dengan standar literasi, numerasi, dan sains bertaraf global (Cambridge Lower Secondary Checkpoint) yang diperkaya dengan akhlakul karimah dan tahfidz Al-Qur\'an.',
            'cambridge_subjects' => [
                'English as a Second Language (Checkpoint 0876)',
                'Mathematics (Checkpoint 0862)',
                'Science (Biology, Chemistry, Physics - Checkpoint 0893)',
                'Global Perspectives & Project-Based Inquiry',
                'Tahfidzul Qur\'an & Islamic Studies Integration',
            ],
        ]);
    }
}

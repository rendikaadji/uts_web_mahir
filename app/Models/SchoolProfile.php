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
        'headmaster_motto',
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
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'youtube_url',
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
        'cambridge_description',
        'cambridge_subjects',
        'graduation_rate',
        'national_achievements',
        'is_teachers_active',
        'is_ppdb_active',
        'ppdb_academic_year',
        'ppdb_status_note',
        'ppdb_external_url',
        'ppdb_guide_url',
        'ppdb_whatsapp_helpdesk',
    ];

    protected function casts(): array
    {
        return [
            'cambridge_subjects' => 'array',
            'is_teachers_active' => 'boolean',
            'is_ppdb_active' => 'boolean',
            'is_video_profile_active' => 'boolean',
        ];
    }

    public function getVideoCoverUrlAttribute(): string
    {
        if ($this->video_profile_cover) {
            return str_starts_with($this->video_profile_cover, 'http')
                ? $this->video_profile_cover
                : asset('storage/'.$this->video_profile_cover);
        }

        return asset('images/campus-life.jpg');
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (! $this->video_profile_url) {
            return null;
        }

        $url = trim($this->video_profile_url);

        // Pattern YouTube: watch?v=ID, youtu.be/ID, embed/ID, shorts/ID
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/'.$matches[1].'?autoplay=1&rel=0&modestbranding=1';
        }

        return str_starts_with($url, 'http') ? $url : asset('storage/'.$url);
    }

    public function getIsYoutubeVideoAttribute(): bool
    {
        if (! $this->video_profile_url) {
            return false;
        }

        return (bool) preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)/', $this->video_profile_url);
    }

    protected $attributes = [
        'is_teachers_active' => true,
        'is_ppdb_active' => true,
        'ppdb_academic_year' => '2026/2027',
        'ppdb_external_url' => 'https://ppdb-madrasahdki.kemenag.go.id',
        'facebook_url' => 'https://www.facebook.com/MTsN32?rdid=sYBd4mjz784GCLt3&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F1D9gaWjQTm%2F#',
    ];

    public static function getActiveProfile(): self
    {
        return static::firstOrCreate([], [
            'is_teachers_active' => true,
            'is_ppdb_active' => true,
            'ppdb_academic_year' => '2026/2027',
            'ppdb_external_url' => 'https://ppdb-madrasahdki.kemenag.go.id',
            'facebook_url' => 'https://www.facebook.com/MTsN32?rdid=sYBd4mjz784GCLt3&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F1D9gaWjQTm%2F#',
            'school_name' => 'MTsN 32 Jakarta',
            'npsn' => '20178264',
            'accreditation' => 'A (Unggul)',
            'headmaster_name' => 'H. Fahrurozi, M.Pd.',
            'headmaster_nip' => '197005121997031002',
            'headmaster_welcome' => 'Assalamu\'alaikum Wr. Wb. Selamat datang di portal resmi MTsN 32 Jakarta. Kami bertekad mewujudkan madrasah maju, bermutu, dan berdaya saing global melalui perpaduan Kurikulum Nasional, Program Internasional Cambridge, serta nilai-nilai luhur Al-Qur\'an.',
            'headmaster_motto' => 'Maju Bermutu, Mendunia Bersama MTsN 32 Jakarta',
            'vice_curriculum' => 'Dra. Hj. Siti Rohmah, M.Pd.',
            'vice_student_affairs' => 'Ahmad Syarifuddin, S.Pd.I.',
            'vice_facilities' => 'Mochamad Ridwan, S.T.',
            'vice_public_relations' => 'Nurul Hidayati, M.Si.',
            'head_administration' => 'Bambang Sutrisno, S.Sos.',
            'cambridge_coordinator' => 'Rizki Ramadhan, M.Ed.',
            'head_library' => 'Siti Nurbaeti, S.Hum.',
            'phone' => '(021) 58906414',
            'whatsapp' => '+62 857-1560-2930',
            'email' => 'info@mtsn32jkt.sch.id',
            'address' => 'Jl. H. Liun, RT.13/RW.11, Petukangan Utara, Kec. Pesanggrahan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12260',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1983.0!2d106.7408207!3d-6.2245871!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f0b3f964b499%3A0x97d10ea1980633b!2sMTsN%2032%20Jakarta%20Selatan!5e0!3m2!1sid!2sid!4v1790000000000!5m2!1sid!2sid',
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

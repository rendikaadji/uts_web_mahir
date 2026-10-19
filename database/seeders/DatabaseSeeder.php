<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Fine;
use App\Models\Gallery;
use App\Models\GuardianStudent;
use App\Models\Loan;
use App\Models\Member;
use App\Models\SchoolProfile;
use App\Models\TeacherStaff;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Profil & Pimpinan Madrasah
        SchoolProfile::truncate();
        $profile = SchoolProfile::create([
            'school_name' => 'MTsN 32 Jakarta',
            'npsn' => '20178264',
            'accreditation' => 'A (Unggul) - BAN-S/M',
            'headmaster_name' => 'H. Fahrurozi, M.Pd.',
            'headmaster_nip' => '197005121997031002',
            'headmaster_welcome' => "Bismillahirrohmanirrohim. Assalamu'alaikum Warahmatullahi Wabarakatuh.\n\nSelamat datang di portal resmi MTs Negeri 32 Jakarta. Kami berikhtiar sungguh-sungguh untuk mendidik generasi muslim yang berakhlak mulia, cerdas, berwawasan global, dan mandiri. Melalui sinergi Kurikulum Merdeka Nasional dan Program Internasional Cambridge, serta pembinaan Tahfidzul Qur'an, MTsN 32 Jakarta berkomitmen menjadi madrasah maju, bermutu, dan mendunia. Semoga website informasi dan perpustakaan digital ini memberikan kemaslahatan bagi civitas akademika dan masyarakat luas.\n\nWassalamu'alaikum Warahmatullahi Wabarakatuh.",
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
            'cambridge_description' => 'Program Pengayaan Cambridge Lower Secondary di MTsN 32 Jakarta dirancang untuk mengintegrasikan keunggulan akademik internasional pada mata pelajaran English, Mathematics, dan Science dengan nilai-nilai luhur Islam dan penguatan karakter kebangsaan.',
            'cambridge_subjects' => [
                'Cambridge Lower Secondary English as a Second Language',
                'Cambridge Lower Secondary Mathematics',
                'Cambridge Lower Secondary Science (Physics, Chemistry, Biology)',
                'Global Perspectives & Project-Based Learning',
                'Integrasi Tahfidzul Qur\'an & Kajian Keislaman',
            ],
        ]);

        // 2. Akun Pengguna & Anggota
        // Admin Madrasah (Password: admin123)
        $admin = User::create([
            'name' => 'H. Fahrurozi, M.Pd.',
            'username' => 'admin',
            'email' => 'admin@mtsn32jkt.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);

        // Pustakawan
        $pustakawan = User::create([
            'name' => 'Siti Nurbaeti, S.Hum.',
            'username' => 'pustakawan',
            'email' => 'perpustakaan@mtsn32jkt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        // Siswa 1
        $siswaUser1 = User::create([
            'name' => 'Muhammad Raihan Pratama',
            'username' => 'siswa',
            'email' => 'raihan@siswa.mtsn32jkt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $siswaMember1 = Member::create([
            'user_id' => $siswaUser1->id,
            'member_type' => 'siswa',
            'nisn' => '0081234567',
            'barcode' => 'MTS32-S-001',
            'phone' => '081234567890',
            'class_or_unit' => 'Kelas 8-A (Cambridge Class)',
            'is_active' => true,
        ]);

        // Siswa 2
        $siswaUser2 = User::create([
            'name' => 'Aisyah Putri Rahmadani',
            'username' => 'siswa2',
            'email' => 'aisyah@siswa.mtsn32jkt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $siswaMember2 = Member::create([
            'user_id' => $siswaUser2->id,
            'member_type' => 'siswa',
            'nisn' => '0087654321',
            'barcode' => 'MTS32-S-002',
            'phone' => '081298765432',
            'class_or_unit' => 'Kelas 7-B (Bilingual)',
            'is_active' => true,
        ]);

        // Guru
        $guruUser = User::create([
            'name' => 'Rizki Ramadhan, M.Ed.',
            'username' => 'guru',
            'email' => 'rizki.ramadhan@guru.mtsn32jkt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $guruMember = Member::create([
            'user_id' => $guruUser->id,
            'member_type' => 'guru',
            'nip' => '198501012010011001',
            'barcode' => '198501012010011001',
            'phone' => '081312345678',
            'class_or_unit' => null,
            'is_active' => true,
        ]);

        // Wali Murid (Format Opsi A: wali_[NISN])
        $waliUser = User::create([
            'name' => 'Bambang Suryo, S.T. (Wali Raihan & Aisyah)',
            'username' => 'wali_0081234567',
            'email' => 'wali_0081234567@wali.mtsn32.sch.id',
            'password' => Hash::make('password'),
            'role' => 'wali_murid',
            'is_active' => true,
        ]);

        GuardianStudent::create([
            'guardian_user_id' => $waliUser->id,
            'student_member_id' => $siswaMember1->id,
        ]);

        GuardianStudent::create([
            'guardian_user_id' => $waliUser->id,
            'student_member_id' => $siswaMember2->id,
        ]);

        // 3. Dewan Guru & Tenaga Kependidikan
        TeacherStaff::truncate();
        $teachersData = [
            [
                'name' => 'H. Fahrurozi, M.Pd.',
                'nip' => '197005121997031002',
                'nuptk' => '4138748650200012',
                'nik' => '3174051205700001',
                'role_type' => 'guru',
                'position_or_subject' => 'Kepala Madrasah / PAI',
                'active_since' => '01 Juli 1997',
                'employment_status' => 'PNS',
                'gender' => 'Laki-laki',
                'birth_place' => 'Jakarta',
                'birth_date' => '1970-05-12',
                'religion' => 'Islam',
                'education' => 'S2 Manajemen Pendidikan UIN Syarif Hidayatullah Jakarta',
                'address' => 'Kec. Pesanggrahan, Kota Jakarta Selatan, DKI Jakarta',
                'email' => 'fahrurozi@mtsn32jkt.sch.id',
                'phone' => '081298761001',
                'bio' => 'Membangun generasi cerdas berakhlakul karimah, berwawasan global, dan berprestasi unggul demi kemajuan bangsa.',
            ],
            [
                'name' => 'Dra. Hj. Siti Rohmah, M.Pd.',
                'nip' => '196803151994032001',
                'nuptk' => '3845746648300021',
                'nik' => '3174065503680002',
                'role_type' => 'guru',
                'position_or_subject' => 'Wakamad Kurikulum / Bahasa Indonesia',
                'active_since' => '01 Maret 1994',
                'employment_status' => 'PNS',
                'gender' => 'Perempuan',
                'birth_place' => 'Bandung',
                'birth_date' => '1968-03-15',
                'religion' => 'Islam',
                'education' => 'S2 Pendidikan Bahasa UNJ',
                'address' => 'Kebayoran Lama, Kota Jakarta Selatan, DKI Jakarta',
                'email' => 'siti.rohmah@mtsn32jkt.sch.id',
                'phone' => '081298761002',
                'bio' => 'Bahasa adalah cermin budi pekerti dan kunci membuka peradaban ilmu pengetahuan dunia.',
            ],
            [
                'name' => 'Ahmad Syarifuddin, S.Pd.I.',
                'nip' => '197508202005011003',
                'nuptk' => '5247753655200033',
                'nik' => '3174072008750003',
                'role_type' => 'guru',
                'position_or_subject' => 'Wakamad Kesiswaan / Fiqih',
                'active_since' => '01 Januari 2005',
                'employment_status' => 'PNS',
                'gender' => 'Laki-laki',
                'birth_place' => 'Cirebon',
                'birth_date' => '1975-08-20',
                'religion' => 'Islam',
                'education' => 'S1 Tarbiyah & Dirasat Islamiyah',
                'address' => 'Petukangan Utara, Pesanggrahan, Jakarta Selatan',
                'email' => 'ahmad.syarif@mtsn32jkt.sch.id',
                'phone' => '081298761003',
                'bio' => 'Menuntun santri berdisiplin tinggi, berkarakter santun, dan taat beribadah dalam bingkai syariat Islam.',
            ],
            [
                'name' => 'Mochamad Ridwan, S.T.',
                'nip' => '198004102008011005',
                'nuptk' => '6438758660200044',
                'nik' => '3174081004800004',
                'role_type' => 'guru',
                'position_or_subject' => 'Wakamad Sarpras / Informatika & Robotik',
                'active_since' => '01 Januari 2008',
                'employment_status' => 'PNS',
                'gender' => 'Laki-laki',
                'birth_place' => 'Jakarta',
                'birth_date' => '1980-04-10',
                'religion' => 'Islam',
                'education' => 'S1 Teknik Elektro / Informatika Univ. Trisakti',
                'address' => 'Ciledug, Kota Tangerang, Banten',
                'email' => 'ridwan.it@mtsn32jkt.sch.id',
                'phone' => '081298761004',
                'bio' => 'Mengembangkan nalar komputasional dan rekayasa teknologi madrasah menyongsong era kecerdasan buatan.',
            ],
            [
                'name' => 'Nurul Hidayati, M.Si.',
                'nip' => '197809122003122002',
                'nuptk' => '7154756658300055',
                'nik' => '3174095209780005',
                'role_type' => 'guru',
                'position_or_subject' => 'Wakamad Humas / IPA Fisika',
                'active_since' => '01 Desember 2003',
                'employment_status' => 'PNS',
                'gender' => 'Perempuan',
                'birth_place' => 'Bogor',
                'birth_date' => '1978-09-12',
                'religion' => 'Islam',
                'education' => 'S2 Fisika Terapan Institut Pertanian Bogor (IPB)',
                'address' => 'Bintaro Jaya, Tangerang Selatan',
                'email' => 'nurul.hidayati@mtsn32jkt.sch.id',
                'phone' => '081298761005',
                'bio' => 'Menjadikan sains sebagai sarana mentadabburi kebesaran Allah SWT melalui hukum-hukum alam semesta.',
            ],
            [
                'name' => 'Rizki Ramadhan, M.Ed.',
                'nip' => '198501012010011001',
                'nuptk' => '8342763664200066',
                'nik' => '3174100101850006',
                'role_type' => 'guru',
                'position_or_subject' => 'Koordinator Cambridge / Cambridge Mathematics',
                'active_since' => '01 Januari 2010',
                'employment_status' => 'PNS',
                'gender' => 'Laki-laki',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '1985-01-01',
                'religion' => 'Islam',
                'education' => 'Master of Education - Monash University, Australia',
                'address' => 'Kebayoran Baru, Jakarta Selatan',
                'email' => 'rizki.ramadhan@mtsn32jkt.sch.id',
                'phone' => '081298761006',
                'bio' => 'Empowering future leaders through logical mathematical reasoning and global bilingual competencies.',
            ],
            [
                'name' => 'Sarah Az-Zahra, B.Ed.',
                'nip' => '199204152019032014',
                'nuptk' => '9456770671300077',
                'nik' => '3174115504920007',
                'role_type' => 'guru',
                'position_or_subject' => 'Cambridge English as a Second Language',
                'active_since' => '01 Maret 2019',
                'employment_status' => 'PNS',
                'gender' => 'Perempuan',
                'birth_place' => 'Jakarta',
                'birth_date' => '1992-04-15',
                'religion' => 'Islam',
                'education' => 'S1 Pendidikan Bahasa Inggris Universitas Negeri Jakarta',
                'address' => 'Pesanggrahan, Jakarta Selatan',
                'email' => 'sarah.azzahra@mtsn32jkt.sch.id',
                'phone' => '081298761007',
                'bio' => 'Inspiring young learners to express ideas fluently, critically, and ethically across borders.',
            ],
            [
                'name' => 'Dr. H. Muhyiddin, M.A.',
                'nip' => '197302141999031004',
                'nuptk' => '2539751653200088',
                'nik' => '3174121402730008',
                'role_type' => 'guru',
                'position_or_subject' => 'Kajian Keislaman & Tahfidzul Qur\'an',
                'active_since' => '01 Maret 1999',
                'employment_status' => 'PNS',
                'gender' => 'Laki-laki',
                'birth_place' => 'Kudus',
                'birth_date' => '1973-02-14',
                'religion' => 'Islam',
                'education' => 'S3 Studi Islam UIN Syarif Hidayatullah Jakarta',
                'address' => 'Ciputat Timur, Tangerang Selatan',
                'email' => 'muhyiddin@mtsn32jkt.sch.id',
                'phone' => '081298761008',
                'bio' => 'Al-Qur\'an di dada, akhlak mulia dalam laku, dan ilmu bermanfaat penuntun kehidupan.',
            ],
            [
                'name' => 'Anjar Himawan Prasetyo, S.Pd.',
                'nip' => '198708142012011003',
                'nuptk' => '5941765666130099',
                'nik' => '3174131408870009',
                'role_type' => 'guru',
                'position_or_subject' => 'Guru Kelas & Pembina Pramuka',
                'active_since' => '01 Januari 2012',
                'employment_status' => 'PNS',
                'gender' => 'Laki-laki',
                'birth_place' => 'Surakarta',
                'birth_date' => '1987-08-14',
                'religion' => 'Islam',
                'education' => 'S1 Pendidikan Sejarah & IPS Universitas Sebelas Maret',
                'address' => 'Larangan Indah, Kota Tangerang',
                'email' => 'anjar.himawan@mtsn32jkt.sch.id',
                'phone' => '081298761009',
                'bio' => 'Mendidik dengan keteladanan, membimbing dengan keikhlasan, menginspirasi dengan karya nyata.',
            ],
            [
                'name' => 'Hendra Wijaya, S.Pd.',
                'nip' => '198906222014031002',
                'nuptk' => '1643767668200101',
                'nik' => '3174142206890010',
                'role_type' => 'guru',
                'position_or_subject' => 'Cambridge Science (Biology & Chemistry)',
                'active_since' => '01 Maret 2014',
                'employment_status' => 'PNS',
                'gender' => 'Laki-laki',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '1989-06-22',
                'religion' => 'Islam',
                'education' => 'S1 Pendidikan Biologi Universitas Negeri Yogyakarta',
                'address' => 'Pondok Aren, Tangerang Selatan',
                'email' => 'hendra.wijaya@mtsn32jkt.sch.id',
                'phone' => '081298761010',
                'bio' => 'Eksplorasi ilmiah membangkitkan rasa ingin tahu dan kekaguman atas keagungan ciptaan Ilahi.',
            ],
            [
                'name' => 'Dewi Anggraeni, S.Pd.',
                'nip' => '198407182009122003',
                'nuptk' => '3749762664300112',
                'nik' => '3174155807840011',
                'role_type' => 'guru',
                'position_or_subject' => 'Matematika Nasional & Riset Madrasah',
                'active_since' => '01 Desember 2009',
                'employment_status' => 'PNS',
                'gender' => 'Perempuan',
                'birth_place' => 'Semarang',
                'birth_date' => '1984-07-18',
                'religion' => 'Islam',
                'education' => 'S1 Matematika Universitas Indonesia',
                'address' => 'Cilandak, Jakarta Selatan',
                'email' => 'dewi.anggraeni@mtsn32jkt.sch.id',
                'phone' => '081298761011',
                'bio' => 'Menumbuhkan budaya riset, inovasi terapan, dan kecintaan terhadap keindahan logika matematika.',
            ],
            [
                'name' => 'Tri Prasetyo, S.Or.',
                'nip' => '199011052015041001',
                'nuptk' => '4851768670100123',
                'nik' => '3174160511900012',
                'role_type' => 'guru',
                'position_or_subject' => 'PJOK & Pembina Ekstrakurikuler Olahraga',
                'active_since' => '01 April 2015',
                'employment_status' => 'PNS',
                'gender' => 'Laki-laki',
                'birth_place' => 'Jakarta',
                'birth_date' => '1990-11-05',
                'religion' => 'Islam',
                'education' => 'S1 Ilmu Keolahragaan Universitas Negeri Jakarta',
                'address' => 'Pesanggrahan, Jakarta Selatan',
                'email' => 'tri.prasetyo@mtsn32jkt.sch.id',
                'phone' => '081298761012',
                'bio' => 'Mens sana in corpore sano — tubuh yang bugar melahirkan jiwa yang tangguh dan berprestasi.',
            ],
            [
                'name' => 'Siti Nurbaeti, S.Hum.',
                'nip' => '199308092020122018',
                'nuptk' => '6942771673300134',
                'nik' => '3174174908930013',
                'role_type' => 'staf',
                'position_or_subject' => 'Kepala Perpustakaan Digital & Literasi',
                'active_since' => '01 Desember 2020',
                'employment_status' => 'PPPK',
                'gender' => 'Perempuan',
                'birth_place' => 'Jakarta',
                'birth_date' => '1993-08-09',
                'religion' => 'Islam',
                'education' => 'S1 Ilmu Perpustakaan & Informasi Universitas Indonesia',
                'address' => 'Kebayoran Lama, Jakarta Selatan',
                'email' => 'perpustakaan@mtsn32jkt.sch.id',
                'phone' => '081298761013',
                'bio' => 'Literasi adalah gerbang kecakapan abad ke-21 yang membuka cakrawala dunia.',
            ],
            [
                'name' => 'Bambang Sutrisno, S.Sos.',
                'nip' => '197112011998031001',
                'nuptk' => '8034749651100145',
                'nik' => '3174180112710014',
                'role_type' => 'staf',
                'position_or_subject' => 'Kepala Urusan Tata Usaha (Ka. TU)',
                'active_since' => '01 Maret 1998',
                'employment_status' => 'PNS',
                'gender' => 'Laki-laki',
                'birth_place' => 'Solo',
                'birth_date' => '1971-12-01',
                'religion' => 'Islam',
                'education' => 'S1 Administrasi Publik Universitas Diponegoro',
                'address' => 'Petukangan Selatan, Pesanggrahan, Jakarta Selatan',
                'email' => 'bambang.tu@mtsn32jkt.sch.id',
                'phone' => '081298761014',
                'bio' => 'Pelayanan tata usaha prima, akuntabel, dan transparan untuk kenyamanan seluruh sivitas akademika.',
            ],
        ];

        foreach ($teachersData as $idx => $t) {
            TeacherStaff::create(array_merge($t, ['sort_order' => $idx + 1, 'is_active' => true]));
        }

        // 4. Ekstrakurikuler
        $ekskulData = [
            [
                'name' => 'Robotik & Kecerdasan Buatan (AI)',
                'category' => 'akademik',
                'mentor_name' => 'Mochamad Ridwan, S.T. & Tim Ahli',
                'schedule_day' => 'Sabtu, 08.00 - 11.00 WIB',
                'description' => 'Mempelajari dasar mekanik, coding Arduino/Microbit, IoT, dan algoritma kecerdasan buatan untuk perlombaan robotik nasional.',
                'achievements' => 'Juara 1 Madrasah Robotic Competition (MRC) Kemenag RI Tingkat Nasional.',
            ],
            [
                'name' => 'Tahfidz Al-Qur\'an & Tilawah',
                'category' => 'keagamaan',
                'mentor_name' => 'Dr. H. Muhyiddin, M.A.',
                'schedule_day' => 'Senin s/d Kamis, 06.15 - 07.00 WIB',
                'description' => 'Bimbingan intensif hafalan Al-Qur\'an juz 30, juz 1-5, tajwid makharijul huruf, serta seni baca Al-Qur\'an (tilawah/qari).',
                'achievements' => 'Juara 1 Musabaqah Hifdzil Qur\'an (MHQ) Pelajar Tingkat DKI Jakarta.',
            ],
            [
                'name' => 'Pramuka Penggalang (Gudep 04-123)',
                'category' => 'kepanduan',
                'mentor_name' => 'Kak Tri Prasetyo & Pembina Kwarran',
                'schedule_day' => 'Jumat, 14.00 - 16.30 WIB',
                'description' => 'Pendidikan kepanduan berbasis karakter, kemandirian, survival alam terbuka, tali temali, semapore, dan kepemimpinan pemuda.',
                'achievements' => 'Regu Berprestasi Tinggi Lomba Tingkat III (LT-III) Kwartir Cabang Jakarta Selatan.',
            ],
            [
                'name' => 'PMR Madya (Palang Merah Remaja)',
                'category' => 'kepanduan',
                'mentor_name' => 'Nurul Hidayati, M.Si. & PMI Jaksel',
                'schedule_day' => 'Sabtu, 09.00 - 11.30 WIB',
                'description' => 'Pelatihan pertolongan pertama (PP), evakuasi korban, kesehatan remaja, dan kesiapsiagaan bencana bekerjasama dengan PMI.',
                'achievements' => 'Juara Umum Jumbara PMR Madya Tingkat Kota Jakarta Selatan.',
            ],
            [
                'name' => 'Paskibra Samudra 32',
                'category' => 'kepanduan',
                'mentor_name' => 'Ahmad Syarifuddin, S.Pd.I.',
                'schedule_day' => 'Rabu & Sabtu, 15.30 - 17.30 WIB',
                'description' => 'Pembinaan kedisiplinan, baris berbaris formasi indah, tata upacara bendera, dan ketahanan mental fisik.',
                'achievements' => 'Juara 1 Lomba Ketangkasan Baris Berbaris (LKBB) Se-Jabodetabek.',
            ],
            [
                'name' => 'Cambridge English & Debating Club',
                'category' => 'akademik',
                'mentor_name' => 'Sarah Az-Zahra, B.Ed.',
                'schedule_day' => 'Kamis, 15.00 - 16.30 WIB',
                'description' => 'Asah keterampilan berbicara, debat bahasa Inggris formal model parlemen Asia/Inggris, public speaking, dan penulisan esai kritis.',
                'achievements' => 'Juara 2 National Junior High English Debate Championship.',
            ],
            [
                'name' => 'Karya Ilmiah Remaja (KIR) & Riset Sains',
                'category' => 'akademik',
                'mentor_name' => 'Dewi Anggraeni, S.Pd.',
                'schedule_day' => 'Selasa, 15.00 - 16.30 WIB',
                'description' => 'Metodologi penelitian sains alam, teknologi terapan, dan ilmu sosial humaniora untuk kompetisi ilmiah remaja.',
                'achievements' => 'Medali Emas Madrasah Young Researchers Supercamp (MYRES) Kemenag RI.',
            ],
            [
                'name' => 'Marawis & Hadroh Shalawat',
                'category' => 'seni_budaya',
                'mentor_name' => 'Ustadz Zulkifli, S.Ud.',
                'schedule_day' => 'Jumat, 15.30 - 17.30 WIB',
                'description' => 'Pelestarian seni musik Islami perkusi marawis, hadroh terbang, dan lantunan shalawat pembuka acara resmi madrasah.',
                'achievements' => 'Juara 1 Festival Seni Musik Islam Tingkat Pelajar Provinsi DKI Jakarta.',
            ],
            [
                'name' => 'Futsal & Sepak Bola Garuda 32',
                'category' => 'olahraga',
                'mentor_name' => 'Coach Tri Prasetyo, S.Or.',
                'schedule_day' => 'Selasa & Sabtu, 15.30 - 17.30 WIB',
                'description' => 'Latihan teknik dasar, passing, dribbling, strategi taktik pertandingan futsal, dan pembinaan fisik atlet madrasah.',
                'achievements' => 'Juara 1 Liga Futsal Madrasah Tsanawiyah Se-DKI Jakarta.',
            ],
            [
                'name' => 'Basket Ball Club 32',
                'category' => 'olahraga',
                'mentor_name' => 'Coach Dimas Ramadhan',
                'schedule_day' => 'Senin & Kamis, 15.30 - 17.30 WIB',
                'description' => 'Pembinaan tim basket putra dan putri MTsN 32 Jakarta untuk turnamen antarpelajar SMP/MTs.',
                'achievements' => 'Juara 2 Turnamen Bola Basket Pelajar Cup Jakarta Selatan.',
            ],
        ];

        foreach ($ekskulData as $ekskul) {
            Extracurricular::create(array_merge($ekskul, ['is_active' => true]));
        }

        // 5. Sarana & Prasarana
        Facility::truncate();
        $facilitiesData = [
            [
                'name' => 'Masjid Baitul Ilmi MTsN 32',
                'category' => 'ibadah',
                'capacity' => '600 Jamaah',
                'photo' => '/images/program-tahfidz.jpg',
                'description' => 'Masjid Baitul Ilmi merupakan pusat episentrum pembinaan ruhiyah dan spiritual civitas akademika MTsN 32 Jakarta. Dilengkapi dengan karpet sajadah tebal berkualitas tinggi, pendingin ruangan (AC), sound system akustik modern, serta tempat wudhu terpisah ikhwan dan akhwat yang higienis. Fasilitas ini menjadi sarana utama pelaksanaan sholat Dhuha berjamaah setiap pagi, sholat Dzuhur dan Ashar berjamaah, halaqah tahfidz Al-Qur\'an intensif, kajian tematik kitab kuning, serta peringatan hari besar Islam (PHBI).',
            ],
            [
                'name' => 'Laboratorium IPA & Sains Cambridge',
                'category' => 'akademik',
                'capacity' => '40 Siswa',
                'photo' => '/images/program-science.jpg',
                'description' => 'Laboratorium terintegrasi berstandar internasional yang mendukung kurikulum nasional dan pengayaan Cambridge Science (Biology, Chemistry, Physics). Dilengkapi mikroskop digital stereo terhubung display proyektor, lemari asam pengaman uap kimia, set alat peraga fisika optik dan mekanika, model anatomi biologi presisi, stasiun cuci mata darurat (emergency eyewash), serta perlengkapan APD keselamatan kerja standar riset madrasah.',
            ],
            [
                'name' => 'Laboratorium Komputer & CBT Center',
                'category' => 'akademik',
                'capacity' => '45 PC Core i5 Modern',
                'photo' => '/images/program-cambridge.jpg',
                'description' => 'Pusat teknologi informasi dan asesmen terpusat dengan 45 unit workstation komputer berperforma tinggi (Intel Core i5, RAM 16GB, SSD NVMe), server CBT dedicated dengan sistem failover, catu daya UPS sentral, koneksi serat optik berkecepatan 1 Gbps, serta sistem pendingin suhu konstan. Digunakan untuk pelaksanaan ANBK, Asesmen Madrasah, Cambridge Checkpoint Test, olimpiade daring, pembelajaran coding/robotika, serta workshop multimedia kreatif.',
            ],
            [
                'name' => 'Perpustakaan Digital Terpadu',
                'category' => 'akademik',
                'capacity' => '50 Pemustaka',
                'photo' => '/images/program-library.jpg',
                'description' => 'Perpustakaan berbasis otomasi sistem SLIMS dengan anjungan pencarian mandiri OPAC (Online Public Access Catalog) dan sistem absensi RFID. Menyediakan lebih dari 8.500 judul buku fisik, ensiklopedia referensi, modul kurikulum merdeka, ribuan e-book interaktif, kubikel riset privat, lounge santai pojok baca karpet bulu, serta jaringan Wi-Fi riset edukasi yang nyaman dan tenang.',
            ],
            [
                'name' => 'Lapangan Olahraga & Upacara Serbaguna',
                'category' => 'olahraga',
                'capacity' => '1.000 Siswa',
                'photo' => '/images/program-extracurricular.jpg',
                'description' => 'Area terbuka multifungsi dengan pelapis lantai khusus anti-slip dan garis marka standar federasi olahraga nasional. Mendukung cabang olahraga futsal, bola basket, bola voli, bulutangkis, senam kesegaran jasmani, latihan Paskibra, latihan Pramuka, serta pelaksanaan upacara bendera hari Senin dan apel peringatan hari nasional seluruh santri MTsN 32 Jakarta.',
            ],
            [
                'name' => 'Ruang Pelayanan Terpadu Satu Pintu (PTSP)',
                'category' => 'penunjang',
                'capacity' => 'Area Resepsionis Nyaman',
                'photo' => '/images/slider-banner-1.jpg',
                'description' => 'Pusat layanan administrasi modern berkonsep satu pintu yang mengedepankan prinsip pelayanan prima, cepat, dan transparan sesuai standar Kemenag RI. Dilengkapi ruang tunggu berpendingin udara, sofa tamu representatif, display informasi digital, anjungan antrean mandiri, fasilitas air minum gratis, serta petugas front-office ramah yang melayani legalisir ijazah, surat rekomendasi, mutasi siswa, dan pusat pengaduan.',
            ],
            [
                'name' => 'Ruang UKS & Konseling Kesehatan',
                'category' => 'penunjang',
                'capacity' => '4 Tempat Tidur Pasien',
                'photo' => '/images/campus-life.jpg',
                'description' => 'Fasilitas pertolongan pertama pada kecelakaan dan kesehatan sekolah yang bekerjasama aktif dengan Puskesmas Kecamatan Pesanggrahan. Menyediakan 4 unit ranjang medis bersekat privasi, tabung oksigen regulator, tensimeter digital, timbangan dan alat ukur antropometri, kotak obat P3K komprehensif, ruang dokter/konseling kesehatan, serta ruang istirahat yang higienis.',
            ],
            [
                'name' => 'Smart Classroom Ber-AC & Multimedia',
                'category' => 'akademik',
                'capacity' => '32 Siswa per Kelas',
                'photo' => '/images/campus-hero.jpg',
                'description' => 'Ruang belajar generasi baru yang dirancang untuk mendukung pembelajaran aktif, kolaboratif, dan interaktif. Setiap kelas dilengkapi interactive smart board/proyektor short-throw, koneksi Wi-Fi high-speed per kelas, pendingin ruangan ganda berfilter udara bersih, loker penyimpanan buku pribadi siswa, meja kursi ergonomis yang fleksibel untuk diskusi kelompok, serta pencahayaan alami optimal.',
            ],
        ];

        foreach ($facilitiesData as $facility) {
            Facility::create(array_merge($facility, ['is_active' => true]));
        }

        // 6. Galeri Dokumentasi Foto Kegiatan
        $galleriesData = [
            [
                'title' => 'Peluncuran Pengayaan Kurikulum Cambridge Lower Secondary',
                'category' => 'akademik',
                'photo_url' => '/images/program-cambridge.jpg',
                'event_date' => now()->subDays(15),
                'description' => 'Pembukaan program kemitraan internasional kurikulum Cambridge terintegrasi nilai keislaman bersama Kemenag dan Komite.',
            ],
            [
                'title' => 'Apel Akbar & Peringatan Hari Santri Nasional di MTsN 32',
                'category' => 'keagamaan',
                'photo_url' => '/images/program-tahfidz.jpg',
                'event_date' => now()->subMonths(2),
                'description' => 'Seluruh dewan guru dan peserta didik mengenakan busana muslim dan sarung dalam apel kebangsaan yang khidmat.',
            ],
            [
                'title' => 'Kunjungan Literasi dan Workshop Membaca Cepat di Perpustakaan',
                'category' => 'literasi',
                'photo_url' => '/images/program-library.jpg',
                'event_date' => now()->subDays(5),
                'description' => 'Kegiatan literasi bergilir siswa kelas VII dalam memanfaatkan sistem pencarian buku OPAC dan resensi bacaan digital.',
            ],
            [
                'title' => 'Penyerahan Medali Juara Kompetisi Sains Madrasah (KSM)',
                'category' => 'prestasi',
                'photo_url' => '/images/program-science.jpg',
                'event_date' => now()->subMonths(1),
                'description' => 'Kepala MTsN 32 Jakarta menyematkan medali penghargaan kepada siswa peraih prestasi riset dan olimpiade sains.',
            ],
            [
                'title' => 'Wisuda Tahfidzul Qur\'an Angkatan Ke-VI',
                'category' => 'keagamaan',
                'photo_url' => '/images/campus-life.jpg',
                'event_date' => now()->subMonths(3),
                'description' => 'Uji publik dan tasyakuran kelulusan hafalan 3 Juz Al-Qur\'an bagi santri madrasah kelas IX dihadiri para wali murid.',
            ],
            [
                'title' => 'Simulasi Tanggap Darurat & Aksi Kemanusiaan PMR Madya',
                'category' => 'kegiatan',
                'photo_url' => '/images/program-extracurricular.jpg',
                'event_date' => now()->subDays(20),
                'description' => 'Latihan pertolongan pertama dan mitigasi bencana gempa bumi di lingkungan sekolah bersama Palang Merah Indonesia.',
            ],
        ];

        foreach ($galleriesData as $gal) {
            Gallery::create($gal);
        }

        // 7. Kategori Buku Perpustakaan
        $categories = [
            ['name' => 'Buku Pelajaran & Kurikulum', 'slug' => 'buku-pelajaran', 'description' => 'Buku teks Kurikulum Merdeka dan Pengayaan Cambridge MTs.'],
            ['name' => 'Keagamaan & Studi Islam', 'slug' => 'keagamaan', 'description' => 'Al-Qur\'an Hadis, Fiqih, Akidah Akhlak, SKI, dan Bahasa Arab.'],
            ['name' => 'Sains & Teknologi', 'slug' => 'sains-teknologi', 'description' => 'Fisika, Biologi, Kimia, Robotik, Komputer, dan Astronomi.'],
            ['name' => 'Bahasa & Sastra', 'slug' => 'bahasa-sastra', 'description' => 'Novel inspiratif, antologi cerpen, puisi, dan kamus multilingual.'],
            ['name' => 'Referensi & Ensiklopedia', 'slug' => 'referensi', 'description' => 'Ensiklopedia sains Islam, kamus besar, dan atlas dunia.'],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = Category::create($cat);
        }

        // 8. Koleksi Buku & Eksemplar
        $booksData = [
            [
                'category_id' => $catModels['buku-pelajaran']->id,
                'title' => 'Cambridge Lower Secondary Mathematics Learner\'s Book 8',
                'slug' => 'cambridge-lower-secondary-mathematics-8',
                'author' => 'Lynn Byrd, Greg Byrd & Chris Pearce',
                'publisher' => 'Cambridge University Press',
                'publication_year' => 2024,
                'isbn' => '978-1108746342',
                'description' => 'Buku teks resmi kurikulum Cambridge Checkpoint Matematika mencakup aljabar, geometri, statistika dan pemecahan masalah tingkat menengah.',
                'cover_image' => '/images/books/cambridge-lower-secondary-mathematics-8.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/cambridge-lower-secondary-mathematics-8.pdf',
                'copies_count' => 5,
            ],
            [
                'category_id' => $catModels['buku-pelajaran']->id,
                'title' => 'Cambridge Checkpoint Science Coursebook 8',
                'slug' => 'cambridge-checkpoint-science-8',
                'author' => 'Mary Jones, Diane Fellowes-Freeman & Michael Smyth',
                'publisher' => 'Cambridge University Press',
                'publication_year' => 2023,
                'isbn' => '978-1108742818',
                'description' => 'Materi sains terpadu fisika, biologi dan kimia dengan pendekatan inkuiri ilmiah dan percobaan laboratorium.',
                'cover_image' => '/images/books/cambridge-checkpoint-science-8.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/cambridge-checkpoint-science-8.pdf',
                'copies_count' => 4,
            ],
            [
                'category_id' => $catModels['keagamaan']->id,
                'title' => 'Fiqih Ibadah & Muamalah Madrasah Tsanawiyah Kelas VIII',
                'slug' => 'fiqih-ibadah-muamalah-mts-viii',
                'author' => 'Drs. H. Ahmad Syukri, M.A.',
                'publisher' => 'Direktorat KSKK Madrasah Kemenag RI',
                'publication_year' => 2024,
                'isbn' => '978-623-6901-44-1',
                'description' => 'Panduan komprehensif hukum Islam praktis mengenai shalat fardhu dan sunnah, sujud sahwi, zakat, puasa, dan etika bermasyarakat.',
                'cover_image' => '/images/books/fiqih-ibadah-muamalah-mts-viii.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/fiqih-ibadah-muamalah-mts-viii.pdf',
                'copies_count' => 6,
            ],
            [
                'category_id' => $catModels['keagamaan']->id,
                'title' => 'Tafsir Ringkas Al-Qur\'an Juz \'Amma untuk Remaja Muslim',
                'slug' => 'tafsir-ringkas-juz-amma-remaja',
                'author' => 'Prof. Dr. Quraish Shihab',
                'publisher' => 'Lentera Hati Publishing',
                'publication_year' => 2022,
                'isbn' => '978-602-8740-89-0',
                'description' => 'Penjelasan mendalam surah-surah pendek dalam Al-Qur\'an dengan gaya bahasa santun yang mudah dipahami generasi muda.',
                'cover_image' => '/images/books/tafsir-ringkas-juz-amma-remaja.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/tafsir-ringkas-juz-amma-remaja.pdf',
                'copies_count' => 3,
            ],
            [
                'category_id' => $catModels['sains-teknologi']->id,
                'title' => 'Pemrograman Robotik dan IoT Menggunakan Arduino & ESP32',
                'slug' => 'pemrograman-robotik-dan-iot-arduino',
                'author' => 'Ir. Mochamad Ridwan & Tim Riset',
                'publisher' => 'Informatika Press',
                'publication_year' => 2025,
                'isbn' => '978-623-7131-02-3',
                'description' => 'Tutorial langkah demi langkah merakit robot cerdas, sensor jarak ultrasonik, navigasi line-follower, dan kontrol melalui smartphone.',
                'cover_image' => '/images/books/pemrograman-robotik-dan-iot-arduino.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/pemrograman-robotik-dan-iot-arduino.pdf',
                'copies_count' => 4,
            ],
            [
                'category_id' => $catModels['bahasa-sastra']->id,
                'title' => 'Negeri 5 Menara: Meneguhkan Niat Man Jadda Wajada',
                'slug' => 'negeri-5-menara-man-jadda-wajada',
                'author' => 'Ahmad Fuadi',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2021,
                'isbn' => '978-979-22-4861-6',
                'description' => 'Kisah inspiratif santri menuntut ilmu di pesantren dengan tekad meraih impian mendunia di bawah naungan kalimat man jadda wajada.',
                'cover_image' => '/images/books/negeri-5-menara-man-jadda-wajada.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/negeri-5-menara-man-jadda-wajada.pdf',
                'copies_count' => 3,
            ],
            [
                'category_id' => $catModels['referensi']->id,
                'title' => 'Ensiklopedia Peradaban Islam Abad Keemasan (The Golden Age)',
                'slug' => 'ensiklopedia-peradaban-islam-golden-age',
                'author' => 'Dr. Raghib As-Sirjani',
                'publisher' => 'Pustaka Al-Kautsar',
                'publication_year' => 2023,
                'isbn' => '978-979-592-748-8',
                'description' => 'Rekam jejak ilmuwan muslim legendaris seperti Ibnu Sina, Al-Khawarizmi, Al-Biruni, dan kontribusi sains Islam bagi dunia peradaban.',
                'cover_image' => '/images/books/ensiklopedia-peradaban-islam-golden-age.jpg',
                'is_digital_available' => true,
                'digital_file_path' => '/documents/ebooks/ensiklopedia-peradaban-islam-golden-age.pdf',
                'copies_count' => 2,
            ],
        ];

        $bookModels = [];
        $copiesCollection = [];

        foreach ($booksData as $bookItem) {
            $copiesCount = $bookItem['copies_count'];
            unset($bookItem['copies_count']);

            $book = Book::create($bookItem);
            $bookModels[] = $book;

            for ($i = 1; $i <= $copiesCount; $i++) {
                $copyCode = sprintf('C-%02d', $i);
                $barcode = sprintf('BK-%03d-%02d', $book->id, $i);

                $copy = BookCopy::create([
                    'book_id' => $book->id,
                    'barcode' => $barcode,
                    'copy_code' => $copyCode,
                    'status' => 'tersedia',
                ]);

                $copiesCollection[] = $copy;
            }
        }

        // 9. Sirkulasi Peminjaman & Denda
        // Loan 1: Aktif dan tepat waktu
        $copyLoan1 = $copiesCollection[0];
        $loan1 = Loan::create([
            'book_copy_id' => $copyLoan1->id,
            'member_id' => $siswaMember1->id,
            'loan_date' => today()->subDays(3),
            'due_date' => today()->addDays(4),
            'status' => 'aktif',
            'notes' => 'Tugas proyek Cambridge Mathematics',
        ]);
        $copyLoan1->update(['status' => 'dipinjam']);

        // Loan 2: Terlambat
        $copyLoan2 = $copiesCollection[5];
        $loan2 = Loan::create([
            'book_copy_id' => $copyLoan2->id,
            'member_id' => $siswaMember1->id,
            'loan_date' => today()->subDays(12),
            'due_date' => today()->subDays(5),
            'status' => 'aktif',
            'notes' => 'Buku pendamping kajian Fiqih',
        ]);
        $copyLoan2->update(['status' => 'dipinjam']);

        // Loan 3: Selesai dengan denda lunas
        $copyLoan3 = $copiesCollection[10];
        $loan3 = Loan::create([
            'book_copy_id' => $copyLoan3->id,
            'member_id' => $siswaMember2->id,
            'loan_date' => today()->subDays(25),
            'due_date' => today()->subDays(18),
            'return_date' => today()->subDays(16),
            'status' => 'terlambat',
            'notes' => 'Dikembalikan terlambat 2 hari',
        ]);
        Fine::create([
            'loan_id' => $loan3->id,
            'days_overdue' => 2,
            'amount' => 2000,
            'payment_status' => 'lunas',
            'paid_at' => today()->subDays(16),
        ]);

        // Loan 4: Pinjaman Guru
        $copyLoan4 = $copiesCollection[8];
        Loan::create([
            'book_copy_id' => $copyLoan4->id,
            'member_id' => $guruMember->id,
            'loan_date' => today()->subDays(2),
            'due_date' => today()->addDays(12),
            'status' => 'aktif',
            'notes' => 'Referensi bahan modul robotik madrasah',
        ]);
        $copyLoan4->update(['status' => 'dipinjam']);

        // 10. Presensi Kiosk Masuk (/gate)
        Visit::create([
            'member_id' => $siswaMember1->id,
            'visitor_type' => 'anggota',
            'purpose' => 'Mengerjakan tugas Cambridge Math di ruang baca',
            'visited_at' => now()->subHours(2),
        ]);

        Visit::create([
            'member_id' => $guruMember->id,
            'visitor_type' => 'anggota',
            'purpose' => 'Meminjam buku referensi silabus IPA',
            'visited_at' => now()->subHours(1),
        ]);

        Visit::create([
            'visitor_type' => 'tamu',
            'guest_name' => 'Drs. H. Mulyadi, M.Pd.',
            'guest_institution' => 'Seksi Penmad Kemenag Kota Jakarta Selatan',
            'purpose' => 'Monitoring & Evaluasi Program Madrasah Unggulan',
            'visited_at' => now()->subMinutes(30),
        ]);

        // 11. Publikasi Warta, Berita & Pengumuman
        $articles = [
            [
                'author_id' => $admin->id,
                'title' => 'Penerimaan Peserta Didik Baru (PPDB) & Seleksi Kelas Pengayaan Cambridge MTsN 32 Jakarta TP 2026/2027',
                'slug' => 'ppdb-seleksi-kelas-cambridge-mtsn32-2026-2027',
                'category' => 'pengumuman',
                'image' => '/images/program-cambridge.jpg',
                'summary' => 'Informasi lengkap jadwal pendaftaran PPDB jalur madrasah unggulan, tahfidz, dan seleksi placement test program Cambridge Lower Secondary di MTsN 32 Jakarta.',
                'content' => "<p>Madrasah Tsanawiyah Negeri (MTsN) 32 Jakarta secara resmi membuka sosialisasi Penerimaan Peserta Didik Baru (PPDB) Tahun Pelajaran 2026/2027. Sebagai salah satu Madrasah Unggulan Akademik Nasional berdasarkan SK Dirjen Pendidikan Islam, MTsN 32 Jakarta membuka beberapa jalur penerimaan:</p><ul><li>Jalur Prestasi Akademik & Non-Akademik</li><li>Jalur Tahfidz Al-Qur'an (Minimal 2 Juz)</li><li>Jalur Reguler Zonasi Madrasah</li><li>Seleksi Program Pengayaan Kurikulum Cambridge (Checkpoint English, Math, and Science)</li></ul><p>Calon peserta didik dan wali murid dapat mengunduh berkas petunjuk teknis di laman resmi atau datang langsung ke loket Pelayanan Terpadu Satu Pintu (PTSP) madrasah pada jam kerja.</p>",
                'is_featured' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'author_id' => $admin->id,
                'title' => 'Tim Robotik MTsN 32 Jakarta Raih Juara 1 Nasional Madrasah Robotic Competition',
                'slug' => 'tim-robotik-mtsn-32-raih-juara-1-nasional',
                'category' => 'berita',
                'image' => '/images/program-science.jpg',
                'summary' => 'Prestasi membanggakan kembali dipersembahkan oleh tim robotik dan inovasi sains madrasah pada ajang bergengsi tingkat nasional di Yogyakarta.',
                'content' => '<p>Alhamdulillah, puji syukur ke hadirat Allah SWT. Tim Robotik MTsN 32 Jakarta berhasil menorehkan prestasi gemilang dengan meraih <strong>Juara 1 Kategori Mobile Robot IoT</strong> pada ajang Madrasah Robotic Competition (MRC) Nasional.</p><p>Kepala MTsN 32 Jakarta, H. Fahrurozi, M.Pd., menyampaikan apresiasi yang setinggi-tingginya kepada para siswa pembina, guru pembimbing, dan orang tua atas kerja keras serta dukungan tanpa henti. Prestasi ini membuktikan bahwa siswa madrasah mampu berdiri sejajar dan memimpin dalam penguasaan teknologi sains masa depan berlandaskan akhlakul karimah.</p>',
                'is_featured' => true,
                'published_at' => now()->subDays(4),
            ],
            [
                'author_id' => $admin->id,
                'title' => 'Jadwal Asesmen Sumatif & Pelaksanaan Cambridge Checkpoint Simulation 2026',
                'slug' => 'jadwal-asesmen-cambridge-checkpoint-simulation-2026',
                'category' => 'agenda',
                'image' => '/images/slider-guru-fokus.jpg',
                'summary' => 'Kalender kegiatan pelaksanaan simulasi ujian Cambridge Checkpoint serta Asesmen Madrasah Semester Genap tahun ajaran 2026.',
                'content' => '<p>Diberitahukan kepada seluruh peserta didik kelas VII, VIII, dan IX serta bapak/ibu wali murid, bahwa rangkaian agenda Asesmen Semester Genap dan Simulasi Cambridge Checkpoint akan dilaksanakan pada:</p><ul><li><strong>Simulasi Cambridge Checkpoint:</strong> 13 - 17 April 2026 di CBT Center</li><li><strong>Asesmen Madrasah Kelas IX:</strong> 20 - 27 April 2026</li><li><strong>Asesmen Sumatif Akhir Tahun (ASAT) Kelas VII & VIII:</strong> 18 - 26 Mei 2026</li></ul><p>Diharapkan siswa menjaga kesehatan dan mempersiapkan diri dengan optimal dengan memanfaatkan koleksi buku di perpustakaan digital madrasah.</p>',
                'is_featured' => false,
                'published_at' => now()->subDays(7),
            ],
        ];

        foreach ($articles as $art) {
            Article::create($art);
        }
    }
}

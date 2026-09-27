<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}

<?php

namespace Database\Seeders;

use App\Models\GuestVisit;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Madrasah
        $admin = User::firstOrCreate([
            'username' => 'admin',
        ], [
            'name' => 'Administrator MTsN 32',
            'email' => 'admin@mtsn32.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);
        $admin->getOrCreateQrToken();

        // 2. Pustakawan
        $pustakawan = User::firstOrCreate([
            'username' => 'pustakawan',
        ], [
            'name' => 'Hj. Siti Pustakawati, S.Pd',
            'email' => 'perpus@mtsn32.sch.id',
            'password' => Hash::make('password'),
            'role' => 'pustakawan',
            'is_active' => true,
        ]);
        $pustakawan->getOrCreateQrToken();

        // 3. Siswa
        $siswa = User::firstOrCreate([
            'username' => 'siswa',
        ], [
            'name' => 'Muhammad Fatih Al-Ayyubi',
            'email' => 'fatih@siswa.mtsn32.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);
        $siswaToken = $siswa->getOrCreateQrToken();

        // 4. Sample initial visits
        Visit::firstOrCreate([
            'visitor_type' => User::class,
            'visitor_id' => $siswa->id,
            'qr_token_id' => $siswaToken->id,
        ], [
            'checked_in_at' => now()->subHours(2),
            'method' => 'qr',
            'device_note' => 'Kiosk Gerbang Pintu 1',
        ]);

        $guest = GuestVisit::firstOrCreate([
            'name' => 'Drs. Ahmad Fauzi',
        ], [
            'origin' => 'Kemenag Wilayah Jakarta Selatan',
            'purpose' => 'Kunjungan Kerja dan Akreditasi',
            'phone' => '081298765432',
        ]);

        Visit::firstOrCreate([
            'visitor_type' => GuestVisit::class,
            'visitor_id' => $guest->id,
        ], [
            'checked_in_at' => now()->subHour(),
            'method' => 'manual',
            'recorded_by' => $pustakawan->id,
            'device_note' => 'Presensi Tamu VIP',
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Member;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin_madrasah',
            'is_active' => true,
        ]);
    }

    private function librarian(): User
    {
        return User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);
    }

    private function studentUser(): User
    {
        return User::factory()->create([
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_activity_logs_page(): void
    {
        ActivityLog::record(
            logType: 'kunjungan',
            eventName: 'Presensi Tamu',
            description: 'Drs. H. Mulyadi, M.Pd. mengisi buku tamu perpustakaan',
            actorName: 'Drs. H. Mulyadi, M.Pd.',
            actorType: 'tamu',
            actorIdentifier: 'Kemenag DKI'
        );

        $response = $this->actingAs($this->admin())
            ->get(route('admin.logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Log Aktivitas Frontend');
        $response->assertSee('Drs. H. Mulyadi, M.Pd.');
        $response->assertSee('Presensi Tamu');
    }

    public function test_librarian_can_access_activity_logs_page(): void
    {
        ActivityLog::record(
            logType: 'sirkulasi',
            eventName: 'Sirkulasi Buku',
            description: 'Muhammad Raihan Pratama meminjam Cambridge Checkpoint Science Coursebook 8',
            actorName: 'Muhammad Raihan Pratama',
            actorType: 'siswa',
            actorIdentifier: 'MBR-1001'
        );

        $response = $this->actingAs($this->librarian())
            ->get(route('admin.logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Log Aktivitas Frontend');
        $response->assertSee('Muhammad Raihan Pratama');
        $response->assertSee('Sirkulasi Buku');
    }

    public function test_unauthorized_user_cannot_access_logs(): void
    {
        // Siswa biasa tidak boleh mengakses
        $response = $this->actingAs($this->studentUser())
            ->get(route('admin.logs.index'));

        $response->assertStatus(403);

        // Guest dialihkan ke login
        auth()->logout();
        $guestResponse = $this->get(route('admin.logs.index'));
        $guestResponse->assertRedirect(route('login'));
    }

    public function test_logs_can_be_filtered_by_type(): void
    {
        ActivityLog::record(
            logType: 'sirkulasi',
            eventName: 'Sirkulasi Buku',
            description: 'Aisyah Putri Rahmadani meminjam Fiqih Ibadah',
            actorName: 'Aisyah Putri Rahmadani'
        );

        ActivityLog::record(
            logType: 'kunjungan',
            eventName: 'Presensi Tamu',
            description: 'Bapak Ahmad mengisi buku tamu',
            actorName: 'Bapak Ahmad'
        );

        // Filter tipe sirkulasi
        $response = $this->actingAs($this->admin())
            ->get(route('admin.logs.index', ['tipe' => 'sirkulasi']));

        $response->assertStatus(200);
        $response->assertSee('Aisyah Putri Rahmadani');
        $response->assertDontSee('Bapak Ahmad');
    }

    public function test_logs_can_be_searched_by_keyword(): void
    {
        ActivityLog::record(
            logType: 'pencarian',
            eventName: 'Pencarian OPAC',
            description: 'Pencarian katalog buku: "cambridge science"',
            actorName: 'Pengunjung Web'
        );

        ActivityLog::record(
            logType: 'kunjungan',
            eventName: 'Presensi Tamu',
            description: 'Kunjungan studi banding',
            actorName: 'H. Sudirman'
        );

        $response = $this->actingAs($this->admin())
            ->get(route('admin.logs.index', ['q' => 'cambridge']));

        $response->assertStatus(200);
        $response->assertSee('cambridge science');
        $response->assertDontSee('H. Sudirman');
    }

    public function test_record_creates_activity_log(): void
    {
        $user = User::factory()->create(['name' => 'Fulan bin Fulan']);
        $member = Member::create([
            'user_id' => $user->id,
            'member_type' => 'siswa',
            'barcode' => 'BRC-998877',
            'nisn' => '1234567890',
            'class_or_unit' => '8A',
            'is_active' => true,
        ]);

        ActivityLog::record(
            logType: 'kunjungan',
            eventName: 'Presensi Anggota',
            description: $member->user->name.' melakukan presensi di Perpustakaan',
            actorName: $member->user->name,
            actorType: $member->member_type,
            actorIdentifier: $member->barcode,
            properties: [
                'class_or_unit' => $member->class_or_unit,
                'purpose' => 'Membaca Buku',
            ]
        );

        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'kunjungan',
            'event_name' => 'Presensi Anggota',
            'actor_name' => 'Fulan bin Fulan',
            'actor_identifier' => 'BRC-998877',
        ]);
    }

    public function test_admin_can_delete_activity_log(): void
    {
        $log = ActivityLog::record(
            logType: 'kunjungan',
            eventName: 'Presensi Tamu',
            description: 'Tamu uji coba',
            actorName: 'Uji Coba'
        );

        // Librarian tidak boleh menghapus (403)
        $this->actingAs($this->librarian())
            ->delete(route('admin.logs.destroy', $log))
            ->assertStatus(403);

        // Admin utama berwenang menghapus
        $this->actingAs($this->admin())
            ->delete(route('admin.logs.destroy', $log))
            ->assertRedirect();

        $this->assertDatabaseMissing('activity_logs', [
            'id' => $log->id,
        ]);
    }

    public function test_dashboard_links_to_activity_logs(): void
    {
        $response = $this->actingAs($this->admin())
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('admin.logs.index'));
    }

    public function test_realtime_feed_returns_json_and_stats(): void
    {
        $log = ActivityLog::record(
            logType: 'pencarian',
            eventName: 'Pencarian Website',
            description: 'Mencari "madrasah digital"',
            actorName: 'Pengunjung Web'
        );

        $response = $this->actingAs($this->admin())
            ->getJson(route('admin.logs.feed', ['since_id' => $log->id - 1]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'latest_id',
            'new_count',
            'new_logs',
            'stats' => ['total_all', 'total_today', 'kunjungan_today', 'sirkulasi_today', 'pencarian_today'],
            'timestamp',
        ]);
        $response->assertJson([
            'status' => 'success',
            'new_count' => 1,
        ]);
    }

    public function test_admin_can_export_activity_logs_to_excel(): void
    {
        ActivityLog::record(
            logType: 'kunjungan',
            eventName: 'Presensi Kiosk',
            description: 'Siswa masuk perpustakaan',
            actorName: 'Ahmad Faiz'
        );

        $response = $this->actingAs($this->admin())
            ->get(route('admin.logs.export-excel', ['tipe' => 'kunjungan']));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_visit_creation_automatically_records_activity_log(): void
    {
        $visit = Visit::create([
            'visitor_type' => 'tamu',
            'guest_name' => 'Budi Santoso, M.Kom',
            'guest_institution' => 'Kemenag Jaksel',
            'purpose' => 'Kunjungan Kerja',
            'method' => 'manual',
            'visited_at' => now(),
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'log_type' => 'kunjungan',
            'event_name' => 'Kunjungan Buku Tamu',
            'actor_name' => 'Budi Santoso, M.Kom',
            'actor_type' => 'tamu',
        ]);
    }
}

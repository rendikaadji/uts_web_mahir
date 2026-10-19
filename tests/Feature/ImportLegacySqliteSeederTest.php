<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\ImportLegacySqliteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDO;
use Tests\TestCase;

class ImportLegacySqliteSeederTest extends TestCase
{
    use RefreshDatabase;

    private string $legacyPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->legacyPath = tempnam(sys_get_temp_dir(), 'legacy').'.sqlite';
        $pdo = new PDO('sqlite:'.$this->legacyPath);
        $pdo->exec('create table users (id integer primary key, name text, username text, email text, password text, role text, is_active integer, legacy_only text)');
        $pdo->exec('create table categories (id integer primary key, name text, slug text, description text, created_at text, updated_at text)');
        $pdo->exec('create table sistem_lama (id integer primary key)');
        $pdo->exec("insert into users values (1, 'Admin Lama', 'admin', null, '".Hash::make('admin123')."', 'admin_madrasah', 1, 'x')");
        $pdo->exec("insert into users values (2, 'Siswa', 'siswa1', null, '".Hash::make('rahasia')."', 'siswa_guru', 1, 'x')");
        $pdo->exec("insert into categories values (1, 'Fiksi', 'fiksi', null, null, null)");
        putenv('LEGACY_SQLITE_PATH='.$this->legacyPath);
    }

    protected function tearDown(): void
    {
        putenv('LEGACY_SQLITE_PATH');
        putenv('LEGACY_IMPORT');
        @unlink($this->legacyPath);

        parent::tearDown();
    }

    public function test_dry_run_reports_without_writing(): void
    {
        $this->seed(ImportLegacySqliteSeeder::class);

        $this->assertSame(0, Category::count());
        $this->assertSame(0, User::count());
    }

    public function test_import_copies_rows_and_keeps_current_admin_credentials(): void
    {
        $current = User::factory()->create([
            'username' => 'admin',
            'role' => 'admin_madrasah',
            'password' => 'password-baru-kuat',
        ]);
        $librarian = User::factory()->create(['username' => 'pustakawan', 'role' => 'pustakawan']);
        putenv('LEGACY_IMPORT=yes');

        $this->seed(ImportLegacySqliteSeeder::class);

        $this->assertSame(1, Category::count());
        $this->assertDatabaseHas('users', ['username' => 'siswa1']);
        $this->assertDatabaseHas('users', ['username' => $librarian->username]);
        $this->assertSame(3, User::count());

        $admin = DB::table('users')->where('username', 'admin')->first();
        $this->assertSame(1, (int) $admin->id);
        $this->assertTrue(Hash::check('password-baru-kuat', $admin->password));
        $this->assertSame($current->name, $admin->name);
    }
}

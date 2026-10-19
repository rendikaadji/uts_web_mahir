<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

/**
 * Pindahkan data dari database.sqlite lama ke database aktif. Sekali pakai.
 *
 * Bawaannya dry-run (hanya melaporkan). Data ditulis hanya jika LEGACY_IMPORT=yes.
 * Tabel tujuan dikosongkan lebih dulu, lalu diisi dari sqlite lama (kolom yang sama saja).
 * Akun yang sudah ada di tujuan dipertahankan: password dan peran barunya menimpa akun lama
 * dengan username yang sama, akun tanpa pasangan ditambahkan.
 *
 * LEGACY_SQLITE_PATH=/path/database.sqlite [LEGACY_IMPORT=yes] php artisan db:seed --class=ImportLegacySqliteSeeder --force
 */
class ImportLegacySqliteSeeder extends Seeder
{
    /** @var list<string> */
    private const SKIPPED_TABLES = [
        'migrations', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches',
        'failed_jobs', 'password_reset_tokens', 'personal_access_tokens',
    ];

    /** @var list<string> */
    private const COMMON_PASSWORDS = ['admin123', 'password', '12345678', '123456'];

    public function run(): void
    {
        $path = (string) getenv('LEGACY_SQLITE_PATH');
        $write = getenv('LEGACY_IMPORT') === 'yes';

        if (! is_file($path)) {
            throw new RuntimeException("LEGACY_SQLITE_PATH tidak menunjuk ke file: {$path}");
        }

        config(['database.connections.legacy' => [
            'driver' => 'sqlite',
            'database' => $path,
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]]);

        $legacySchema = Schema::connection('legacy');
        $targetTables = array_column(Schema::getTables(), 'name');
        $tables = array_diff(
            array_intersect(array_column($legacySchema->getTables(), 'name'), $targetTables),
            self::SKIPPED_TABLES,
        );

        $this->command->info($write ? 'MODE TULIS' : 'DRY-RUN (tidak ada yang ditulis; setel LEGACY_IMPORT=yes untuk menulis)');

        $keptUsers = $write ? DB::table('users')->get() : collect();

        Schema::disableForeignKeyConstraints();

        try {
            foreach ($tables as $table) {
                $legacyColumns = $legacySchema->getColumnListing($table);
                $columns = array_values(array_intersect($legacyColumns, Schema::getColumnListing($table)));
                $dropped = array_diff($legacyColumns, $columns);
                $rows = DB::connection('legacy')->table($table)->select($columns)->get();

                $this->command->line(sprintf(
                    '%-28s %5d baris%s',
                    $table,
                    $rows->count(),
                    $dropped ? ' (kolom diabaikan: '.implode(', ', $dropped).')' : '',
                ));

                if (! $write) {
                    continue;
                }

                DB::table($table)->truncate();
                foreach ($rows->chunk(500) as $chunk) {
                    DB::table($table)->insert($chunk->map(fn ($row) => (array) $row)->all());
                }
            }

            if ($write) {
                $this->restoreKeptUsers($keptUsers);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $skipped = array_diff(array_column($legacySchema->getTables(), 'name'), $tables, self::SKIPPED_TABLES);
        $this->command->line('Tabel lama tanpa pasangan (dilewati): '.($skipped ? implode(', ', $skipped) : '-'));

        $this->warnAboutWeakPrivilegedPasswords($write);
    }

    /**
     * @param  Collection<int, object>  $keptUsers
     */
    private function restoreKeptUsers($keptUsers): void
    {
        foreach ($keptUsers as $kept) {
            $values = (array) $kept;
            $existing = DB::table('users')->where('username', $kept->username)->first();

            if ($existing) {
                DB::table('users')->where('id', $existing->id)->update(
                    array_intersect_key($values, array_flip(['password', 'role', 'admin_level', 'is_active', 'name'])),
                );

                continue;
            }

            unset($values['id']);
            DB::table('users')->insert($values);
        }
    }

    private function warnAboutWeakPrivilegedPasswords(bool $write): void
    {
        $this->command->info($write ? 'Akun admin/pustakawan di database sekarang:' : 'Akun admin/pustakawan di data LAMA (akan masuk saat import):');

        DB::connection($write ? null : 'legacy')->table('users')
            ->whereIn('role', ['admin_madrasah', 'pustakawan'])
            ->get()
            ->each(function (object $user): void {
                $weak = collect(self::COMMON_PASSWORDS)->first(fn (string $p) => Hash::check($p, $user->password));
                $this->command->{$weak ? 'warn' : 'line'}(sprintf(
                    'Akun %s (%s): %s',
                    $user->username,
                    $user->role,
                    $weak ? "PASSWORD LEMAH \"{$weak}\", segera ganti" : 'ok',
                ));
            });
    }
}

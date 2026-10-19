<?php

namespace App\Console\Commands;

use App\Models\GuardianStudent;
use App\Models\Member;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SyncGuardianAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:sync-guardians';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi akun wali murid (wali_[NISN]) untuk seluruh santri yang memiliki NISN';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai sinkronisasi akun wali murid...');

        $students = Member::where('member_type', 'siswa')
            ->whereNotNull('nisn')
            ->where('nisn', '!=', '')
            ->with('user')
            ->get();

        $defaultPasswordHash = Hash::make('password');
        $createdCount = 0;
        $linkedCount = 0;

        DB::beginTransaction();

        try {
            foreach ($students as $student) {
                $nisn = trim((string) $student->nisn);
                if ($nisn === '') {
                    continue;
                }

                $username = 'wali_'.$nisn;
                $studentName = $student->user?->name ?? 'Santri';

                $guardian = User::firstOrCreate(
                    ['username' => $username],
                    [
                        'name' => 'Wali dari '.$studentName,
                        'email' => $username.'@wali.mtsn32.sch.id',
                        'password' => $defaultPasswordHash,
                        'role' => 'wali_murid',
                        'is_active' => true,
                    ]
                );

                if ($guardian->wasRecentlyCreated) {
                    $createdCount++;
                }

                $link = GuardianStudent::firstOrCreate([
                    'guardian_user_id' => $guardian->id,
                    'student_member_id' => $student->id,
                ]);

                if ($link->wasRecentlyCreated) {
                    $linkedCount++;
                }
            }

            DB::commit();

            $this->info("Berhasil! {$createdCount} akun wali murid baru dibuat, {$linkedCount} keterhubungan ananda dicatat.");
            $this->info('Total akun wali murid aktif saat ini: '.User::where('role', 'wali_murid')->count());

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Gagal sinkronisasi akun wali: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}

<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_type')->index();
            $table->string('event_name');
            $table->string('actor_name')->nullable();
            $table->string('actor_type')->nullable();
            $table->string('actor_identifier')->nullable();
            $table->text('description');
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable();
        });

        // Backfill riwayat kunjungan / buku tamu jika ada
        if (Schema::hasTable('visits')) {
            $visits = DB::table('visits')
                ->leftJoin('members', 'visits.member_id', '=', 'members.id')
                ->leftJoin('users', 'members.user_id', '=', 'users.id')
                ->select(
                    'visits.*',
                    'users.name as member_name',
                    'members.member_type',
                    'members.barcode as member_barcode',
                    'members.nisn',
                    'members.nip'
                )
                ->orderBy('visits.id')
                ->get();

            foreach ($visits as $visit) {
                $isAnggota = ($visit->visitor_type ?? 'anggota') === 'anggota';
                $actorName = $isAnggota ? ($visit->member_name ?? 'Anggota Madrasah') : ($visit->guest_name ?? 'Tamu Perpustakaan');
                $actorType = $isAnggota ? ($visit->member_type ?? 'siswa') : 'tamu';
                $actorId = $isAnggota ? ($visit->member_barcode ?? $visit->nisn ?? $visit->nip ?? '-') : ($visit->guest_institution ?? 'Pengunjung Umum');
                $eventName = $isAnggota ? 'Presensi Anggota' : 'Presensi Tamu';
                $purpose = $visit->purpose ? ': '.$visit->purpose : '';
                $desc = $isAnggota
                    ? "{$actorName} melakukan presensi di Gate Perpustakaan{$purpose}"
                    : "{$actorName} mengisi buku tamu perpustakaan{$purpose}";

                DB::table('activity_logs')->insert([
                    'log_type' => 'kunjungan',
                    'event_name' => $eventName,
                    'actor_name' => $actorName,
                    'actor_type' => $actorType,
                    'actor_identifier' => $actorId,
                    'description' => $desc,
                    'properties' => json_encode([
                        'visit_id' => $visit->id,
                        'purpose' => $visit->purpose,
                        'institution' => $visit->guest_institution ?? null,
                    ]),
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Gate Kiosk MTsN 32',
                    'created_at' => $visit->visited_at ?? $visit->created_at ?? now(),
                    'updated_at' => $visit->created_at ?? now(),
                ]);
            }
        }

        // Backfill riwayat peminjaman / sirkulasi buku jika ada
        if (Schema::hasTable('loans')) {
            $loans = DB::table('loans')
                ->leftJoin('book_copies', 'loans.book_copy_id', '=', 'book_copies.id')
                ->leftJoin('books', 'book_copies.book_id', '=', 'books.id')
                ->leftJoin('members', 'loans.member_id', '=', 'members.id')
                ->leftJoin('users', 'members.user_id', '=', 'users.id')
                ->select(
                    'loans.*',
                    'users.name as member_name',
                    'members.member_type',
                    'members.barcode as member_barcode',
                    'books.title as book_title',
                    'book_copies.barcode as copy_barcode'
                )
                ->orderBy('loans.id')
                ->get();

            foreach ($loans as $loan) {
                $actorName = $loan->member_name ?? 'Siswa Madrasah';
                $bookTitle = $loan->book_title ?? 'Buku Perpustakaan';

                // Log Peminjaman
                DB::table('activity_logs')->insert([
                    'log_type' => 'sirkulasi',
                    'event_name' => 'Sirkulasi Buku',
                    'actor_name' => $actorName,
                    'actor_type' => $loan->member_type ?? 'siswa',
                    'actor_identifier' => $loan->member_barcode ?? '-',
                    'description' => "{$actorName} meminjam {$bookTitle}",
                    'properties' => json_encode([
                        'loan_id' => $loan->id,
                        'copy_barcode' => $loan->copy_barcode,
                        'due_date' => $loan->due_date,
                    ]),
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Sistem Sirkulasi Perpustakaan',
                    'created_at' => $loan->created_at ?? now(),
                    'updated_at' => $loan->created_at ?? now(),
                ]);

                // Jika sudah dikembalikan
                if (! empty($loan->return_date) || in_array($loan->status, ['kembali', 'terlambat'])) {
                    $returnDate = $loan->return_date ? Carbon::parse($loan->return_date) : ($loan->updated_at ?? now());
                    $descReturn = "{$actorName} mengembalikan {$bookTitle}".($loan->status === 'terlambat' ? ' (Terlambat)' : '');

                    DB::table('activity_logs')->insert([
                        'log_type' => 'sirkulasi',
                        'event_name' => 'Pengembalian Buku',
                        'actor_name' => $actorName,
                        'actor_type' => $loan->member_type ?? 'siswa',
                        'actor_identifier' => $loan->member_barcode ?? '-',
                        'description' => $descReturn,
                        'properties' => json_encode([
                            'loan_id' => $loan->id,
                            'status' => $loan->status,
                            'return_date' => $loan->return_date,
                        ]),
                        'ip_address' => '127.0.0.1',
                        'user_agent' => 'Sistem Sirkulasi Perpustakaan',
                        'created_at' => $returnDate,
                        'updated_at' => $loan->updated_at ?? now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

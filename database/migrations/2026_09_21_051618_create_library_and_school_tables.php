<?php

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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->enum('member_type', ['siswa', 'guru'])->default('siswa');
            $table->string('nisn')->nullable()->unique();
            $table->string('nip')->nullable()->unique();
            $table->string('barcode')->unique();
            $table->string('phone')->nullable();
            $table->string('class_or_unit')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('guardian_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guardian_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_member_id')->constrained('members')->cascadeOnDelete();
            $table->unique(['guardian_user_id', 'student_member_id']);
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author');
            $table->string('publisher');
            $table->integer('publication_year')->default(2024);
            $table->string('isbn')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_digital_available')->default(false);
            $table->string('digital_file_path')->nullable();
            $table->timestamps();
        });

        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->string('barcode')->unique();
            $table->string('copy_code')->default('C-01');
            $table->enum('status', ['tersedia', 'dipinjam', 'rusak', 'hilang'])->default('tersedia');
            $table->string('condition_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_copy_id')->constrained('book_copies')->restrictOnDelete();
            $table->foreignId('member_id')->constrained('members')->restrictOnDelete();
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['aktif', 'kembali', 'terlambat'])->default('aktif');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->unique()->constrained('loans')->cascadeOnDelete();
            $table->integer('days_overdue')->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('payment_status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->enum('visitor_type', ['anggota', 'tamu'])->default('anggota');
            $table->string('guest_name')->nullable();
            $table->string('guest_institution')->nullable();
            $table->string('purpose')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('category', ['berita', 'pengumuman', 'agenda'])->default('berita');
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('image')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('visits');
        Schema::dropIfExists('fines');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('book_copies');
        Schema::dropIfExists('books');
        Schema::dropIfExists('guardian_students');
        Schema::dropIfExists('members');
        Schema::dropIfExists('categories');
    }
};

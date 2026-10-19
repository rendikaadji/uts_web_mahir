<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use App\Services\MemberQrZipExporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ZipArchive;

class StudentQrZipExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_export_qr_zip(): void
    {
        $response = $this->get('/api/v1/members/export-qr-zip');

        $this->assertTrue(in_array($response->status(), [302, 401], true));
    }

    public function test_non_staff_user_is_forbidden_from_exporting_qr_zip(): void
    {
        $studentUser = User::factory()->create([
            'role' => 'siswa_guru',
            'is_active' => true,
        ]);

        $response = $this->actingAs($studentUser)->get('/api/v1/members/export-qr-zip');

        $response->assertStatus(403);
    }

    public function test_staff_can_export_qr_zip_with_exact_folder_hierarchy(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        // Guru anggota
        $teacher = Member::enroll([
            'name' => 'Rizki Ramadhan, M.Ed.',
            'username' => '198501012010011001',
            'email' => 'rizki@guru.mtsn32.sch.id',
            'password_hash' => 'password',
            'member_type' => 'guru',
        ]);

        // Siswa di kelas 7-B (rombel 72)
        $studentA = Member::enroll([
            'name' => 'Aisyah Putri Rahmadani',
            'username' => '0087654321',
            'email' => 'aisyah@siswa.mtsn32.sch.id',
            'password_hash' => 'password',
            'member_type' => 'siswa',
        ]);
        $studentA->member()->update(['class_or_unit' => 'Kelas 7-B (Bilingual)']);

        // Siswa di kelas 81 (rombel 81)
        $studentB = Member::enroll([
            'name' => 'Muhammad Raihan Pratama',
            'username' => '0081234567',
            'email' => 'raihan@siswa.mtsn32.sch.id',
            'password_hash' => 'password',
            'member_type' => 'siswa',
        ]);
        $studentB->member()->update(['class_or_unit' => 'Kelas 8-A (Cambridge Class)']);

        // Siswa di kelas 9.5 (rombel 95)
        $studentC = Member::enroll([
            'name' => 'Zahra Amelia',
            'username' => '0089998887',
            'email' => 'zahra@siswa.mtsn32.sch.id',
            'password_hash' => 'password',
            'member_type' => 'siswa',
        ]);
        $studentC->member()->update(['class_or_unit' => 'Kelas 9.5']);

        $response = $this->actingAs($librarian)->get('/api/v1/members/export-qr-zip');

        $response->assertStatus(200);
        $this->assertStringContainsString('data_anggota.zip', (string) $response->headers->get('content-disposition'));

        $zipPath = $response->baseResponse->getFile()->getPathname();
        $this->assertFileExists($zipPath);

        $zip = new ZipArchive;
        $this->assertTrue($zip->open($zipPath) === true);

        // Kumpulkan semua daftar entri file/folder di zip
        $entries = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entries[] = $zip->getNameIndex($i);
        }

        // 1. Cek folder root utama: guru/ dan siswa/
        $this->assertContains('guru/', $entries);
        $this->assertContains('siswa/', $entries);

        // 2. Cek guru langsung terdapat folder nama guru dan berkas QR PNG
        $expectedTeacherFile = 'guru/Rizki Ramadhan, M.Ed./QR_Rizki Ramadhan, M.Ed..png';
        $this->assertContains($expectedTeacherFile, $entries);
        $pngTeacher = $zip->getFromName($expectedTeacherFile);
        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $pngTeacher);

        // 3. Cek semua 15 struktur rombel siswa di dalam folder siswa/
        $expectedStudentFolders = [
            'siswa/kelas 7/', 'siswa/kelas 7/71/', 'siswa/kelas 7/72/', 'siswa/kelas 7/73/', 'siswa/kelas 7/74/', 'siswa/kelas 7/75/',
            'siswa/kelas 8/', 'siswa/kelas 8/81/', 'siswa/kelas 8/82/', 'siswa/kelas 8/83/', 'siswa/kelas 8/84/', 'siswa/kelas 8/85/',
            'siswa/kelas 9/', 'siswa/kelas 9/91/', 'siswa/kelas 9/92/', 'siswa/kelas 9/93/', 'siswa/kelas 9/94/', 'siswa/kelas 9/95/',
        ];

        foreach ($expectedStudentFolders as $folder) {
            $this->assertContains($folder, $entries, "Folder {$folder} wajib ada di dalam ZIP.");
        }

        // 4. Cek siswa masuk ke folder kelas yang sesuai beserta berkas QR PNG-nya
        $expectedStudentAFile = 'siswa/kelas 7/72/Aisyah Putri Rahmadani/QR_Aisyah Putri Rahmadani.png';
        $this->assertContains($expectedStudentAFile, $entries);
        $pngA = $zip->getFromName($expectedStudentAFile);
        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $pngA);

        $expectedStudentBFile = 'siswa/kelas 8/81/Muhammad Raihan Pratama/QR_Muhammad Raihan Pratama.png';
        $this->assertContains($expectedStudentBFile, $entries);
        $pngB = $zip->getFromName($expectedStudentBFile);
        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $pngB);

        $expectedStudentCFile = 'siswa/kelas 9/95/Zahra Amelia/QR_Zahra Amelia.png';
        $this->assertContains($expectedStudentCFile, $entries);
        $pngC = $zip->getFromName($expectedStudentCFile);
        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $pngC);

        $zip->close();
        @unlink($zipPath);
    }

    public function test_class_folder_parser(): void
    {
        $this->assertSame(['grade' => 'kelas 7', 'section' => '71'], MemberQrZipExporter::parseClassFolder('71'));
        $this->assertSame(['grade' => 'kelas 7', 'section' => '72'], MemberQrZipExporter::parseClassFolder('Kelas 7-B'));
        $this->assertSame(['grade' => 'kelas 8', 'section' => '83'], MemberQrZipExporter::parseClassFolder('Kelas 8-C (Unggulan)'));
        $this->assertSame(['grade' => 'kelas 8', 'section' => '84'], MemberQrZipExporter::parseClassFolder('VIII D'));
        $this->assertSame(['grade' => 'kelas 9', 'section' => '95'], MemberQrZipExporter::parseClassFolder('Kelas 9.5'));
        $this->assertSame(['grade' => 'kelas 9', 'section' => '91'], MemberQrZipExporter::parseClassFolder('IX-A'));
        $this->assertSame(['grade' => 'kelas 7', 'section' => '71'], MemberQrZipExporter::parseClassFolder(null));
    }
}

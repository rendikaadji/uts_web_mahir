<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use App\Services\SimpleExcelReader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MemberImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_download_member_import_template(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $response = $this->actingAs($librarian)->get('/api/v1/members/template');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $templatePath = $response->baseResponse->getFile()->getPathname();
        $rows = SimpleExcelReader::readXlsx($templatePath);

        $this->assertSame(['Nama Lengkap', 'NIS/NIP', 'Peran', 'Email', 'Password'], $rows[0]);

        // Fill one row like a user would; headers must be recognised and the "Petunjuk" sheet ignored.
        $zip = new \ZipArchive;
        $zip->open($templatePath);
        $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
        $sheet = str_replace('<c r="A2" s="2"/>', '<c r="A2" s="2" t="inlineStr"><is><t>Siswa Template</t></is></c>', $sheet);
        $sheet = str_replace('<c r="B2" s="2"/>', '<c r="B2" s="2" t="inlineStr"><is><t>0081112223</t></is></c>', $sheet);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        $file = new UploadedFile($templatePath, 'template.xlsx', null, null, true);
        $this->actingAs($librarian)
            ->postJson('/api/v1/members/import', ['file' => $file])
            ->assertOk()
            ->assertJsonPath('data.imported', 1)
            ->assertJsonPath('data.skipped', 0);

        $this->assertDatabaseHas('users', ['username' => '0081112223', 'name' => 'Siswa Template', 'role' => 'siswa_guru']);
        $this->assertDatabaseHas('members', ['nisn' => '0081112223', 'member_type' => 'siswa']);

        @unlink($templatePath);
    }

    public function test_staff_can_import_members_from_csv(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $csvContent = "nama,nis_nip,peran,email,password\n";
        $csvContent .= "Raihan Fadilah,0091122334,siswa,raihan@siswa.mtsn32.sch.id,secret123\n";
        $csvContent .= "Hj. Nurul Hidayah,197901012000012001,guru,nurul@guru.mtsn32.sch.id,secret123\n";

        $file = UploadedFile::fake()->createWithContent('import_test.csv', $csvContent);

        $response = $this->actingAs($librarian)->postJson('/api/v1/members/import', [
            'file' => $file,
            'default_role' => 'siswa',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'imported' => 2,
                    'updated' => 0,
                    'skipped' => 0,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Raihan Fadilah',
            'username' => '0091122334',
            'role' => 'siswa_guru',
        ]);
        $this->assertDatabaseHas('members', ['nisn' => '0091122334', 'member_type' => 'siswa']);

        $this->assertDatabaseHas('users', [
            'name' => 'Hj. Nurul Hidayah',
            'username' => '197901012000012001',
            'role' => 'siswa_guru',
        ]);
        $this->assertDatabaseHas('members', ['nip' => '197901012000012001', 'member_type' => 'guru']);

        $user = User::where('username', '0091122334')->first();
        $this->assertNotNull($user->activeQrToken);
    }

    public function test_import_can_update_existing_members(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $existing = Member::enroll([
            'name' => 'Nama Lama',
            'username' => '0099998888',
            'email' => 'lama@siswa.mtsn32.sch.id',
            'password_hash' => 'password',
            'member_type' => 'siswa',
        ]);

        $csvContent = "nama,nis_nip,peran,email,password\n";
        $csvContent .= "Nama Baru Diperbarui,0099998888,siswa,baru@siswa.mtsn32.sch.id,newpass123\n";

        $file = UploadedFile::fake()->createWithContent('update_test.csv', $csvContent);

        $response = $this->actingAs($librarian)->postJson('/api/v1/members/import', [
            'file' => $file,
            'update_existing' => true,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'imported' => 0,
                    'updated' => 1,
                    'skipped' => 0,
                ],
            ]);

        $existing->refresh();
        $this->assertSame('Nama Baru Diperbarui', $existing->name);
    }

    public function test_non_staff_cannot_import_members(): void
    {
        $student = User::factory()->create(['role' => 'siswa_guru']);
        $file = UploadedFile::fake()->createWithContent('hack.csv', "nama,nis_nip\nHack,123\n");

        $this->actingAs($student)
            ->postJson('/api/v1/members/import', ['file' => $file])
            ->assertStatus(403);
    }

    public function test_staff_can_import_members_from_xlsx(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $rows = [
            ['nama', 'nis_nip', 'peran', 'email', 'password'],
            ['Siti Aisyah Excel', '0089876543', 'siswa', 'aisyah.excel@siswa.mtsn32.sch.id', 'secret123'],
        ];

        $xlsxPath = $this->createFakeXlsx($rows);
        $file = new UploadedFile($xlsxPath, 'test_excel.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($librarian)->postJson('/api/v1/members/import', [
            'file' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'imported' => 1,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Siti Aisyah Excel',
            'username' => '0089876543',
        ]);

        @unlink($xlsxPath);
    }

    public function test_import_supports_title_rows_and_indonesian_column_aliases_and_class(): void
    {
        $librarian = User::factory()->create([
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $csvContent = "DATA SISWA MTsN 32 JAKARTA SELATAN\n";
        $csvContent .= "TAHUN AJARAN 2025/2026\n";
        $csvContent .= "No,Nama Peserta Didik,NISN,Kelas / Rombel\n";
        $csvContent .= "1,Ahmad Maulana,0099887766,71\n";

        $file = UploadedFile::fake()->createWithContent('emis_siswa.csv', $csvContent);

        $response = $this->actingAs($librarian)->postJson('/api/v1/members/import', [
            'file' => $file,
            'default_role' => 'siswa',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'imported' => 1,
                    'skipped' => 0,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Ahmad Maulana',
            'username' => '0099887766',
            'role' => 'siswa_guru',
        ]);

        $this->assertDatabaseHas('members', [
            'nisn' => '0099887766',
            'member_type' => 'siswa',
            'class_or_unit' => '71',
        ]);
    }

    protected function createFakeXlsx(array $rows): string
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'xlsx_test_').'.xlsx';
        $zip = new \ZipArchive;
        $zip->open($tempPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Default Extension="xml" ContentType="application/xml"/>
<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
</Types>');

        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>');

        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>
</Relationships>');

        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheets><sheet name="Sheet1" sheetId="1" r:id="rId1" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/></sheets></workbook>');

        $strings = [];
        $stringMap = [];
        $rowsXml = '';
        foreach ($rows as $rIdx => $row) {
            $rowNum = $rIdx + 1;
            $rowsXml .= "<row r=\"{$rowNum}\">";
            foreach ($row as $cIdx => $val) {
                if (! isset($stringMap[$val])) {
                    $stringMap[$val] = count($strings);
                    $strings[] = $val;
                }
                $sId = $stringMap[$val];
                $colLetter = chr(ord('A') + $cIdx);
                $rowsXml .= "<c r=\"{$colLetter}{$rowNum}\" t=\"s\"><v>{$sId}</v></c>";
            }
            $rowsXml .= '</row>';
        }

        $sharedXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="'.count($strings).'" uniqueCount="'.count($strings).'">';
        foreach ($strings as $s) {
            $sharedXml .= '<si><t>'.htmlspecialchars($s).'</t></si>';
        }
        $sharedXml .= '</sst>';
        $zip->addFromString('xl/sharedStrings.xml', $sharedXml);

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>'.$rowsXml.'</sheetData></worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

        $zip->close();

        return $tempPath;
    }
}

<?php

namespace App\Services;

use ZipArchive;

/**
 * Builds the member import Excel (.xlsx) template with ZipArchive (no spreadsheet library).
 *
 * Sheet 1 "Data Anggota" holds only the header row, because SimpleExcelReader imports the
 * first worksheet. All guidance lives on sheet 2 "Petunjuk", which the importer never reads.
 */
class MemberImportTemplate
{
    private const XML_HEADER = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

    private const NS_MAIN = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    private const NS_REL = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    private const NS_PKG_REL = 'http://schemas.openxmlformats.org/package/2006/relationships';

    /** Number of pre-formatted (bordered, text) rows on the data sheet. */
    private const DATA_ROWS = 500;

    // Cell style indexes, matching the <cellXfs> order in styles().
    private const HEADER = 1;

    private const DATA = 2;

    private const DATA_ZEBRA = 3;

    private const TITLE = 4;

    private const SUBTITLE = 5;

    private const BODY = 6;

    private const KEY = 7;

    private const REQUIRED = 8;

    private const OPTIONAL = 9;

    private const SECTION = 10;

    private const STEP = 11;

    private const WARNING = 12;

    private const EXAMPLE = 13;

    /**
     * Write the template to a temporary file and return its path.
     */
    public static function build(): string
    {
        $path = tempnam(storage_path('app'), 'tpl');

        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::OVERWRITE);

        $zip->addFromString('[Content_Types].xml', self::XML_HEADER
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet2.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'</Types>');
        $zip->addFromString('_rels/.rels', self::XML_HEADER
            .'<Relationships xmlns="'.self::NS_PKG_REL.'">'
            .'<Relationship Id="rId1" Type="'.self::NS_REL.'/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>');
        $zip->addFromString('xl/workbook.xml', self::XML_HEADER
            .'<workbook xmlns="'.self::NS_MAIN.'" xmlns:r="'.self::NS_REL.'">'
            .'<bookViews><workbookView activeTab="1"/></bookViews>'
            .'<sheets><sheet name="Data Anggota" sheetId="1" r:id="rId1"/><sheet name="Petunjuk" sheetId="2" r:id="rId2"/></sheets>'
            .'</workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', self::XML_HEADER
            .'<Relationships xmlns="'.self::NS_PKG_REL.'">'
            .'<Relationship Id="rId1" Type="'.self::NS_REL.'/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="'.self::NS_REL.'/worksheet" Target="worksheets/sheet2.xml"/>'
            .'<Relationship Id="rId3" Type="'.self::NS_REL.'/styles" Target="styles.xml"/>'
            .'</Relationships>');
        $zip->addFromString('xl/worksheets/sheet1.xml', self::dataSheet());
        $zip->addFromString('xl/worksheets/sheet2.xml', self::guideSheet());
        $zip->addFromString('xl/styles.xml', self::styles());
        $zip->close();

        return $path;
    }

    /**
     * Sheet 1: header row + empty bordered text rows, with per-column input hints.
     */
    private static function dataSheet(): string
    {
        $rows = [1 => [
            'A' => ['Nama Lengkap', self::HEADER],
            'B' => ['NIS/NIP', self::HEADER],
            'C' => ['Peran', self::HEADER],
            'D' => ['Email', self::HEADER],
            'E' => ['Password', self::HEADER],
        ]];
        $heights = [1 => 26];

        for ($row = 2; $row <= self::DATA_ROWS + 1; $row++) {
            $style = $row % 2 === 0 ? self::DATA : self::DATA_ZEBRA;
            $rows[$row] = array_fill_keys(['A', 'B', 'C', 'D', 'E'], [null, $style]);
        }

        $last = self::DATA_ROWS + 1;
        $hint = fn (string $range, string $title, string $message, string $extra = ''): string => '<dataValidation '.$extra
            .'allowBlank="1" showInputMessage="1" showErrorMessage="1" promptTitle="'.$title.'" prompt="'.$message.'" sqref="'.$range.'">'
            .($extra !== '' ? '<formula1>"siswa,guru"</formula1>' : '').'</dataValidation>';

        return self::XML_HEADER
            .'<worksheet xmlns="'.self::NS_MAIN.'">'
            .'<sheetPr><tabColor rgb="FF0B1B3D"/></sheetPr>'
            .'<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/>'
            .'<selection pane="bottomLeft" activeCell="A2" sqref="A2"/></sheetView></sheetViews>'
            .'<cols><col min="1" max="1" width="36" customWidth="1"/><col min="2" max="2" width="24" customWidth="1"/>'
            .'<col min="3" max="3" width="12" customWidth="1"/><col min="4" max="4" width="36" customWidth="1"/>'
            .'<col min="5" max="5" width="18" customWidth="1"/></cols>'
            .'<sheetData>'.self::rowsXml($rows, $heights).'</sheetData>'
            .'<dataValidations count="5">'
            .$hint("A2:A{$last}", 'Nama Lengkap (wajib)', 'Nama lengkap anggota.')
            .$hint("B2:B{$last}", 'NIS/NIP (wajib)', 'NIS untuk siswa, NIP untuk guru. Angka 0 di depan tetap aman.')
            .$hint("C2:C{$last}", 'Peran (opsional)', 'Pilih siswa atau guru. Kosong = Peran Default di form impor.', 'type="list" errorTitle="Peran tidak valid" error="Isi dengan siswa atau guru." ')
            .$hint("D2:D{$last}", 'Email (opsional)', 'Kosong = dibuat otomatis dari NIS/NIP.')
            .$hint("E2:E{$last}", 'Password (opsional)', 'Kosong = password awal: password')
            .'</dataValidations>'
            .'</worksheet>';
    }

    /**
     * Sheet 2: human-readable guide. Never read by the importer.
     */
    private static function guideSheet(): string
    {
        $rows = [
            1 => self::merged('Template Impor Anggota — Perpustakaan MTsN 32 Jakarta', self::TITLE),
            2 => self::merged('Isi data pada sheet "Data Anggota", lalu unggah file ini di menu Impor Anggota.', self::SUBTITLE),
            4 => ['A' => ['Kolom', self::HEADER], 'B' => ['Status', self::HEADER], 'C' => ['Keterangan', self::HEADER], 'D' => ['Contoh', self::HEADER]],
        ];

        $columns = [
            ['Nama Lengkap', true, 'Nama lengkap anggota sesuai data madrasah.', 'Ahmad Zaki Pratama'],
            ['NIS/NIP', true, 'NIS untuk siswa, NIP untuk guru. Unik per anggota; angka 0 di depan tetap aman.', '0081234567'],
            ['Peran', false, 'Pilih "siswa" atau "guru" dari dropdown. Jika kosong, memakai Peran Default di form impor.', 'siswa'],
            ['Email', false, 'Jika kosong, dibuat otomatis: NIS/NIP@siswa.mtsn32.sch.id atau @guru.mtsn32.sch.id.', 'zaki@siswa.mtsn32.sch.id'],
            ['Password', false, 'Jika kosong, password awal adalah "password". Sarankan anggota menggantinya setelah login.', 'password123'],
        ];
        foreach ($columns as $i => [$name, $required, $description, $example]) {
            $rows[5 + $i] = [
                'A' => [$name, self::KEY],
                'B' => [$required ? 'Wajib' : 'Opsional', $required ? self::REQUIRED : self::OPTIONAL],
                'C' => [$description, self::BODY],
                'D' => [$example, self::EXAMPLE],
            ];
        }

        $rows[11] = self::merged('Langkah Pengisian', self::SECTION);
        $steps = [
            '1.  Buka sheet "Data Anggota", isi data mulai baris ke-2 (satu baris = satu anggota).',
            '2.  Simpan file dalam format Excel (.xlsx).',
            '3.  Di web, buka Impor Anggota, pilih file ini, lalu klik "Mulai Impor ke Database".',
            '4.  Periksa ringkasan hasil impor; baris yang bermasalah ditampilkan beserta nomor barisnya.',
        ];
        foreach ($steps as $i => $step) {
            $rows[12 + $i] = self::merged($step, self::STEP);
        }

        $rows[17] = self::merged('Penting', self::SECTION);
        $warnings = [
            '•  Jangan mengubah atau menghapus judul kolom (baris 1) pada sheet "Data Anggota".',
            '•  Jangan memindah urutan sheet: "Data Anggota" harus tetap menjadi sheet pertama.',
            '•  Saat menyalin dari file lain, gunakan Paste Values (Ctrl+Shift+V) agar NIS/NIP tetap berformat teks.',
            '•  Sheet "Petunjuk" ini tidak ikut diimpor, jadi aman dibiarkan.',
        ];
        foreach ($warnings as $i => $warning) {
            $rows[18 + $i] = self::merged($warning, self::WARNING);
        }

        $heights = [1 => 32, 2 => 20, 4 => 24, 11 => 24, 17 => 24]
            + array_fill(5, 5, 30) + array_fill(12, 4, 20) + array_fill(18, 4, 22);

        $merges = '';
        foreach ([1, 2, 11, 12, 13, 14, 15, 17, 18, 19, 20, 21] as $row) {
            $merges .= '<mergeCell ref="A'.$row.':D'.$row.'"/>';
        }

        return self::XML_HEADER
            .'<worksheet xmlns="'.self::NS_MAIN.'">'
            .'<sheetPr><tabColor rgb="FF01ABE8"/></sheetPr>'
            .'<sheetViews><sheetView showGridLines="0" tabSelected="1" workbookViewId="0"/></sheetViews>'
            .'<cols><col min="1" max="1" width="18" customWidth="1"/><col min="2" max="2" width="12" customWidth="1"/>'
            .'<col min="3" max="3" width="70" customWidth="1"/><col min="4" max="4" width="28" customWidth="1"/></cols>'
            .'<sheetData>'.self::rowsXml($rows, $heights).'</sheetData>'
            .'<mergeCells count="12">'.$merges.'</mergeCells>'
            .'</worksheet>';
    }

    /**
     * A row whose text spans A–D (every cell styled so fills/borders cover the merge).
     *
     * @return array<string, array{0: string|null, 1: int}>
     */
    private static function merged(string $text, int $style): array
    {
        return ['A' => [$text, $style], 'B' => [null, $style], 'C' => [null, $style], 'D' => [null, $style]];
    }

    /**
     * @param  array<int, array<string, array{0: string|null, 1: int}>>  $rows  row number => [column => [value, style]]
     * @param  array<int, int>  $heights  row number => height in points
     */
    private static function rowsXml(array $rows, array $heights): string
    {
        $xml = '';
        foreach ($rows as $rowNumber => $row) {
            $height = isset($heights[$rowNumber]) ? ' ht="'.$heights[$rowNumber].'" customHeight="1"' : '';
            $xml .= '<row r="'.$rowNumber.'"'.$height.'>';
            foreach ($row as $column => [$value, $style]) {
                $ref = $column.$rowNumber;
                $xml .= $value === null
                    ? '<c r="'.$ref.'" s="'.$style.'"/>'
                    : '<c r="'.$ref.'" s="'.$style.'" t="inlineStr"><is><t>'.htmlspecialchars($value, ENT_XML1).'</t></is></c>';
            }
            $xml .= '</row>';
        }

        return $xml;
    }

    private static function styles(): string
    {
        $font = fn (string $extra = '', string $color = 'FF1F2937', int $size = 11): string => '<font>'.$extra.'<sz val="'.$size.'"/><color rgb="'.$color.'"/><name val="Calibri"/></font>';
        $fill = fn (string $color): string => '<fill><patternFill patternType="solid"><fgColor rgb="'.$color.'"/></patternFill></fill>';
        $center = '<alignment horizontal="center" vertical="center"/>';
        $middle = '<alignment vertical="center"/>';
        $wrap = '<alignment vertical="center" wrapText="1"/>';

        return self::XML_HEADER
            .'<styleSheet xmlns="'.self::NS_MAIN.'">'
            .'<fonts count="8">'
            .$font()                                  // 0 body
            .$font('<b/>', 'FFFFFFFF')                // 1 header white
            .$font('<b/>', 'FF0B1B3D', 16)            // 2 title navy
            .$font('<i/>', 'FF6B7280', 10)            // 3 subtitle / example gray
            .$font('<b/>', 'FF0B1B3D')                // 4 bold navy
            .$font('<b/>', 'FFB91C1C')                // 5 required red
            .$font('', 'FF6B7280')                    // 6 optional gray
            .$font('', 'FF9A3412')                    // 7 warning amber
            .'</fonts>'
            .'<fills count="6"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill>'
            .$fill('FF0B1B3D').$fill('FFF4F7FB').$fill('FFE6F6FD').$fill('FFFFF7ED')
            .'</fills>'
            .'<borders count="2"><border/>'
            .'<border><left style="thin"><color rgb="FFD1D5DB"/></left><right style="thin"><color rgb="FFD1D5DB"/></right>'
            .'<top style="thin"><color rgb="FFD1D5DB"/></top><bottom style="thin"><color rgb="FFD1D5DB"/></bottom></border>'
            .'</borders>'
            .'<cellStyleXfs count="1"><xf/></cellStyleXfs>'
            .'<cellXfs count="14">'
            .'<xf/>'
            .'<xf numFmtId="49" fontId="1" fillId="2" borderId="1" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">'.$center.'</xf>'
            .'<xf numFmtId="49" borderId="1" applyNumberFormat="1" applyBorder="1" applyAlignment="1">'.$middle.'</xf>'
            .'<xf numFmtId="49" fillId="3" borderId="1" applyNumberFormat="1" applyFill="1" applyBorder="1" applyAlignment="1">'.$middle.'</xf>'
            .'<xf fontId="2" applyFont="1" applyAlignment="1">'.$middle.'</xf>'
            .'<xf fontId="3" applyFont="1" applyAlignment="1">'.$middle.'</xf>'
            .'<xf borderId="1" applyBorder="1" applyAlignment="1">'.$wrap.'</xf>'
            .'<xf fontId="4" borderId="1" applyFont="1" applyBorder="1" applyAlignment="1">'.$middle.'</xf>'
            .'<xf fontId="5" borderId="1" applyFont="1" applyBorder="1" applyAlignment="1">'.$center.'</xf>'
            .'<xf fontId="6" borderId="1" applyFont="1" applyBorder="1" applyAlignment="1">'.$center.'</xf>'
            .'<xf fontId="4" fillId="4" applyFont="1" applyFill="1" applyAlignment="1"><alignment vertical="center" indent="1"/></xf>'
            .'<xf applyAlignment="1"><alignment vertical="center" indent="1"/></xf>'
            .'<xf fontId="7" fillId="5" applyFont="1" applyFill="1" applyAlignment="1"><alignment vertical="center" indent="1"/></xf>'
            .'<xf numFmtId="49" fontId="3" borderId="1" applyNumberFormat="1" applyFont="1" applyBorder="1" applyAlignment="1">'.$middle.'</xf>'
            .'</cellXfs>'
            .'</styleSheet>';
    }
}

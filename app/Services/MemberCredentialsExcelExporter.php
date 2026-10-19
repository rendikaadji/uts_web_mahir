<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use ZipArchive;

class MemberCredentialsExcelExporter
{
    private const XML_HEADER = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

    private const NS_MAIN = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    private const NS_REL = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    private const NS_PKG_REL = 'http://schemas.openxmlformats.org/package/2006/relationships';

    /**
     * Build the Excel (.xlsx) file containing member credentials and return the temporary file path.
     *
     * @param  Collection  $members  Collection of Member models with user relation loaded.
     * @param  string  $filterDescription  Human-readable description of current filter (e.g. "Kelas 71").
     * @return string Path to the temporary .xlsx file.
     *
     * @throws Exception
     */
    public static function build(Collection $members, string $filterDescription = 'Semua Civitas'): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new Exception('Ekstensi PHP ZipArchive tidak tersedia di server.');
        }

        $path = tempnam(sys_get_temp_dir(), 'cred_');
        if (! $path) {
            throw new Exception('Gagal membuat berkas sementara untuk ekspor Excel.');
        }

        $xlsxPath = $path.'.xlsx';
        @unlink($path);

        $zip = new ZipArchive;
        $openResult = $zip->open($xlsxPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($openResult !== true) {
            throw new Exception("Gagal membuat arsip XLSX (Kode: {$openResult}).");
        }

        $zip->addFromString('[Content_Types].xml', self::XML_HEADER
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'</Types>');

        $zip->addFromString('_rels/.rels', self::XML_HEADER
            .'<Relationships xmlns="'.self::NS_PKG_REL.'">'
            .'<Relationship Id="rId1" Type="'.self::NS_REL.'/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>');

        $zip->addFromString('xl/workbook.xml', self::XML_HEADER
            .'<workbook xmlns="'.self::NS_MAIN.'" xmlns:r="'.self::NS_REL.'">'
            .'<bookViews><workbookView activeTab="0"/></bookViews>'
            .'<sheets><sheet name="Kredensial Akun" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>');

        $zip->addFromString('xl/_rels/workbook.xml.rels', self::XML_HEADER
            .'<Relationships xmlns="'.self::NS_PKG_REL.'">'
            .'<Relationship Id="rId1" Type="'.self::NS_REL.'/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="'.self::NS_REL.'/styles" Target="styles.xml"/>'
            .'</Relationships>');

        $zip->addFromString('xl/worksheets/sheet1.xml', self::sheetXml($members, $filterDescription));
        $zip->addFromString('xl/styles.xml', self::stylesXml());
        $zip->close();

        return $xlsxPath;
    }

    /**
     * Generate worksheet XML.
     */
    private static function sheetXml(Collection $members, string $filterDescription): string
    {
        $downloadDate = now()->translatedFormat('d F Y, H:i').' WIB';

        $rows = [
            // Row 1: Main Title
            1 => [
                'A' => ['REKAP AKUN & KREDENSIAL LOGIN PORTAL MANDIRI — MTsN 32 JAKARTA', 1],
                'B' => ['', 1], 'C' => ['', 1], 'D' => ['', 1], 'E' => ['', 1], 'F' => ['', 1], 'G' => ['', 1], 'H' => ['', 1],
            ],
            // Row 2: Subtitle Info
            2 => [
                'A' => ["Filter Data: {$filterDescription}  |  Diunduh: {$downloadDate}  |  Total: {$members->count()} Akun", 2],
                'B' => ['', 2], 'C' => ['', 2], 'D' => ['', 2], 'E' => ['', 2], 'F' => ['', 2], 'G' => ['', 2], 'H' => ['', 2],
            ],
            // Row 3: Blank separator
            3 => [
                'A' => ['', 0], 'B' => ['', 0], 'C' => ['', 0], 'D' => ['', 0], 'E' => ['', 0], 'F' => ['', 0], 'G' => ['', 0], 'H' => ['', 0],
            ],
            // Row 4: Table Column Headers
            4 => [
                'A' => ['NO', 3],
                'B' => ['NAMA LENGKAP', 3],
                'C' => ['PERAN', 3],
                'D' => ['KELAS / UNIT', 3],
                'E' => ['USERNAME (NISN / NIP)', 3],
                'F' => ['PASSWORD AWAL', 3],
                'G' => ['STATUS AKUN', 3],
                'H' => ['PETUNJUK BAGI PENGGUNA', 3],
            ],
        ];

        $heights = [
            1 => 28,
            2 => 20,
            3 => 10,
            4 => 25,
        ];

        $i = 0;
        foreach ($members as $m) {
            $i++;
            $rowNumber = $i + 4;
            $isZebra = ($i % 2 === 0);

            $user = $m->user;
            $name = $user ? $user->name : 'Tanpa Nama';
            $roleLabel = ($m->member_type === 'guru') ? 'Dewan Guru' : 'Siswa';
            $class = ($m->member_type === 'guru') ? '-' : ($m->class_or_unit ?? '-');
            $username = $user ? $user->username : ($m->nisn ?? $m->nip ?? '-');
            $password = 'password';
            $status = $m->is_active ? 'Aktif' : 'Nonaktif';
            $guide = 'Login di web madrasah, lalu segera ganti password di Dashboard.';

            $styleText = $isZebra ? 8 : 4;
            $styleCenter = $isZebra ? 9 : 5;
            $styleCode = $isZebra ? 10 : 6;
            $stylePass = $isZebra ? 11 : 7;

            $rows[$rowNumber] = [
                'A' => [(string) $i, $styleCenter],
                'B' => [$name, $styleText],
                'C' => [$roleLabel, $styleCenter],
                'D' => [$class, $styleCenter],
                'E' => [$username, $styleCode],
                'F' => [$password, $stylePass],
                'G' => [$status, $styleCenter],
                'H' => [$guide, $styleText],
            ];

            $heights[$rowNumber] = 22;
        }

        // Summary Note Row
        $summaryRow = count($rows) + 2;
        $rows[$summaryRow] = [
            'A' => ['Catatan: Password awal di atas adalah kata sandi default sistem. Siswa & guru dapat mengubah kata sandi mereka secara mandiri melalui tombol "Ubah Kata Sandi" di Dashboard atau tautan "Lupa kata sandi?".', 2],
            'B' => ['', 2], 'C' => ['', 2], 'D' => ['', 2], 'E' => ['', 2], 'F' => ['', 2], 'G' => ['', 2], 'H' => ['', 2],
        ];
        $heights[$summaryRow] = 24;

        $xml = self::XML_HEADER.'<worksheet xmlns="'.self::NS_MAIN.'">'
            .'<sheetViews><sheetView workbookViewId="0"><pane ySplit="4" topLeftCell="A5" activePane="bottomLeft" state="frozen"/>'
            .'<selection pane="bottomLeft" activeCell="A5" sqref="A5"/></sheetView></sheetViews>'
            .'<cols>'
            .'<col min="1" max="1" width="8" customWidth="1"/>'
            .'<col min="2" max="2" width="34" customWidth="1"/>'
            .'<col min="3" max="3" width="14" customWidth="1"/>'
            .'<col min="4" max="4" width="16" customWidth="1"/>'
            .'<col min="5" max="5" width="24" customWidth="1"/>'
            .'<col min="6" max="6" width="18" customWidth="1"/>'
            .'<col min="7" max="7" width="14" customWidth="1"/>'
            .'<col min="8" max="8" width="46" customWidth="1"/>'
            .'</cols>'
            .'<sheetData>'.self::rowsXml($rows, $heights).'</sheetData>'
            .'<mergeCells count="3">'
            .'<mergeCell ref="A1:H1"/>'
            .'<mergeCell ref="A2:H2"/>'
            .'<mergeCell ref="A'.$summaryRow.':H'.$summaryRow.'"/>'
            .'</mergeCells>'
            .'</worksheet>';

        return $xml;
    }

    /**
     * Build row XML with optional height formatting.
     */
    private static function rowsXml(array $rows, array $heights = []): string
    {
        $xml = '';
        foreach ($rows as $rowNumber => $row) {
            $h = isset($heights[$rowNumber]) ? ' ht="'.$heights[$rowNumber].'" customHeight="1"' : '';
            $xml .= '<row r="'.$rowNumber.'"'.$h.'>';
            foreach ($row as $column => [$value, $style]) {
                $ref = $column.$rowNumber;
                $xml .= '<c r="'.$ref.'" s="'.$style.'" t="inlineStr"><is><t>'.htmlspecialchars((string) ($value ?? ''), ENT_XML1, 'UTF-8').'</t></is></c>';
            }
            $xml .= '</row>';
        }

        return $xml;
    }

    /**
     * Stylesheet for the workbook matching MTsN 32 Jakarta branding.
     */
    private static function stylesXml(): string
    {
        return self::XML_HEADER.'<styleSheet xmlns="'.self::NS_MAIN.'">'
            .'<fonts count="6">'
            .'<font><sz val="10"/><name val="Segoe UI"/></font>' // 0: regular
            .'<font><b/><sz val="13"/><color rgb="FFFFFFFF"/><name val="Segoe UI"/></font>' // 1: title white bold
            .'<font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Segoe UI"/></font>' // 2: header white bold
            .'<font><i/><sz val="9"/><color rgb="FF475569"/><name val="Segoe UI"/></font>' // 3: subtitle slate
            .'<font><b/><sz val="10"/><color rgb="FF0B1B3D"/><name val="Consolas"/></font>' // 4: code / NISN bold navy
            .'<font><b/><sz val="10"/><color rgb="FF0284C7"/><name val="Consolas"/></font>' // 5: password bold sky
            .'</fonts>'
            .'<fills count="5">'
            .'<fill><patternFill patternType="none"/></fill>' // 0
            .'<fill><patternFill patternType="gray125"/></fill>' // 1
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF0B1B3D"/></patternFill></fill>' // 2: Deep Navy #0B1B3D
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFF8FAFC"/></patternFill></fill>' // 3: Zebra light slate #F8FAFC
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFF0F9FF"/></patternFill></fill>' // 4: Header accent light
            .'</fills>'
            .'<borders count="2">'
            .'<border/>' // 0: none
            .'<border>' // 1: thin border
            .'<left style="thin"><color rgb="FFE2E8F0"/></left>'
            .'<right style="thin"><color rgb="FFE2E8F0"/></right>'
            .'<top style="thin"><color rgb="FFE2E8F0"/></top>'
            .'<bottom style="thin"><color rgb="FFE2E8F0"/></bottom>'
            .'</border>'
            .'</borders>'
            .'<cellStyleXfs count="1"><xf/></cellStyleXfs>'
            .'<cellXfs count="12">'
            .'<xf/>' // 0: default
            .'<xf fontId="1" fillId="2" applyFont="1" applyFill="1"><alignment horizontal="center" vertical="center"/></xf>' // 1: Title (Navy)
            .'<xf fontId="3" applyFont="1"><alignment horizontal="left" vertical="center"/></xf>' // 2: Subtitle
            .'<xf fontId="2" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 3: Table Header
            .'<xf fontId="0" borderId="1" applyFont="1" applyBorder="1"><alignment horizontal="left" vertical="center"/></xf>' // 4: Data Regular
            .'<xf fontId="0" borderId="1" applyFont="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 5: Data Centered
            .'<xf fontId="4" borderId="1" applyFont="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 6: Data NISN Code
            .'<xf fontId="5" borderId="1" applyFont="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 7: Data Password
            .'<xf fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="left" vertical="center"/></xf>' // 8: Zebra Regular
            .'<xf fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 9: Zebra Centered
            .'<xf fontId="4" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 10: Zebra NISN Code
            .'<xf fontId="5" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 11: Zebra Password
            .'</cellXfs>'
            .'</styleSheet>';
    }
}

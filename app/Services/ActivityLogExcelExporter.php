<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use ZipArchive;

class ActivityLogExcelExporter
{
    private const XML_HEADER = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

    private const NS_MAIN = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    private const NS_REL = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    private const NS_PKG_REL = 'http://schemas.openxmlformats.org/package/2006/relationships';

    /**
     * Build the Excel (.xlsx) file containing activity logs and return the temporary file path.
     *
     * @param  Collection  $logs  Collection of ActivityLog models
     * @param  string  $filterDescription  Human-readable description of current filter.
     * @param  string  $exporterName  Name of user who exported the file.
     * @return string Path to the temporary .xlsx file.
     *
     * @throws Exception
     */
    public static function build(Collection $logs, string $filterDescription = 'Semua Aktivitas', string $exporterName = 'Administrator'): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new Exception('Ekstensi PHP ZipArchive tidak tersedia di server.');
        }

        $path = tempnam(sys_get_temp_dir(), 'actlog_');
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
            .'<sheets><sheet name="Log Aktivitas" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>');

        $zip->addFromString('xl/_rels/workbook.xml.rels', self::XML_HEADER
            .'<Relationships xmlns="'.self::NS_PKG_REL.'">'
            .'<Relationship Id="rId1" Type="'.self::NS_REL.'/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="'.self::NS_REL.'/styles" Target="styles.xml"/>'
            .'</Relationships>');

        $zip->addFromString('xl/worksheets/sheet1.xml', self::sheetXml($logs, $filterDescription, $exporterName));
        $zip->addFromString('xl/styles.xml', self::stylesXml());
        $zip->close();

        return $xlsxPath;
    }

    /**
     * Generate worksheet XML.
     */
    private static function sheetXml(Collection $logs, string $filterDescription, string $exporterName): string
    {
        $rows = [];
        $heights = [];

        // Row 1: Main Title
        $rows[1] = [
            'A' => ['LAPORAN REKAPITULASI LOG AKTIVITAS & INTERAKSI SISTEM', 1],
            'B' => ['', 1], 'C' => ['', 1], 'D' => ['', 1], 'E' => ['', 1],
            'F' => ['', 1], 'G' => ['', 1], 'H' => ['', 1], 'I' => ['', 1], 'J' => ['', 1],
        ];
        $heights[1] = 30;

        // Row 2: Subtitle
        $rows[2] = [
            'A' => ['Madrasah Tsanawiyah Negeri (MTsN) 32 Jakarta • Portal Web Resmi & Perpustakaan Digital Terpadu', 2],
            'B' => ['', 2], 'C' => ['', 2], 'D' => ['', 2], 'E' => ['', 2],
            'F' => ['', 2], 'G' => ['', 2], 'H' => ['', 2], 'I' => ['', 2], 'J' => ['', 2],
        ];
        $heights[2] = 20;

        // Row 3: Metadata Info
        $exportTime = now()->translatedFormat('d F Y, H:i:s').' WIB';
        $rows[3] = [
            'A' => ["Filter: {$filterDescription}   |   Waktu Unduh: {$exportTime}   |   Operator: {$exporterName}   |   Total Data: {$logs->count()} Catatan", 3],
            'B' => ['', 3], 'C' => ['', 3], 'D' => ['', 3], 'E' => ['', 3],
            'F' => ['', 3], 'G' => ['', 3], 'H' => ['', 3], 'I' => ['', 3], 'J' => ['', 3],
        ];
        $heights[3] = 20;

        // Row 4: Spacer
        $rows[4] = [
            'A' => ['', 0], 'B' => ['', 0], 'C' => ['', 0], 'D' => ['', 0], 'E' => ['', 0],
            'F' => ['', 0], 'G' => ['', 0], 'H' => ['', 0], 'I' => ['', 0], 'J' => ['', 0],
        ];
        $heights[4] = 8;

        // Row 5: Table Header
        $rows[5] = [
            'A' => ['NO', 4],
            'B' => ['WAKTU (WIB)', 4],
            'C' => ['KATEGORI & EVENT', 4],
            'D' => ['TIPE LOG', 4],
            'E' => ['PELAKU / PENGGUNA', 4],
            'F' => ['PERAN', 4],
            'G' => ['IDENTITAS / NO. INDUK', 4],
            'H' => ['AKTIVITAS YANG DILAKUKAN', 4],
            'I' => ['ALAMAT IP', 4],
            'J' => ['INFO PERANGKAT / BROWSER', 4],
        ];
        $heights[5] = 24;

        // Data Rows
        $no = 1;
        foreach ($logs as $log) {
            $rowNumber = $no + 5;
            $isZebra = ($no % 2 === 0);

            $styleText = $isZebra ? 9 : 5;
            $styleCenter = $isZebra ? 10 : 6;
            $styleCode = $isZebra ? 11 : 7;
            $styleBadge = $isZebra ? 12 : 8;

            $waktuFormatted = $log->created_at ? $log->created_at->translatedFormat('d/m/Y H:i:s') : '-';
            $tipeLabel = strtoupper($log->log_type ?? 'UMUM');
            $actorTypeLabel = strtoupper($log->actor_type ?? 'UMUM');
            $identitas = $log->actor_identifier && $log->actor_identifier !== '-' ? $log->actor_identifier : '-';

            $rows[$rowNumber] = [
                'A' => [$no, $styleCenter],
                'B' => [$waktuFormatted, $styleCenter],
                'C' => [$log->event_name ?? '-', $styleText],
                'D' => [$tipeLabel, $styleBadge],
                'E' => [$log->actor_name ?? 'Pengunjung Web', $styleText],
                'F' => [$actorTypeLabel, $styleCenter],
                'G' => [$identitas, $styleCode],
                'H' => [$log->description ?? '-', $styleText],
                'I' => [$log->ip_address ?? '127.0.0.1', $styleCenter],
                'J' => [$log->user_agent ?? 'Aplikasi Web', $styleText],
            ];

            $heights[$rowNumber] = 22;
            $no++;
        }

        // Summary Note Row
        $summaryRow = count($rows) + 2;
        $rows[$summaryRow] = [
            'A' => ['Catatan: Laporan ini dihasilkan secara otomatis oleh Pusat Monitoring Log Sistem MTsN 32 Jakarta sesuai dengan filter yang aktif saat diekspor.', 3],
            'B' => ['', 3], 'C' => ['', 3], 'D' => ['', 3], 'E' => ['', 3],
            'F' => ['', 3], 'G' => ['', 3], 'H' => ['', 3], 'I' => ['', 3], 'J' => ['', 3],
        ];
        $heights[$summaryRow] = 24;

        $xml = self::XML_HEADER.'<worksheet xmlns="'.self::NS_MAIN.'">'
            .'<sheetViews><sheetView workbookViewId="0"><pane ySplit="5" topLeftCell="A6" activePane="bottomLeft" state="frozen"/>'
            .'<selection pane="bottomLeft" activeCell="A6" sqref="A6"/></sheetView></sheetViews>'
            .'<cols>'
            .'<col min="1" max="1" width="6" customWidth="1"/>'
            .'<col min="2" max="2" width="22" customWidth="1"/>'
            .'<col min="3" max="3" width="26" customWidth="1"/>'
            .'<col min="4" max="4" width="16" customWidth="1"/>'
            .'<col min="5" max="5" width="28" customWidth="1"/>'
            .'<col min="6" max="6" width="16" customWidth="1"/>'
            .'<col min="7" max="7" width="22" customWidth="1"/>'
            .'<col min="8" max="8" width="55" customWidth="1"/>'
            .'<col min="9" max="9" width="18" customWidth="1"/>'
            .'<col min="10" max="10" width="38" customWidth="1"/>'
            .'</cols>'
            .'<sheetData>'.self::rowsXml($rows, $heights).'</sheetData>'
            .'<mergeCells count="4">'
            .'<mergeCell ref="A1:J1"/>'
            .'<mergeCell ref="A2:J2"/>'
            .'<mergeCell ref="A3:J3"/>'
            .'<mergeCell ref="A'.$summaryRow.':J'.$summaryRow.'"/>'
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
            .'<fonts count="7">'
            .'<font><sz val="10"/><name val="Segoe UI"/></font>' // 0: regular
            .'<font><b/><sz val="13"/><color rgb="FFFFFFFF"/><name val="Segoe UI"/></font>' // 1: title white bold
            .'<font><i/><sz val="9.5"/><color rgb="FF475569"/><name val="Segoe UI"/></font>' // 2: subtitle slate
            .'<font><sz val="9"/><color rgb="FF64748B"/><name val="Segoe UI"/></font>' // 3: metadata
            .'<font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Segoe UI"/></font>' // 4: table header white bold
            .'<font><b/><sz val="10"/><color rgb="FF01ABE8"/><name val="Segoe UI"/></font>' // 5: badge sky bold
            .'<font><b/><sz val="9.5"/><color rgb="FF0B1B3D"/><name val="Consolas"/></font>' // 6: code/id Consolas
            .'</fonts>'
            .'<fills count="5">'
            .'<fill><patternFill patternType="none"/></fill>' // 0
            .'<fill><patternFill patternType="gray125"/></fill>' // 1
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF0B1B3D"/></patternFill></fill>' // 2: Deep Navy #0B1B3D
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFF8FAFC"/></patternFill></fill>' // 3: Zebra light slate #F8FAFC
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFF0F9FF"/></patternFill></fill>' // 4: Soft Sky #F0F9FF
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
            .'<cellXfs count="13">'
            .'<xf/>' // 0: default
            .'<xf fontId="1" fillId="2" applyFont="1" applyFill="1"><alignment horizontal="center" vertical="center"/></xf>' // 1: Main Title
            .'<xf fontId="2" applyFont="1"><alignment horizontal="left" vertical="center"/></xf>' // 2: Subtitle
            .'<xf fontId="3" applyFont="1"><alignment horizontal="left" vertical="center"/></xf>' // 3: Metadata Info
            .'<xf fontId="4" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 4: Table Header
            .'<xf fontId="0" borderId="1" applyFont="1" applyBorder="1"><alignment horizontal="left" vertical="center"/></xf>' // 5: Regular Left
            .'<xf fontId="0" borderId="1" applyFont="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 6: Regular Center
            .'<xf fontId="6" borderId="1" applyFont="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 7: Code Center
            .'<xf fontId="5" borderId="1" applyFont="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 8: Badge Center
            .'<xf fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="left" vertical="center"/></xf>' // 9: Zebra Left
            .'<xf fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 10: Zebra Center
            .'<xf fontId="6" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 11: Zebra Code Center
            .'<xf fontId="5" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 12: Zebra Badge Center
            .'</cellXfs>'
            .'</styleSheet>';
    }
}

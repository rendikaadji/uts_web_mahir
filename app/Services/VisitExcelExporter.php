<?php

namespace App\Services;

use ZipArchive;

class VisitExcelExporter
{
    private const XML_HEADER = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

    private const NS_MAIN = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    private const NS_REL = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    private const NS_PKG_REL = 'http://schemas.openxmlformats.org/package/2006/relationships';

    public static function build($kunjungan, $periodLabel): string
    {
        $path = tempnam(sys_get_temp_dir(), 'visit_');
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
            .'<bookViews><workbookView activeTab="0"/></bookViews>'
            .'<sheets><sheet name="Data Anggota" sheetId="1" r:id="rId1"/><sheet name="Data Tamu" sheetId="2" r:id="rId2"/></sheets>'
            .'</workbook>');

        $zip->addFromString('xl/_rels/workbook.xml.rels', self::XML_HEADER
            .'<Relationships xmlns="'.self::NS_PKG_REL.'">'
            .'<Relationship Id="rId1" Type="'.self::NS_REL.'/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="'.self::NS_REL.'/worksheet" Target="worksheets/sheet2.xml"/>'
            .'<Relationship Id="rId3" Type="'.self::NS_REL.'/styles" Target="styles.xml"/>'
            .'</Relationships>');

        $zip->addFromString('xl/worksheets/sheet1.xml', self::sheet1($kunjungan));
        $zip->addFromString('xl/worksheets/sheet2.xml', self::sheet2($kunjungan));
        $zip->addFromString('xl/styles.xml', self::styles());
        $zip->close();

        return $path;
    }

    private static function sheet1($kunjungan): string
    {
        $anggota = $kunjungan->filter(fn ($v) => $v->visitor_type === 'anggota')->values();
        $rows = [
            1 => ['A' => ['LAPORAN PRESENSI ANGGOTA PERPUSTAKAAN', 1], 'B' => ['', 1], 'C' => ['', 1], 'D' => ['', 1], 'E' => ['', 1]],
            3 => ['A' => ['No', 1], 'B' => ['Nama Anggota', 1], 'C' => ['Kelas / Unit', 1], 'D' => ['Keperluan', 1], 'E' => ['Waktu Presensi', 1]],
        ];
        foreach ($anggota as $i => $v) {
            $row = $i + 4;
            $name = $v->member->user->name ?? '-';
            $rows[$row] = [
                'A' => [(string) ($i + 1), 2],
                'B' => [$name, 2],
                'C' => [$v->member->class_or_unit ?? '-', 2],
                'D' => [$v->purpose ?? 'Membaca / Literasi Mandiri', 2],
                'E' => [$v->visited_at->format('d/m/Y H:i'), 2],
            ];
        }

        $xml = self::XML_HEADER.'<worksheet xmlns="'.self::NS_MAIN.'">';
        $xml .= '<cols><col min="1" max="1" width="10" customWidth="1"/><col min="2" max="2" width="30" customWidth="1"/><col min="3" max="3" width="20" customWidth="1"/><col min="4" max="4" width="30" customWidth="1"/><col min="5" max="5" width="25" customWidth="1"/></cols>';
        $xml .= '<sheetData>'.self::rowsXml($rows).'</sheetData>';
        $xml .= '<mergeCells count="1"><mergeCell ref="A1:E1"/></mergeCells>';
        $xml .= '</worksheet>';

        return $xml;
    }

    private static function sheet2($kunjungan): string
    {
        $tamu = $kunjungan->filter(fn ($v) => $v->visitor_type === 'tamu')->values();
        $rows = [
            1 => ['A' => ['LAPORAN PRESENSI TAMU PERPUSTAKAAN', 1], 'B' => ['', 1], 'C' => ['', 1], 'D' => ['', 1], 'E' => ['', 1]],
            3 => ['A' => ['No', 1], 'B' => ['Nama Tamu', 1], 'C' => ['Asal Instansi', 1], 'D' => ['Keperluan', 1], 'E' => ['Waktu Presensi', 1]],
        ];
        foreach ($tamu as $i => $v) {
            $row = $i + 4;
            $rows[$row] = [
                'A' => [(string) ($i + 1), 2],
                'B' => [$v->guest_name ?? '-', 2],
                'C' => [$v->guest_institution ?? '-', 2],
                'D' => [$v->purpose ?? '-', 2],
                'E' => [$v->visited_at->format('d/m/Y H:i'), 2],
            ];
        }

        $xml = self::XML_HEADER.'<worksheet xmlns="'.self::NS_MAIN.'">';
        $xml .= '<cols><col min="1" max="1" width="10" customWidth="1"/><col min="2" max="2" width="30" customWidth="1"/><col min="3" max="3" width="30" customWidth="1"/><col min="4" max="4" width="30" customWidth="1"/><col min="5" max="5" width="25" customWidth="1"/></cols>';
        $xml .= '<sheetData>'.self::rowsXml($rows).'</sheetData>';
        $xml .= '<mergeCells count="1"><mergeCell ref="A1:E1"/></mergeCells>';
        $xml .= '</worksheet>';

        return $xml;
    }

    private static function rowsXml(array $rows): string
    {
        $xml = '';
        foreach ($rows as $rowNumber => $row) {
            $xml .= '<row r="'.$rowNumber.'">';
            foreach ($row as $column => [$value, $style]) {
                $ref = $column.$rowNumber;
                $xml .= '<c r="'.$ref.'" s="'.$style.'" t="inlineStr"><is><t>'.htmlspecialchars($value ?? '', ENT_XML1).'</t></is></c>';
            }
            $xml .= '</row>';
        }

        return $xml;
    }

    private static function styles(): string
    {
        return self::XML_HEADER.'<styleSheet xmlns="'.self::NS_MAIN.'">'
            .'<fonts count="3"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="12"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font><font><sz val="11"/><name val="Calibri"/></font></fonts>'
            .'<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF047857"/></patternFill></fill></fills>'
            .'<borders count="2"><border/><border><left style="thin"/><right style="thin"/><top style="thin"/><bottom style="thin"/></border></borders>'
            .'<cellStyleXfs count="1"><xf/></cellStyleXfs>'
            .'<cellXfs count="3">'
            .'<xf/>' // 0
            .'<xf fontId="1" fillId="2" applyFont="1" applyFill="1"><alignment horizontal="center" vertical="center"/></xf>' // 1 header (green)
            .'<xf fontId="0" borderId="1" applyFont="1" applyBorder="1"/>' // 2 body
            .'</cellXfs>'
            .'</styleSheet>';
    }
}

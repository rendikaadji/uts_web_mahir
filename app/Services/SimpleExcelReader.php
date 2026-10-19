<?php

namespace App\Services;

use Exception;
use XMLReader;
use ZipArchive;

class SimpleExcelReader
{
    /**
     * Read an Excel (.xlsx) or CSV (.csv) file into an array of rows.
     *
     * @return array<int, array<int, string>>
     */
    public static function read(string $filePath, ?string $extension = null): array
    {
        $ext = strtolower($extension ?: (string) pathinfo($filePath, PATHINFO_EXTENSION));

        if ($ext === 'csv' || $ext === 'txt') {
            return self::readCsv($filePath);
        }

        if ($ext === 'xlsx') {
            return self::readXlsx($filePath);
        }

        throw new Exception("Format file tidak didukung: .{$ext}. Gunakan format .xlsx atau .csv");
    }

    /**
     * Read CSV file with automatic delimiter detection (, or ; or \t).
     *
     * @return array<int, array<int, string>>
     */
    public static function readCsv(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        if (! $handle) {
            throw new Exception('Gagal membuka file CSV.');
        }

        // Detect BOM
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Detect delimiter from first line
        $firstLine = fgets($handle);
        rewind($handle);
        if ($bom === "\xEF\xBB\xBF") {
            fread($handle, 3);
        }

        $delimiter = ',';
        if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
            $delimiter = ';';
        } elseif (substr_count($firstLine, "\t") > substr_count($firstLine, ',')) {
            $delimiter = "\t";
        }

        $rows = [];
        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rows[] = array_map('trim', $data);
        }
        fclose($handle);

        return $rows;
    }

    /**
     * Read XLSX file via built-in ZipArchive and XMLReader.
     *
     * @return array<int, array<int, string>>
     */
    public static function readXlsx(string $filePath): array
    {
        $zip = new ZipArchive;
        if ($zip->open($filePath) !== true) {
            throw new Exception('Gagal membuka file XLSX. File mungkin rusak atau tidak valid.');
        }

        // 1. Read shared strings
        $sharedStrings = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXml !== false) {
            $reader = new XMLReader;
            $reader->XML($sharedXml);
            $currentString = '';
            $inT = false;

            while ($reader->read()) {
                if ($reader->nodeType === XMLReader::ELEMENT && $reader->name === 'si') {
                    $currentString = '';
                } elseif ($reader->nodeType === XMLReader::ELEMENT && $reader->name === 't') {
                    $inT = true;
                } elseif ($reader->nodeType === XMLReader::TEXT && $inT) {
                    $currentString .= $reader->value;
                } elseif ($reader->nodeType === XMLReader::END_ELEMENT && $reader->name === 't') {
                    $inT = false;
                } elseif ($reader->nodeType === XMLReader::END_ELEMENT && $reader->name === 'si') {
                    $sharedStrings[] = $currentString;
                }
            }
            $reader->close();
        }

        // 2. Read first worksheet
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXml === false) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (str_starts_with($name, 'xl/worksheets/sheet') && str_ends_with($name, '.xml')) {
                    $sheetXml = $zip->getFromIndex($i);
                    break;
                }
            }
        }
        $zip->close();

        if ($sheetXml === false) {
            throw new Exception('Worksheet tidak ditemukan dalam file Excel.');
        }

        $reader = new XMLReader;
        $reader->XML($sheetXml);

        $rows = [];
        $currentRow = [];
        $currentCellType = '';
        $currentCellCol = 0;
        $inValue = false;
        $cellValue = '';

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::ELEMENT) {
                if ($reader->name === 'row') {
                    $currentRow = [];
                    $currentCellCol = 0;
                } elseif ($reader->name === 'c') {
                    $currentCellType = $reader->getAttribute('t') ?? '';
                    $cellRef = $reader->getAttribute('r') ?? '';
                    if ($cellRef) {
                        preg_match('/^([A-Z]+)/', $cellRef, $matches);
                        if (! empty($matches[1])) {
                            $targetCol = self::columnLetterToIndex($matches[1]);
                            while ($currentCellCol < $targetCol) {
                                $currentRow[] = '';
                                $currentCellCol++;
                            }
                        }
                    }
                    $cellValue = '';
                } elseif ($reader->name === 'v' || $reader->name === 't') {
                    $inValue = true;
                }
            } elseif ($reader->nodeType === XMLReader::TEXT && $inValue) {
                $cellValue .= $reader->value;
            } elseif ($reader->nodeType === XMLReader::END_ELEMENT) {
                if ($reader->name === 'v' || $reader->name === 't') {
                    $inValue = false;
                } elseif ($reader->name === 'c') {
                    if ($currentCellType === 's') {
                        $idx = (int) $cellValue;
                        $finalVal = $sharedStrings[$idx] ?? '';
                    } else {
                        $finalVal = $cellValue;
                    }
                    $currentRow[] = trim($finalVal);
                    $currentCellCol++;
                } elseif ($reader->name === 'row') {
                    if (! empty(array_filter($currentRow, fn ($v) => $v !== ''))) {
                        $rows[] = $currentRow;
                    }
                }
            }
        }
        $reader->close();

        return $rows;
    }

    private static function columnLetterToIndex(string $letters): int
    {
        $index = 0;
        $len = strlen($letters);
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }

        return $index - 1;
    }
}

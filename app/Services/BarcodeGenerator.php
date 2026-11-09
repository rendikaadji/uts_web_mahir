<?php

namespace App\Services;

class BarcodeGenerator
{
    private static array $patterns = [
        '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212', '221213',
        '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211', '221132',
        '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112', '322211',
        '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311', '211313',
        '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121', '211331',
        '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311', '332111',
        '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221', '112214',
        '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112', '134111',
        '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211', '212141',
        '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311', '113141',
        '114131', '311141', '411131', '211412', '211214', '211232', '2331112',
    ];

    /**
     * Generate Code 128 (Subset B) SVG string.
     */
    public static function code128Svg(string $text, int $height = 48, int $moduleWidth = 2, string $color = '#0B1B3D'): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        // Start Code B is index 104
        $codes = [104];
        $sum = 104;

        for ($i = 0; $i < strlen($text); $i++) {
            $ascii = ord($text[$i]);
            // ASCII 32 to 127 corresponds to Code 128 B values 0 to 95
            $val = $ascii - 32;
            if ($val < 0 || $val > 95) {
                $val = 0; // fallback space
            }
            $codes[] = $val;
            $sum += $val * ($i + 1);
        }

        // Checksum modulo 103
        $checksum = $sum % 103;
        $codes[] = $checksum;

        // Stop character is index 106
        $codes[] = 106;

        // Build bars
        $totalModules = 0;
        $rects = [];
        $currentX = 0;

        foreach ($codes as $code) {
            $pattern = self::$patterns[$code] ?? self::$patterns[0];
            $isBar = true;
            for ($p = 0; $p < strlen($pattern); $p++) {
                $width = (int) $pattern[$p] * $moduleWidth;
                if ($isBar) {
                    $rects[] = sprintf('<rect x="%d" y="0" width="%d" height="%d" fill="%s" />', $currentX, $width, $height, $color);
                }
                $currentX += $width;
                $isBar = ! $isBar;
            }
        }

        $totalWidth = $currentX;

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" width="100%%" height="%d" style="display:block;margin:0 auto;max-width:%dpx;">%s</svg>',
            $totalWidth,
            $height,
            $height,
            $totalWidth,
            implode('', $rects)
        );
    }
}

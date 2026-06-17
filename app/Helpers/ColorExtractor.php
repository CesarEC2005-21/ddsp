<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ColorExtractor
{
    /**
     * Extrae los dos colores principales (primario y secundario) de una imagen,
     * ignorando el blanco o colores casi blancos.
     */
    public static function extractColors($imagePath)
    {
        // Generamos una clave de caché única basada en la ruta del archivo y su fecha de modificación
        $absolutePath = storage_path('app/public/' . $imagePath);
        
        if (!file_exists($absolutePath)) {
            return ['#da291c', '#b71c1c']; // Fallback (rojo por defecto)
        }

        $cacheKey = 'color_extractor_' . md5($imagePath . filemtime($absolutePath));

        return Cache::rememberForever($cacheKey, function () use ($absolutePath) {
            $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
            
            $img = null;
            if ($extension === 'png') {
                $img = @imagecreatefrompng($absolutePath);
            } elseif (in_array($extension, ['jpg', 'jpeg'])) {
                $img = @imagecreatefromjpeg($absolutePath);
            } elseif ($extension === 'webp') {
                $img = @imagecreatefromwebp($absolutePath);
            }

            if (!$img) {
                return ['#0055ff', '#0033a0']; // Fallback
            }

            // Redimensionar para procesar más rápido
            $width = imagesx($img);
            $height = imagesy($img);
            
            $thumbWidth = 50;
            $thumbHeight = 50;
            $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
            
            // Preservar transparencia
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
            imagefilledrectangle($thumb, 0, 0, $thumbWidth, $thumbHeight, $transparent);
            
            imagecopyresampled($thumb, $img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
            imagedestroy($img);

            $colors = [];
            for ($x = 0; $x < $thumbWidth; $x++) {
                for ($y = 0; $y < $thumbHeight; $y++) {
                    $colorIndex = imagecolorat($thumb, $x, $y);
                    $colorsRGBA = imagecolorsforindex($thumb, $colorIndex);
                    
                    // Ignorar transparente
                    if ($colorsRGBA['alpha'] > 100) continue;

                    $r = $colorsRGBA['red'];
                    $g = $colorsRGBA['green'];
                    $b = $colorsRGBA['blue'];

                    // Ignorar blancos y grises muy claros (fondos)
                    if ($r > 240 && $g > 240 && $b > 240) continue;
                    // Ignorar grises neutros si quieres enfocarte en colores vivos, pero por ahora solo blancos

                    // Agrupar colores similares redondeando a múltiplos de 16
                    $rRound = round($r / 16) * 16;
                    $gRound = round($g / 16) * 16;
                    $bRound = round($b / 16) * 16;
                    
                    $hex = sprintf("#%02x%02x%02x", min($rRound, 255), min($gRound, 255), min($bRound, 255));
                    
                    if (!isset($colors[$hex])) {
                        $colors[$hex] = 0;
                    }
                    $colors[$hex]++;
                }
            }
            imagedestroy($thumb);

            if (empty($colors)) {
                return ['#009EE3', '#65B32E']; // Fallback
            }

            arsort($colors);
            $topColors = array_keys($colors);
            
            $primary = $topColors[0];
            $secondary = isset($topColors[1]) ? $topColors[1] : $primary;

            // Si ambos colores son muy similares, intentamos buscar el 3ro
            if (count($topColors) > 2) {
                // Cálculo simple de distancia euclidiana
                list($r1, $g1, $b1) = sscanf($primary, "#%02x%02x%02x");
                list($r2, $g2, $b2) = sscanf($secondary, "#%02x%02x%02x");
                $dist = sqrt(pow($r1-$r2, 2) + pow($g1-$g2, 2) + pow($b1-$b2, 2));
                if ($dist < 50) {
                    $secondary = $topColors[2];
                }
            }

            return [$primary, $secondary];
        });
    }
}

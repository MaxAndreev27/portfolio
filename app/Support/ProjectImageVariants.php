<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ProjectImageVariants
{
    private const WIDTHS = [480, 768, 1200];

    private const QUALITY = 80;

    public static function generate(string $imagePath): void
    {
        if (! function_exists('imagewebp')) {
            throw new RuntimeException('GD with WebP support is required to generate project image variants.');
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($imagePath)) {
            throw new RuntimeException("Project image [{$imagePath}] does not exist.");
        }

        $source = @imagecreatefromstring($disk->get($imagePath));

        if (! $source instanceof \GdImage) {
            throw new RuntimeException("Project image [{$imagePath}] could not be decoded.");
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);

        try {
            foreach (self::WIDTHS as $width) {
                if ($width >= $sourceWidth) {
                    continue;
                }

                $height = (int) round($sourceHeight * $width / $sourceWidth);
                $resized = imagecreatetruecolor($width, $height);

                if (! $resized instanceof \GdImage) {
                    throw new RuntimeException("Could not allocate a {$width}px project image variant.");
                }

                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                imagefilledrectangle($resized, 0, 0, $width, $height, $transparent);
                imagecopyresampled(
                    $resized,
                    $source,
                    0,
                    0,
                    0,
                    0,
                    $width,
                    $height,
                    $sourceWidth,
                    $sourceHeight,
                );

                ob_start();
                $encoded = imagewebp($resized, null, self::QUALITY);
                $contents = ob_get_clean();
                imagedestroy($resized);

                if (! $encoded || $contents === false || $contents === '') {
                    throw new RuntimeException("Could not encode the {$width}px project image variant as WebP.");
                }

                $variantPath = self::variantPath($imagePath, $width);

                if (! $disk->put($variantPath, $contents)) {
                    throw new RuntimeException("Could not save project image variant [{$variantPath}].");
                }
            }
        } finally {
            imagedestroy($source);
        }
    }

    public static function srcset(string $imagePath): ?string
    {
        $disk = Storage::disk('public');
        $variants = [];

        foreach (self::WIDTHS as $width) {
            $variantPath = self::variantPath($imagePath, $width);

            if ($disk->exists($variantPath)) {
                $variants[] = asset('storage/'.$variantPath).' '.$width.'w';
            }
        }

        return $variants === [] ? null : implode(', ', $variants);
    }

    public static function delete(string $imagePath): void
    {
        $disk = Storage::disk('public');
        $paths = array_map(
            fn (int $width): string => self::variantPath($imagePath, $width),
            self::WIDTHS,
        );

        $disk->delete($paths);
    }

    private static function variantPath(string $imagePath, int $width): string
    {
        $filename = hash('sha256', $imagePath)."-{$width}.webp";
        $directory = dirname($imagePath);
        $prefix = $directory === '.' ? '' : $directory.'/';

        return $prefix.'_variants/'.$filename;
    }
}

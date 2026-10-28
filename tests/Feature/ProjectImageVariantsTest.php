<?php

use App\Support\ProjectImageVariants;
use Illuminate\Support\Facades\Storage;

it('generates responsive WebP variants without changing the source image', function () {
    Storage::fake('public');

    $source = imagecreatetruecolor(1600, 900);
    ob_start();
    imagepng($source);
    $contents = ob_get_clean();
    imagedestroy($source);

    Storage::disk('public')->put('projects/source.png', $contents);

    ProjectImageVariants::generate('projects/source.png');

    $variantFiles = Storage::disk('public')->allFiles('projects/_variants');
    $variantWidths = array_map(function (string $path): int {
        $image = imagecreatefromstring(Storage::disk('public')->get($path));
        $width = imagesx($image);
        imagedestroy($image);

        return $width;
    }, $variantFiles);
    sort($variantWidths);
    $srcset = ProjectImageVariants::srcset('projects/source.png');

    expect(Storage::disk('public')->exists('projects/source.png'))->toBeTrue()
        ->and($variantWidths)->toBe([480, 768, 1200])
        ->and($srcset)->toContain('480w')
        ->and($srcset)->toContain('768w')
        ->and($srcset)->toContain('1200w');
});

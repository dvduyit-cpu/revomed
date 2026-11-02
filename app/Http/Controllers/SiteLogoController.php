<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SiteLogoController extends Controller
{
    public function __invoke(): BinaryFileResponse
    {
        $path = SiteSetting::values()['site_logo'] ?? '';
        abort_unless(is_string($path) && $path !== '' && ! str_contains($path, '..') && ! str_contains($path, '\\') && ! str_starts_with($path, '/'), 404);

        $disk = Storage::disk('public');
        abort_unless($disk->exists($path), 404);
        $file = $disk->path($path);
        $image = @getimagesize($file);
        abort_unless($image && in_array($image[2], [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_WEBP], true), 404);

        return response()->file($file, [
            'Content-Type' => $image['mime'],
            'Cache-Control' => 'no-cache, public',
        ]);
    }
}

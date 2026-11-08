<?php

namespace App\Support;

class AssetVersion
{
    public static function get(string $path): int
    {
        $file = public_path($path);

        // During hosting updates, views and public assets may arrive separately.
        // A missing stylesheet should return a 404, not prevent HTML rendering.
        if (! is_file($file)) {
            return 0;
        }

        return (int) @filemtime($file);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PublicAssetVersionTest extends TestCase
{
    use RefreshDatabase;

    public function test_missing_new_stylesheet_does_not_turn_public_pages_into_server_errors(): void
    {
        $originalPublicPath = public_path();
        $temporaryPublicPath = storage_path('framework/testing/public-assets-'.uniqid());
        File::ensureDirectoryExists($temporaryPublicPath);
        File::copyDirectory($originalPublicPath.'/css', $temporaryPublicPath.'/css');
        File::copyDirectory($originalPublicPath.'/js', $temporaryPublicPath.'/js');
        File::delete($temporaryPublicPath.'/css/medical-portfolio.css');

        try {
            $this->app->usePublicPath($temporaryPublicPath);
            $this->get('/')->assertOk()->assertSee('css/medical-portfolio.css?v=0', false);
            foreach (['/gioi-thieu', '/doi-ngu', '/tin-tuc', '/tuyen-dung', '/lien-he', '/admin/login'] as $path) {
                $this->get($path)->assertOk();
            }
        } finally {
            $this->app->usePublicPath($originalPublicPath);
            File::deleteDirectory($temporaryPublicPath);
        }
    }
}

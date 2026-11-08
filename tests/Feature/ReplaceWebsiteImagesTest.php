<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\SiteSetting;
use App\Support\TeamProducts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReplaceWebsiteImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_replacement_updates_nested_product_and_article_images_before_purging_old_media(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $disk = Storage::disk('public');
        foreach (['media/old.jpg', 'branding/logo.jpg', 'media/current-logo.jpg', 'documents/catalogue.pdf'] as $path) {
            $disk->put($path, 'existing file');
        }
        SiteSetting::putMany([
            'site_logo' => 'media/current-logo.jpg',
            'home_title_vi' => 'Preserve this heading',
            'home_hero_image' => 'media/old.jpg',
            TeamProducts::PREFIX.'stent' => json_encode([
                'name' => 'Stent mạch vành', 'is_published' => 0, 'sort_order' => 9,
                'gallery_paths' => ['media/old.jpg'],
            ]),
        ]);
        $post = News::create([
            'slug' => 'image-replacement', 'title_vi' => 'Existing article', 'excerpt_vi' => 'Existing excerpt',
            'content_vi' => '<img src="/storage/media/old.jpg">', 'status' => 'draft',
            'cover_image' => 'media/old.jpg',
        ]);

        $this->artisan('website:replace-images', ['--purge-old-images' => true])->assertSuccessful();

        $disk->assertMissing('media/old.jpg');
        foreach (['branding/logo.jpg', 'media/current-logo.jpg', 'documents/catalogue.pdf'] as $path) {
            $disk->assertExists($path);
        }
        $this->assertSame('media/current-logo.jpg', SiteSetting::values()['site_logo']);
        $this->assertSame('Preserve this heading', SiteSetting::values()['home_title_vi']);
        $product = TeamProducts::find('stent');
        $this->assertSame(['media/ai/coronary-stent.webp'], $product['gallery_paths']);
        $this->assertSame(0, $product['is_published']);
        $this->assertSame(9, $product['sort_order']);
        $this->assertStringContainsString('/storage/media/ai/research-laboratory.webp', $post->fresh()->content_vi);
        $this->assertSame('draft', $post->fresh()->status);
        $this->assertCount(6, $disk->allFiles('media/ai'));
        foreach ($disk->allFiles('media/ai') as $path) {
            $this->assertNotFalse(getimagesize($disk->path($path)));
        }
    }

    public function test_install_without_purge_keeps_old_files_and_repeat_install_has_no_duplicate_products(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        Storage::disk('public')->put('media/old.png', 'old image');
        $this->artisan('website:replace-images')->assertSuccessful();
        $this->artisan('website:replace-images')->assertSuccessful();
        Storage::disk('public')->assertExists('media/old.png');
        $this->assertCount(6, Storage::disk('public')->allFiles('media/ai'));
        $this->assertCount(0, TeamProducts::all());
    }
}

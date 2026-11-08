<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\SiteSetting;
use App\Support\TeamProducts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RewriteWebsiteCopyTest extends TestCase
{
    use RefreshDatabase;

    public function test_rewrite_publishes_bilingual_copy_while_preserving_images_business_details_and_article_identity(): void
    {
        Storage::fake('local');
        $this->seed(\Database\Seeders\WebsiteSeeder::class);
        SiteSetting::putMany([
            'home_title_vi' => 'Old heading',
            'company_phone' => '0123456789',
            'company_address_vi' => 'Existing address',
            'home_hero_image' => 'media/ai/heart-hero.webp',
            'home_title_vi_alignment' => 'center',
            'web_primary_color' => '#123456',
            TeamProducts::PREFIX.'stent' => json_encode([
                'name' => 'Stent', 'description' => 'Old description', 'is_published' => 0,
                'sort_order' => 8, 'gallery_paths' => ['media/ai/coronary-stent.webp'],
            ]),
        ]);
        $post = News::first();
        $post->update(['title_vi' => 'Old article title', 'cover_image' => 'media/ai/research-team.webp', 'status' => 'draft']);
        $originalSlug = $post->slug;
        $originalDate = $post->published_at;

        $this->artisan('website:rewrite-copy')->assertSuccessful();
        $this->artisan('website:rewrite-copy')->assertSuccessful();

        $settings = SiteSetting::values();
        $this->assertSame(config('revomed-content.home_title_vi'), $settings['home_title_vi']);
        $this->assertSame(config('revomed-content.home_title_en'), $settings['home_title_en']);
        foreach (['company_phone' => '0123456789', 'company_address_vi' => 'Existing address',
            'home_hero_image' => 'media/ai/heart-hero.webp', 'home_title_vi_alignment' => 'center',
            'web_primary_color' => '#123456'] as $key => $value) {
            $this->assertSame($value, $settings[$key]);
        }
        $product = TeamProducts::find('stent');
        $this->assertSame(config('revomed-products.stent.description'), $product['description']);
        $this->assertSame(['media/ai/coronary-stent.webp'], $product['gallery_paths']);
        $this->assertSame(0, $product['is_published']);
        $this->assertSame(8, $product['sort_order']);
        $this->assertNotSame('Old article title', $post->fresh()->title_vi);
        $this->assertSame($originalSlug, $post->fresh()->slug);
        $this->assertEquals($originalDate, $post->fresh()->published_at);
        $this->assertSame('draft', $post->fresh()->status);
        $this->assertSame('media/ai/research-team.webp', $post->fresh()->cover_image);
        $this->assertDatabaseCount('news', 3);
        $this->assertCount(1, TeamProducts::all());
        $this->assertCount(2, Storage::disk('local')->allFiles('deployment/backups'));
    }
}

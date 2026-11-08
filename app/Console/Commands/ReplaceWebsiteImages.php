<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\News;
use App\Models\SiteSetting;
use App\Support\TeamProducts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReplaceWebsiteImages extends Command
{
    protected $signature = 'website:replace-images {--purge-old-images : Delete replaced raster images from the public media library, preserving branding}';

    protected $description = 'Install the AI medical image collection and replace website image selections';

    public function handle(): int
    {
        $disk = Storage::disk('public');
        $names = ['heart-hero', 'coronary-stent', 'balloon-catheter', 'coronary-accessories', 'research-laboratory', 'research-team'];
        $paths = [];
        foreach ($names as $name) {
            $source = public_path('images/ai/'.$name.'.webp');
            if (! is_file($source)) {
                $this->error('Missing AI image: '.$source);

                return self::FAILURE;
            }
            $paths[$name] = 'media/ai/'.$name.'.webp';
        }

        $saved = SiteSetting::values();
        $oldImages = collect($disk->allFiles())->filter(fn ($path) =>
            preg_match('/\.(png|jpe?g|webp|gif|avif)$/i', $path)
            && ! str_starts_with($path, 'branding/')
            && $path !== ($saved['site_logo'] ?? '')
            && ! in_array($path, $paths, true)
        )->values()->all();

        // Save metadata before replacing references; old image binaries are not retained.
        $backup = 'deployment/backups/images-'.now()->format('Ymd-His-u').'.json';
        if (! Storage::disk('local')->put($backup, json_encode([
            'settings' => $saved,
            'news' => News::all()->toArray(),
            'jobs' => Job::all()->toArray(),
            'removed_image_paths' => $oldImages,
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR))) {
            $this->error('Could not save metadata backup. No images were replaced.');

            return self::FAILURE;
        }
        foreach ($paths as $name => $path) {
            if (! $disk->put($path, file_get_contents(public_path('images/ai/'.$name.'.webp')))) {
                $this->error('Could not install '.$path);

                return self::FAILURE;
            }
        }

        DB::transaction(function () use ($paths, $saved, $oldImages): void {
            $settings = [];
            foreach (config('page-content', []) as $fields) {
                foreach ($fields as $key => $field) {
                    if ($field[1] === 'image') {
                        $settings[$key] = $paths['research-laboratory'];
                    }
                }
            }
            $settings = array_replace($settings, [
                'home_hero_image' => $paths['heart-hero'],
                'culture_image' => $paths['research-laboratory'],
                'about_hero_image' => $paths['heart-hero'],
                'about_story_image' => $paths['research-laboratory'],
                'about_team_background_image' => $paths['research-team'],
                'team_portrait_image' => $paths['research-team'],
                'team_group_image' => $paths['research-team'],
                'home_journey_background_image' => $paths['research-team'],
                'careers_hero_image' => $paths['research-team'],
                'team_product_image_1' => $paths['coronary-stent'],
                'team_product_image_2' => $paths['balloon-catheter'],
                'team_product_image_3' => $paths['coronary-accessories'],
            ]);

            // Replace legacy inline media references as well as selected image fields.
            foreach ($saved as $key => $value) {
                if ($key !== 'site_logo' && ! isset($settings[$key]) && is_string($value)) {
                    $updated = str_replace($oldImages, $paths['research-laboratory'], $value);
                    if ($updated !== $value) {
                        $settings[$key] = $updated;
                    }
                }
            }
            foreach (TeamProducts::all() as $product) {
                $name = mb_strtolower(($product['name_en'] ?? '').' '.$product['name']);
                $image = str_contains($name, 'stent') ? 'coronary-stent'
                    : ((str_contains($name, 'balloon') || str_contains($name, 'bóng')) ? 'balloon-catheter' : 'coronary-accessories');
                $id = $product['id'];
                unset($product['id']);
                $product['image_path'] = $paths[$image];
                $product['gallery_paths'] = [$paths[$image]];
                $settings[TeamProducts::PREFIX.$id] = json_encode($product, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            }
            SiteSetting::putMany($settings);

            foreach (News::orderBy('id')->get() as $index => $post) {
                $post->cover_image = $paths[['research-laboratory', 'coronary-stent', 'research-team'][$index % 3]];
                foreach (['content_vi', 'content_en'] as $field) {
                    if (is_string($post->$field)) {
                        $post->$field = str_replace($oldImages, $paths['research-laboratory'], $post->$field);
                    }
                }
                $post->save();
            }
            Job::query()->update(['image_path' => $paths['research-team']]);
        });

        if ($this->option('purge-old-images')) {
            foreach ($oldImages as $path) {
                if (! $disk->delete($path)) {
                    $this->error('Could not delete old image: '.$path);

                    return self::FAILURE;
                }
            }
            $this->info('Deleted '.count($oldImages).' old media images. Branding preserved.');
        }
        $this->info('Installed 6 AI images; updated page images, products, news and jobs.');

        return self::SUCCESS;
    }
}

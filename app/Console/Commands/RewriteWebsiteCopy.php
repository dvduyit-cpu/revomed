<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\SiteSetting;
use App\Support\TeamProducts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RewriteWebsiteCopy extends Command
{
    protected $signature = 'website:rewrite-copy';

    protected $description = 'Publish bilingual REVOMED editorial copy, preserving images and business details';

    public function handle(): int
    {
        $copy = collect(require config_path('revomed-editorial.php'))
            ->filter(fn ($value, $key) => $key === 'site_description'
                || (preg_match('/_(vi|en)$/', $key) && ! str_starts_with($key, 'company_')))
            ->all();
        $saved = SiteSetting::values();
        $stories = config('revomed-stories', []);
        $backup = 'deployment/backups/editorial-'.now()->format('Ymd-His-u').'.json';
        if (! Storage::disk('local')->put($backup, json_encode([
            'settings' => $saved,
            'news' => News::whereIn('slug', array_column($stories, 'slug'))->get()->toArray(),
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR))) {
            $this->error('Could not save the previous copy. No content was changed.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($copy, $stories): void {
            SiteSetting::putMany($copy);
            foreach ($stories as $story) {
                $slug = $story['slug'];
                unset($story['slug']);
                News::where('slug', $slug)->update($story);
            }
            foreach (TeamProducts::all() as $product) {
                $name = mb_strtolower($product['name'].' '.($product['name_en'] ?? ''));
                $category = match (true) {
                    str_contains($name, 'stent') => 'stent',
                    str_contains($name, 'balloon'), str_contains($name, 'bóng nong') => 'balloon',
                    str_contains($name, 'accessor'), str_contains($name, 'dụng cụ can thiệp') => 'accessories',
                    default => null,
                };
                if ($category === null) {
                    continue;
                }
                $id = $product['id'];
                unset($product['id']);
                SiteSetting::putMany([TeamProducts::PREFIX.$id => json_encode(
                    array_replace($product, config('revomed-products.'.$category)),
                    JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
                )]);
            }
        });
        $this->info('Published Vietnamese and English page copy, product descriptions and existing REVOMED articles.');

        return self::SUCCESS;
    }
}

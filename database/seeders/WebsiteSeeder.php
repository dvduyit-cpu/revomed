<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\SiteSetting;
use App\Support\PageContent;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PageContent::defaults() as $key => $value) {
            SiteSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        foreach (array_values(config('revomed-stories', [])) as $index => $story) {
            News::firstOrCreate(['slug' => $story['slug']], $story + [
                'status' => 'published',
                'published_at' => now()->subMinutes($index),
            ]);
        }

        // Vacancies must be entered and published by an administrator, not invented by a seeder.
    }
}

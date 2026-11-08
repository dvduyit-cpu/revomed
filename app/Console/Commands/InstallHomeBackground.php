<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class InstallHomeBackground extends Command
{
    protected $signature = 'website:install-home-background';

    protected $description = 'Install and select the light vascular anatomy background for the homepage';

    public function handle(): int
    {
        $source = public_path('images/ai/vascular-anatomy-hero.webp');
        $path = 'media/ai/vascular-anatomy-hero.webp';
        if (! is_file($source)) {
            $this->error('Missing bundled image: '.$source);

            return self::FAILURE;
        }
        if (! Storage::disk('public')->put($path, file_get_contents($source))) {
            $this->error('Could not save the new homepage image. The current selection was preserved.');

            return self::FAILURE;
        }
        SiteSetting::putMany(['home_hero_image' => $path]);
        $this->info('Installed the vascular anatomy homepage background. Other images and content were preserved.');

        return self::SUCCESS;
    }
}

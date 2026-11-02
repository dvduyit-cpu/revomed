<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fresh installs use PageContent defaults; migrate only existing editorial data.
        if (!DB::table('site_settings')->exists()) {
            return;
        }
        DB::transaction(function () {
            foreach (DB::table('site_settings')->get() as $setting) {
                // Preserve uploaded files and contact details; rebrand editorial text only.
                if (str_contains($setting->key, 'image') || str_contains($setting->key, 'logo') || str_contains($setting->key, 'email')) {
                    continue;
                }
                $value = str_replace(
                    ['HOANG THUOC MEDICAL', 'Hoang Thuoc Medical', 'Hoàng Thuộc Medical', 'HOÀNG THUỘC', 'Hoàng Thuộc', 'HOANG THUOC', 'Hoang Thuoc'],
                    'REVOMED',
                    $setting->value ?? ''
                );
                if ($value !== $setting->value) {
                    DB::table('site_settings')->where('id', $setting->id)->update(['value' => $value]);
                }
            }
            foreach (config('revomed-content') as $key => $value) {
                DB::table('site_settings')->updateOrInsert(['key' => $key], ['value' => $value, 'updated_at' => now()]);
            }
            $logo = DB::table('site_settings')->where('key', 'site_logo')->value('value');
            if (!$logo || str_contains($logo, 'hoang-thuoc')) {
                DB::table('site_settings')->updateOrInsert(['key' => 'site_logo'], ['value' => '', 'updated_at' => now()]);
            }
        });
    }

    public function down(): void
    {
        // Keep editorial content: a rollback must not overwrite subsequent admin edits.
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::table('site_settings')->exists()) {
            return;
        }

        $legacyJobs = ['chuyen-vien-tu-van-duoc', 'dieu-duong-phong-kham', 'nhan-vien-kinh-doanh-thiet-bi-y-te'];
        $backupDirectory = storage_path('app/private/content-backups');
        File::ensureDirectoryExists($backupDirectory);
        File::put($backupDirectory.'/before-original-revomed-'.now()->format('YmdHis').'.json', json_encode([
            'site_settings' => DB::table('site_settings')->get(),
            'news' => DB::table('news')->whereIn('slug', array_keys(config('revomed-stories')))->get(),
            'jobs' => DB::table('jobs')->whereIn('slug', $legacyJobs)->get(),
            'products' => DB::table('site_settings')->where('key', 'like', 'team_product_item_%')->get(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        DB::transaction(function () use ($legacyJobs) {
            foreach (config('revomed-editorial') as $key => $value) {
                DB::table('site_settings')->updateOrInsert(['key' => $key], ['value' => $value, 'updated_at' => now()]);
            }
            // These are the three copied seed articles, not user-authored news.
            foreach (config('revomed-stories') as $oldSlug => $story) {
                if (!DB::table('news')->where('slug', $story['slug'])->exists()) {
                    DB::table('news')->where('slug', $oldSlug)->update($story + ['published_at' => now(), 'updated_at' => now()]);
                }
            }
            // Keep applications and original records, but do not advertise inherited vacancies.
            DB::table('jobs')->whereIn('slug', $legacyJobs)
                ->where('contact_email', 'like', '%hoangthuocmedical.com')
                ->update(['status' => 'draft', 'contact_email' => '', 'updated_at' => now()]);

            $productCopy = [
                'qrxbrSDPn3YqfbVoiVoZEdgLnyDUmZjFuC9B8LAj.jpg' => ['Dụng cụ can thiệp tim mạch', 'Cardiovascular intervention accessories', 'Tìm hiểu nhóm dụng cụ và các thông tin đi kèm danh mục. Trao đổi với REVOMED về sản phẩm cụ thể, tài liệu cần đối chiếu và câu hỏi của bạn.', 'Explore accessory categories and supporting information. Discuss a specific product, relevant documentation and your questions with REVOMED.'],
                'sGupkj4uYmbxf0GXFRlUqoF5qGSeqNYVTy2GTA5c.jpg' => ['Bóng nong mạch vành', 'Coronary balloon catheters', 'Khám phá nhóm sản phẩm bóng nong trong danh mục hiện có. Bắt đầu cuộc trao đổi bằng yêu cầu về thông tin sản phẩm và tài liệu của nhà sản xuất.', 'Explore balloon catheter products in the current portfolio. Start a conversation about product information and manufacturer documentation.'],
                'kEMepYr7fzFxXKw565wbb5OFz39eWHwRqwJauBGq.jpg' => ['Stent mạch vành', 'Coronary stents', 'Tìm hiểu danh mục stent mạch vành và các nội dung cần làm rõ trước khi trao đổi chuyên sâu. Liên hệ REVOMED để đặt câu hỏi về từng sản phẩm.', 'Explore the coronary stent portfolio and the points to clarify before a detailed discussion. Contact REVOMED with questions about individual products.'],
            ];
            foreach (DB::table('site_settings')->where('key', 'like', 'team_product_item_%')->get() as $setting) {
                $product = json_decode($setting->value, true);
                if (!is_array($product) || ($product['name'] ?? '') !== 'Giải pháp y khoa') {
                    continue;
                }
                $image = basename(str_replace('\\', '/', $product['image_path'] ?? ($product['gallery_paths'][0] ?? '')));
                if (isset($productCopy[$image])) {
                    [$product['name'], $product['name_en'], $product['description'], $product['description_en']] = $productCopy[$image];
                    DB::table('site_settings')->where('id', $setting->id)->update(['value' => json_encode($product, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), 'updated_at' => now()]);
                }
            }
        });
    }

    public function down(): void
    {
        // Editorial data remains editable. The private snapshot retains the previous content.
    }
};

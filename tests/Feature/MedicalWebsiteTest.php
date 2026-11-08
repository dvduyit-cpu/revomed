<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MedicalWebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_revised_public_pages_render_and_link_to_the_team(): void
    {
        $this->get('/')->assertOk()->assertSee('Kết nối công nghệ.')->assertSee(route('team'));
        $this->get('/gioi-thieu')->assertOk()->assertSee('Chuyên tâm công nghệ.');
        $this->get('/doi-ngu')->assertOk()->assertSee('Kết nối chuyên môn.')->assertSee('ecg-timeline');
        $this->get('/lien-he')->assertOk()->assertSee('Sản phẩm &amp; Giải pháp', false)
            ->assertDontSee('maps/search')->assertSee('name="subject"', false);
    }

    public function test_revomed_seeding_uses_original_stories_and_leaves_business_details_blank(): void
    {
        $this->seed(\Database\Seeders\WebsiteSeeder::class);
        $this->assertDatabaseCount('jobs', 0);
        $this->assertDatabaseCount('news', 3);
        foreach (['company_email', 'company_phone', 'company_tax_id', 'company_address_vi', 'company_address_en'] as $key) {
            $this->assertDatabaseHas('site_settings', ['key' => $key, 'value' => '']);
        }
        foreach (['/', '/gioi-thieu', '/doi-ngu', '/tuyen-dung', '/tin-tuc', '/lien-he'] as $path) {
            $this->get($path)->assertOk()->assertDontSee('Hoàng Thuộc')->assertDontSee('Hoang Thuoc')
                ->assertDontSee('hoangthuocmedical.com')->assertDontSee('0939 481 678')->assertDontSee('672/12');
        }
        foreach (config('revomed-stories') as $story) {
            $this->get('/tin-tuc/'.$story['slug'])->assertOk()->assertSee($story['title_vi'])
                ->assertDontSee('Hoang Thuoc');
        }
        \App\Models\SiteSetting::putMany(['home_title_vi' => 'Nội dung REVOMED đã biên tập']);
        $this->seed(\Database\Seeders\WebsiteSeeder::class);
        $this->assertDatabaseCount('news', 3);
        $this->get('/')->assertSee('Nội dung REVOMED đã biên tập');
    }

    public function test_contact_topic_is_delivered_to_the_contact_mailer(): void
    {
        Mail::fake();
        $this->from('/lien-he')->post('/lien-he', [
            'name' => 'Đối tác', 'email' => 'partner@example.com',
            'phone' => '0939481678', 'subject' => 'Sản phẩm & Giải pháp',
            'message' => 'Tôi muốn tìm hiểu giải pháp can thiệp tim mạch.',
        ])->assertRedirect('/lien-he')->assertSessionHasNoErrors()->assertSessionHas('success');
        Mail::assertSent(ContactMessageReceived::class);
    }
}

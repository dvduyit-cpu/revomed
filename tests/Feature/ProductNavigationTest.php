<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Support\TeamProducts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_menu_category_has_a_working_destination_in_both_languages(): void
    {
        $home = $this->get('/')->assertOk()->assertSee('rm-products-menu');
        foreach (config('product-categories') as $slug => $category) {
            $home->assertSee(route('products.show', $slug))->assertSee($category['name']);
            $this->get('/san-pham/'.$slug)->assertOk()->assertSee('<h1>'.$category['name'].'</h1>', false);
            $this->withSession(['locale' => 'en'])->get('/san-pham/'.$slug)
                ->assertOk()->assertSee('<h1>'.$category['name_en'].'</h1>', false);
            $this->withSession(['locale' => 'vi']);
        }
        $this->get('/san-pham/unknown-category')->assertNotFound();
    }

    public function test_category_pages_only_display_relevant_published_products(): void
    {
        foreach ([
            'coronary' => ['name' => 'Stent mạch vành', 'is_published' => 1],
            'accessory' => ['name' => 'Dụng cụ can thiệp', 'is_published' => 1],
            'draft' => ['name' => 'Bóng nong mạch vành bản nháp', 'is_published' => 0],
            'neuro' => ['name' => 'Thiết bị thử nghiệm theo nhóm', 'category' => 'can-thiep-mach-nao', 'is_published' => 1],
        ] as $id => $product) {
            SiteSetting::putMany([TeamProducts::PREFIX.$id => json_encode($product)]);
        }
        $this->get('/san-pham/can-thiep-mach-vanh')->assertOk()->assertSee('<h3>Stent mạch vành</h3>', false)
            ->assertDontSee('<h3>Dụng cụ can thiệp</h3>', false)->assertDontSee('Bóng nong mạch vành bản nháp');
        $this->get('/san-pham/can-thiep-mach-nao')->assertOk()->assertSee('Thiết bị thử nghiệm theo nhóm')
            ->assertDontSee('<h3>Stent mạch vành</h3>', false);
    }
}

<?php

namespace App\Http\Controllers;

use App\Support\TeamProducts;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $category): View
    {
        $categories = config('product-categories');
        abort_unless(isset($categories[$category]), 404);

        $products = TeamProducts::all()->where('is_published', 1)->filter(function ($product) use ($category) {
            if (filled($product['category'] ?? null)) {
                return $product['category'] === $category;
            }
            // Existing catalogue entries predate categories; only map known names.
            $name = mb_strtolower($product['name'].' '.($product['name_en'] ?? ''));

            return match ($category) {
                'can-thiep-mach-vanh' => str_contains($name, 'coronary stent') || str_contains($name, 'stent mạch vành')
                    || str_contains($name, 'balloon') || str_contains($name, 'bóng nong mạch vành'),
                'phu-kien-can-thiep' => str_contains($name, 'accessor') || str_contains($name, 'dụng cụ can thiệp'),
                default => false,
            };
        });

        return view('pages.products', compact('category', 'categories', 'products'));
    }
}

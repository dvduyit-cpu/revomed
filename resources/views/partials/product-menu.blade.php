<details class="rm-products-menu">
    <summary>{{ $locale === 'en' ? 'Products' : 'Sản phẩm' }} <span class="rm-dropdown-chevron" aria-hidden="true">⌄</span></summary>
    <nav class="rm-products-dropdown" aria-label="{{ $locale === 'en' ? 'Product categories' : 'Nhóm sản phẩm' }}">
        @foreach(config('product-categories') as $categorySlug => $categoryItem)
        <a href="{{ route('products.show', $categorySlug) }}" @if(request()->routeIs('products.show') && request()->route('category') === $categorySlug) aria-current="page" @endif>{{ $locale === 'en' ? $categoryItem['name_en'] : $categoryItem['name'] }}</a>
        @endforeach
    </nav>
</details>

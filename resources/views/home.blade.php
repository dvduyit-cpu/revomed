@extends('layouts.app')
@section('title', $site['site_title'] ?? 'REVOMED | Revolution Medical')
@section('content')
@php
    $en = $locale === 'en';
    $heroImage = !empty($site['home_hero_image']) ? asset('storage/'.$site['home_hero_image']) : asset('images/ai/heart-hero.webp');
    $products = \App\Support\TeamProducts::all()->where('is_published', 1)->values();
@endphp
<div class="home-premium revomed-home">
<section class="rm-hero hp-hero {{ empty($site['home_hero_image']) ? 'rm-hero-medical' : '' }}" aria-labelledby="home-heading">
    <img class="rm-hero-image hp-hero-image" src="{{ $heroImage }}" alt="{{ $en ? 'REVOMED medical solutions' : 'Giải pháp y khoa REVOMED' }}" fetchpriority="high"><div class="rm-hero-wash hp-hero-shade"></div>
    <div class="rm-container rm-hero-content hp-hero-content"><span class="rm-eyebrow" data-content-keys="home_eyebrow_vi">{{ $site['home_eyebrow_vi'] }}</span><h1 id="home-heading" data-content-keys="home_title_vi">{!! nl2br(e($site['home_title_vi'])) !!}</h1><p data-content-keys="home_body_vi">{{ $site['home_body_vi'] }}</p><a class="rm-link" href="{{ route('about') }}"><span class="rm-circle" aria-hidden="true">↗</span>{{ $en ? 'Discover REVOMED' : 'Khám phá REVOMED' }}</a></div>
    <div class="rm-hero-bottom rm-container"><span>REVOLUTION MEDICAL</span><a href="#giai-phap">{{ $en ? 'Scroll to explore' : 'Khám phá thêm' }} <span aria-hidden="true">↓</span></a><span class="rm-hero-page">{{ $en ? 'MEDICAL SOLUTIONS' : 'GIẢI PHÁP Y KHOA' }}</span></div>
</section>
<section class="rm-opportunity hp-opportunity"><div class="rm-container"><span>{{ $en ? 'People. Purpose. Progress.' : 'Con người. Tận tâm. Tiến bước.' }}</span><a href="{{ route('jobs.index') }}">{{ $openPositions }} {{ $en ? 'open opportunities at REVOMED' : 'cơ hội đang mở tại REVOMED' }} <span aria-hidden="true">↗</span></a></div></section>
<section class="rm-solutions hp-standards-white" id="cung-dong-hanh"><span id="giai-phap" class="rm-anchor" aria-hidden="true"></span>
    <div class="rm-intro-stage">
        <img class="rm-intro-backdrop" src="{{ !empty($site['culture_image']) ? asset('storage/'.$site['culture_image']) : asset('images/ai/research-laboratory.webp') }}" alt="" loading="lazy">
        <div class="rm-container rm-section-heading hp-intro"><div><span class="rm-eyebrow" data-content-keys="home_block_2_vi">{{ $site['home_block_2_vi'] }}</span><h2 data-content-keys="home_intro_title_vi">{!! nl2br(e($site['home_intro_title_vi'])) !!}</h2></div><p data-content-keys="home_intro_body_vi">{{ $site['home_intro_body_vi'] }}</p><p class="rm-intro-statement" data-content-keys="about_block_1_vi">{{ $site['about_block_1_vi'] }}</p></div>
    </div>
    @if($products->isNotEmpty())
    <div class="rm-container rm-product-carousel" data-rm-carousel role="region" aria-label="{{ $en ? 'Medical solutions' : 'Giải pháp y khoa' }}"><span class="rm-product-rail">{{ $en ? 'INTERVENTIONAL CARDIOLOGY' : 'GIẢI PHÁP CAN THIỆP TIM MẠCH' }}</span><div class="rm-product-track" tabindex="0">
    @foreach($products as $product)
        @php
            $name = $en && filled($product['name_en'] ?? '') ? $product['name_en'] : $product['name'];
            $description = $en && filled($product['description_en'] ?? '') ? $product['description_en'] : ($product['description'] ?? '');
            $productImage = $product['image_path'] ?? ($product['gallery_paths'][0] ?? null);
        @endphp
        <article class="rm-product"><div class="rm-product-visual"><span class="rm-product-number">{{ sprintf('%02d', $loop->iteration) }}</span><img src="{{ $productImage ? asset('storage/'.$productImage) : asset('images/ai/coronary-stent.webp') }}" alt="{{ $name }}" loading="lazy"></div><div class="rm-product-copy"><span class="rm-eyebrow">REVOMED / {{ $en ? 'MEDICAL SOLUTIONS' : 'GIẢI PHÁP Y KHOA' }}</span><h3>{{ $name }}</h3><p>{{ $description }}</p><a class="rm-link" href="{{ route('about') }}#san-pham"><span class="rm-circle" aria-hidden="true">↗</span>{{ $en ? 'Explore solutions' : 'Tìm hiểu giải pháp' }}</a></div></article>
    @endforeach
    </div><div class="rm-carousel-controls"><span data-rm-count aria-live="polite">01 / {{ sprintf('%02d', $products->count()) }}</span><div><button type="button" data-rm-prev aria-label="{{ $en ? 'Previous product' : 'Sản phẩm trước' }}">←</button><button type="button" data-rm-next aria-label="{{ $en ? 'Next product' : 'Sản phẩm tiếp theo' }}">→</button></div></div>
    <div class="rm-product-picker" aria-label="{{ $en ? 'Choose a product category' : 'Chọn nhóm sản phẩm' }}">
        @foreach($products as $product)
        @php
            $pickerName = $en && filled($product['name_en'] ?? '') ? $product['name_en'] : $product['name'];
            $pickerImage = $product['image_path'] ?? ($product['gallery_paths'][0] ?? null);
        @endphp
        <button class="rm-picker-card" type="button" data-rm-slide="{{ $loop->index }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}"><span class="rm-picker-index">{{ sprintf('%02d', $loop->iteration) }}</span><img src="{{ $pickerImage ? asset('storage/'.$pickerImage) : asset('images/ai/coronary-stent.webp') }}" alt="" loading="lazy"><span class="rm-picker-name">{{ $pickerName }}</span><span class="rm-picker-arrow" aria-hidden="true">↗</span></button>
        @endforeach
    </div></div>
    @else
    <div class="rm-container rm-product"><div class="rm-product-visual"><span class="rm-product-number">01</span><img src="{{ asset('images/ai/coronary-stent.webp') }}" alt="{{ $en ? 'Cardiovascular solutions' : 'Giải pháp can thiệp tim mạch' }}" loading="lazy"></div><div class="rm-product-copy"><span class="rm-eyebrow">REVOMED / MEDTECH</span><h3>{{ $en ? 'Connecting technology. Supporting care.' : 'Kết nối công nghệ. Đồng hành chăm sóc.' }}</h3><p>{{ $en ? 'Explore medical devices and cardiovascular solutions with REVOMED.' : 'Khám phá các thiết bị y tế và giải pháp can thiệp tim mạch cùng REVOMED.' }}</p><a class="rm-link" href="{{ route('about') }}#san-pham"><span class="rm-circle" aria-hidden="true">↗</span>{{ $en ? 'Learn more' : 'Tìm hiểu thêm' }}</a></div></div>
    @endif
</section>
<section class="rm-impact hp-culture"><div class="rm-impact-art" aria-hidden="true"><img src="{{ (!empty($site['culture_image']) ? asset('storage/'.$site['culture_image']) : asset('images/ai/research-laboratory.webp')) }}" alt="" loading="lazy"><div class="rm-orbit rm-orbit-one"></div><div class="rm-orbit rm-orbit-two"></div><span class="rm-orbit-dot"></span><span class="rm-art-label">CONNECTED BY CARE.</span></div><div class="rm-impact-copy"><span class="rm-eyebrow" data-content-keys="home_block_12_vi">{{ $site['home_block_12_vi'] }}</span><h2 data-content-keys="culture_title_vi">{!! nl2br(e($site['culture_title_vi'])) !!}</h2><p data-content-keys="culture_body_vi">{{ $site['culture_body_vi'] }}</p><a class="rm-link" href="{{ route('about') }}"><span class="rm-circle" aria-hidden="true">↗</span>{{ $en ? 'Our story' : 'Câu chuyện của chúng tôi' }}</a></div></section>
<section class="rm-careers hp-process"><img class="hp-journey-background" src="{{ !empty($site['home_journey_background_image']) ? asset('storage/'.$site['home_journey_background_image']) : asset('images/ai/research-team.webp') }}" alt="" loading="lazy"><div class="rm-careers-shade"></div><div class="rm-container rm-careers-copy"><span class="rm-eyebrow" data-content-keys="home_block_13_vi">{{ $site['home_block_13_vi'] }}</span><h2 data-content-keys="home_block_14_vi">{!! nl2br(e($site['home_block_14_vi'])) !!}</h2><p data-content-keys="home_block_15_vi">{{ $site['home_block_15_vi'] }}</p><a class="rm-link" href="{{ route('jobs.index') }}"><span class="rm-circle" aria-hidden="true">↗</span>{{ $en ? 'Explore careers' : 'Cơ hội nghề nghiệp' }}@if($openPositions > 0)<span class="rm-job-count">{{ $openPositions }}</span>@endif</a></div></section>
@if($latestNews->isNotEmpty())
<section class="rm-news hp-updates"><div class="rm-container"><div class="rm-section-heading"><div><span class="rm-eyebrow" data-content-keys="home_block_22_vi">{{ $site['home_block_22_vi'] }}</span><h2 data-content-keys="home_block_23_vi">{{ $site['home_block_23_vi'] }}</h2></div><a class="rm-link" href="{{ route('news') }}"><span class="rm-circle" aria-hidden="true">↗</span>{{ $en ? 'All news' : 'Tất cả tin tức' }}</a></div><div class="rm-news-grid">@foreach($latestNews as $post)<article class="rm-news-card"><a class="rm-news-image" href="{{ route('news.show', $post->slug) }}" tabindex="-1" aria-hidden="true"><img src="{{ $post->cover_image ? asset('storage/'.$post->cover_image) : asset('images/ai/research-team.webp') }}" alt="" loading="lazy"><span>↗</span></a><div class="rm-news-meta"><span>{{ $en ? 'REVOMED NEWS' : 'TIN REVOMED' }}</span><time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('d.m.Y') }}</time></div><h3><a href="{{ route('news.show', $post->slug) }}">{{ $post->localized('title') }}</a></h3><p>{{ $post->localized('excerpt') }}</p></article>@endforeach</div></div></section>
@endif
<section class="rm-final hp-final"><div class="rm-container"><span class="rm-eyebrow" data-content-keys="home_block_24_vi">{{ $site['home_block_24_vi'] }}</span><h2 data-content-keys="home_block_25_vi">{{ $site['home_block_25_vi'] }}</h2><p data-content-keys="home_block_26_vi">{{ $site['home_block_26_vi'] }}</p><a class="rm-link" href="{{ route('contact.create') }}"><span class="rm-circle" aria-hidden="true">↗</span>{{ $en ? 'Connect with REVOMED' : 'Kết nối cùng REVOMED' }}</a></div></section>
</div>
@endsection

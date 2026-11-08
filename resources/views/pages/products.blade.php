@extends('layouts.app')
@php
    $en = app()->getLocale() === 'en';
    $categoryInfo = $categories[$category];
    $categoryName = $categoryInfo[$en ? 'name_en' : 'name'];
@endphp
@section('title', $categoryName.' | REVOMED')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">{{ $en ? 'REVOMED / PRODUCTS' : 'REVOMED / SẢN PHẨM' }}</span><h1>{{ $categoryName }}</h1><p>{{ $categoryInfo[$en ? 'description_en' : 'description'] }}</p></div></section>
<section class="container section rm-category-page">
    <nav class="rm-category-links" aria-label="{{ $en ? 'Browse product categories' : 'Xem nhóm sản phẩm' }}">
        @foreach($categories as $slug => $item)
        <a href="{{ route('products.show', $slug) }}" @if($slug === $category) aria-current="page" @endif>{{ $item[$en ? 'name_en' : 'name'] }}</a>
        @endforeach
    </nav>
    @if($products->isNotEmpty())
    @include('partials.product-cards')
    @else
    <div class="rm-category-enquiry"><h2>{{ $en ? 'Discuss your product requirements.' : 'Trao đổi về sản phẩm bạn cần.' }}</h2><p>{{ $en ? 'Send the product category and your questions so REVOMED can connect you with relevant information and documentation.' : 'Gửi nhóm sản phẩm và nội dung bạn quan tâm để REVOMED kết nối thông tin, tài liệu và đầu mối phù hợp.' }}</p><a class="rm-link" href="{{ route('contact.create') }}"><span class="rm-circle" aria-hidden="true">↗</span>{{ $en ? 'Contact REVOMED' : 'Liên hệ REVOMED' }}</a></div>
    @endif
</section>
@endsection

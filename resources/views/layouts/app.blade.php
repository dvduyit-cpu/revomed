<!doctype html>
<html lang="{{ $locale ?? 'vi' }}">
<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/revomed-mark.svg') }}">
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $site['site_title'] ?? 'REVOMED')</title><meta name="description" content="@yield('meta_description', $site['site_description'] ?? 'REVOMED Careers')">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"><link rel="stylesheet" href="{{ asset('css/rebrand.css') }}?v={{ \App\Support\AssetVersion::get('css/rebrand.css') }}"><link rel="stylesheet" href="{{ asset('css/typography-fix.css') }}?v={{ \App\Support\AssetVersion::get('css/typography-fix.css') }}"><link rel="stylesheet" href="{{ asset('css/brand-content.css') }}?v={{ \App\Support\AssetVersion::get('css/brand-content.css') }}"><link rel="stylesheet" href="{{ asset('css/website-fix.css') }}?v={{ \App\Support\AssetVersion::get('css/website-fix.css') }}"><link rel="stylesheet" href="{{ asset('css/polish.css') }}"><link rel="stylesheet" href="{{ asset('css/logo.css') }}">
    @if(request()->routeIs('home') || (request()->routeIs('admin.content.preview') && in_array(request()->route('section'), ['trang-chu', 'thuong-hieu', 'mau-sac'], true)))<link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ \App\Support\AssetVersion::get('css/home.css') }}">@endif
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}?v={{ \App\Support\AssetVersion::get('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site-motion.css') }}?v={{ \App\Support\AssetVersion::get('css/site-motion.css') }}">
    <script defer src="{{ asset('js/site-motion.js') }}?v={{ \App\Support\AssetVersion::get('js/site-motion.js') }}"></script>
    <script defer src="{{ asset('js/site-navigation.js') }}?v={{ \App\Support\AssetVersion::get('js/site-navigation.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/medical-update.css') }}?v={{ \App\Support\AssetVersion::get('css/medical-update.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}?v={{ \App\Support\AssetVersion::get('css/responsive.css') }}">
@include('partials.website-theme')
@if(request()->routeIs('contact.*') || (request()->routeIs('admin.content.preview') && request()->route('section') === 'lien-he'))
<link rel="stylesheet" href="{{ asset('css/contact.css') }}?v={{ \App\Support\AssetVersion::get('css/contact.css') }}">
@endif
@include('partials.content-alignment')
@include('partials.visual-page-style')
<script defer src="{{ asset('js/product-slider.js') }}?v={{ \App\Support\AssetVersion::get('js/product-slider.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/revomed.css') }}?v={{ \App\Support\AssetVersion::get('css/revomed.css') }}">
<link rel="stylesheet" href="{{ asset('css/medical-portfolio.css') }}?v={{ \App\Support\AssetVersion::get('css/medical-portfolio.css') }}">
<script defer src="{{ asset('js/revomed.js') }}?v={{ \App\Support\AssetVersion::get('js/revomed.js') }}"></script>
</head>
<body class="revomed-site">
<a class="rm-skip-link" href="#main-content">{{ $locale === 'en' ? 'Skip to content' : 'Đến nội dung chính' }}</a>
<header class="site-header"><div class="container nav nav-rebuild"><a class="brand brand-logo" href="{{ route('home') }}" aria-label="REVOMED"><img class="company-logo" src="{{ !empty($site['site_logo']) ? route('site.logo', ['v' => substr(hash('sha256', $site['site_logo']), 0, 12)], false) : asset('images/revomed-logo.svg') }}" alt="REVOMED"><span class="visually-hidden">{{ $site['company_name'] ?? 'REVOMED' }}</span></a><button class="site-menu-toggle" type="button" aria-label="{{ $locale === 'en' ? 'Open navigation menu' : 'Mở menu điều hướng' }}" aria-expanded="false" aria-controls="primary-navigation"><span aria-hidden="true">☰</span></button><nav id="primary-navigation" class="primary-nav" aria-label="Điều hướng chính"><a data-home-link class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ $locale === 'en' ? 'Home' : 'Trang chủ' }}</a><a class="{{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">{{ $locale === 'en' ? 'About us' : 'Giới thiệu' }}</a>@include('partials.product-menu')<a class="{{ request()->routeIs('team') ? 'active' : '' }}" href="{{ route('team') }}">{{ $locale === 'en' ? 'Our team' : 'Đội ngũ' }}</a><a class="{{ request()->routeIs('news*') ? 'active' : '' }}" href="{{ route('news') }}">{{ $locale === 'en' ? 'News' : 'Tin tức' }}</a><a data-careers-link class="{{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">{{ $locale === 'en' ? 'Careers' : 'Tuyển dụng' }}</a><a class="{{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.create') }}">{{ $locale === 'en' ? 'Contact' : 'Liên hệ' }}</a><a class="locale-toggle" href="{{ route('locale.switch', ['locale' => $locale === 'vi' ? 'en' : 'vi', 'redirect' => url()->full()]) }}">{{ $locale === 'vi' ? 'EN' : 'VI' }}</a></nav></div></header>
<main id="main-content">@if(session('success'))<div class="container alert success">{{ session('success') }}</div>@endif @yield('content')</main>
@include('partials.site-footer')
</body></html>

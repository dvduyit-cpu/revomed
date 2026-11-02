<link rel="icon" href="{{ !empty($site['site_logo']) ? route('site.logo', ['v' => substr(hash('sha256', $site['site_logo']), 0, 12)], false) : asset('images/hoang-thuoc-logo.png') }}">
<link rel="apple-touch-icon" href="{{ !empty($site['site_logo']) ? route('site.logo', ['v' => substr(hash('sha256', $site['site_logo']), 0, 12)], false) : asset('images/hoang-thuoc-logo.png') }}">

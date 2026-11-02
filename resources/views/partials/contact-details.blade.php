@php
    $email = $site['company_email'] ?? '';
    $phone = $site['company_phone'] ?? '';
    $address = $site['company_address_'.$locale] ?? '';
    $hasContactDetails = filled($email) || filled($phone) || filled($address);
@endphp
<h2 class="contact-info-title" data-content-keys="contact_block_2_vi">{{ $site['contact_block_2_vi'] }}</h2>
@if($hasContactDetails)
<div class="contact-details">
    @if(filled($email))<p><x-admin-icon name="mail" size="22" /><strong>EMAIL</strong><a href="mailto:{{ $email }}" data-content-keys="company_email">{{ $email }}</a></p>@endif
    @if(filled($phone))<p><x-admin-icon name="phone" size="22" /><strong>{{ __('ĐIỆN THOẠI') }}</strong><a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" data-content-keys="company_phone">{{ $phone }}</a></p>@endif
    @if(filled($address))<p><x-admin-icon name="building" size="22" /><strong>{{ __('VĂN PHÒNG') }}</strong><a href="https://www.google.com/maps/search/?api=1&amp;query={{ rawurlencode($address) }}" target="_blank" rel="noopener noreferrer" data-content-keys="company_address_vi">{{ $address }}<br><small>{{ __('Xem vị trí trên bản đồ ↗') }}</small></a></p>@endif
</div>
@if(filled($address))<a class="contact-map" href="https://www.google.com/maps/search/?api=1&amp;query={{ rawurlencode($address) }}" target="_blank" rel="noopener noreferrer"><x-admin-icon name="pin" size="28" /><span data-content-keys="company_name">{{ $site['company_name'] }}<br><small data-content-keys="company_address_vi">{{ $address }} — {{ __('Mở bản đồ ↗') }}</small></span></a>@endif
@else
<div class="contact-details">
    <p><x-admin-icon name="building" size="22" /><strong>{{ $locale === 'en' ? 'PRODUCTS & APPLICATIONS' : 'SẢN PHẨM & ỨNG DỤNG' }}</strong><span>{{ $locale === 'en' ? 'Device categories, documentation and information to clarify.' : 'Nhóm thiết bị, tài liệu và thông tin cần làm rõ.' }}</span></p>
    <p><x-admin-icon name="mail" size="22" /><strong>{{ $locale === 'en' ? 'WORKING TOGETHER' : 'ĐỀ XUẤT HỢP TÁC' }}</strong><span>{{ $locale === 'en' ? 'Your area of expertise, proposed scope and collaboration ideas.' : 'Lĩnh vực chuyên môn, phạm vi dự kiến và ý tưởng phối hợp.' }}</span></p>
    <p><x-admin-icon name="building" size="22" /><strong>{{ $locale === 'en' ? 'CAREER DIRECTION' : 'ĐỊNH HƯỚNG NGHỀ NGHIỆP' }}</strong><span>{{ $locale === 'en' ? 'The roles you are interested in and the skills you would like to develop.' : 'Vai trò bạn quan tâm và năng lực bạn muốn phát triển.' }}</span></p>
</div>
@endif

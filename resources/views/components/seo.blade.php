{{-- SEO + OpenGraph meta tags --}}

<title>{{ $seo['title'] ?? '' }}</title>

<meta name="description" content="{{ $seo['description'] ?? '' }}">

{{-- Standard OpenGraph --}}
<meta property="og:title" content="{{ $seo['title'] ?? '' }}">
<meta property="og:description" content="{{ $seo['description'] ?? '' }}">
<meta property="og:type" content="{{ $seo['type'] ?? 'article' }}">
<meta property="og:url" content="{{ $seo['url'] ?? url()->current() }}">
<meta property="og:image" content="{{ $seo['image'] ?? '' }}">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo['title'] ?? '' }}">
<meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
<meta name="twitter:image" content="{{ $seo['image'] ?? '' }}">

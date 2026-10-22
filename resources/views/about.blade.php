<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $content['seo']['title'] }}</title><meta name="description" content="{{ $content['seo']['description'] }}">
    <link href="{{ asset('css/vendor/about-fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/about.css') }}" rel="stylesheet">
</head>
<body>
<header class="header"><div class="wrap header-inner">
    <a class="brand" href="{{ route('home') }}"><span class="brand-mark">{{ $content['branding']['mark'] }}</span><span><strong>{{ $content['branding']['name'] }}<em>{{ $content['branding']['highlight'] }}</em></strong><small>{{ $content['branding']['tagline'] }}</small></span></a>
    <nav aria-label="Main navigation">@foreach($content['navigation'] as $link)<a href="{{ $link['url'] }}" @if(parse_url($link['url'], PHP_URL_PATH) === '/about') aria-current="page" @endif>{{ $link['label'] }}</a>@endforeach</nav>
    <a class="profile" href="{{ auth('web')->check() ? route('user.profile') : route('login') }}" aria-label="Your account"><i class="bi bi-person" aria-hidden="true"></i></a>
</div></header>
<main>
    <section class="hero" aria-labelledby="hero-title">
        <img class="hero-image" src="{{ asset($content['hero']['image']) }}" alt="{{ $content['hero']['alt'] }}" fetchpriority="high">
        <div class="hero-fade"></div><div class="hero-arc" aria-hidden="true"></div>
        <div class="wrap hero-inner"><div class="hero-copy"><div class="eyebrow">{{ $content['hero']['eyebrow'] }}</div><h1 id="hero-title">{{ $content['hero']['title'] }}<span>{{ $content['hero']['highlight'] }}</span></h1><p>{{ $content['hero']['description'] }}</p></div>
        <div class="hero-badge"><i class="bi bi-car-front-fill" aria-hidden="true"></i><div><strong>{{ $content['hero']['badge_title'] }}</strong><small>{{ $content['hero']['badge_text'] }}</small></div></div></div>
    </section>
    <div class="wrap body-content">
        <section class="story" aria-labelledby="story-title"><div class="story-picture"><img src="{{ asset($content['story']['image']) }}" alt="{{ $content['story']['alt'] }}"><div class="story-ribbon" aria-hidden="true"></div><div class="story-badge"><i class="bi bi-calendar-check" aria-hidden="true"></i><strong>{{ $content['story']['badge_title'] }}<br>{{ $content['story']['badge_text'] }}</strong></div></div>
            <div class="story-copy"><div class="eyebrow">{{ $content['story']['eyebrow'] }}</div><h2 id="story-title">{{ $content['story']['title'] }} <span>{{ $content['story']['highlight'] }}</span></h2><p>{{ $content['story']['description'] }}</p></div>
        </section>
        <section class="purpose" aria-label="Mission and vision">@foreach(['mission', 'vision'] as $section)<article><i class="purpose-icon bi bi-{{ $content[$section]['icon'] }}" aria-hidden="true"></i><div><h2>{{ $content[$section]['title'] }} <span>{{ $content[$section]['highlight'] }}</span></h2><p>{{ $content[$section]['description'] }}</p></div></article>@endforeach</section>
        <section class="difference" aria-labelledby="difference-title"><div><div class="eyebrow">{{ $content['difference']['eyebrow'] }}</div><h2 id="difference-title">{{ $content['difference']['title'] }} <span>{{ $content['difference']['highlight'] }}</span></h2><p class="difference-intro">{{ $content['difference']['description'] }}</p><div class="features">@foreach($content['features'] as $feature)<article><i class="feature-icon bi bi-{{ $feature['icon'] }}" aria-hidden="true"></i><div><h3>{{ $feature['title'] }}</h3><p>{{ $feature['description'] }}</p></div></article>@endforeach</div></div>
            <div class="difference-picture"><img src="{{ asset($content['difference']['image']) }}" alt="{{ $content['difference']['alt'] }}" loading="lazy"><div class="picture-note">{{ $content['difference']['badge'] }}<span aria-hidden="true"></span></div></div>
        </section>
        @if(count($content['statistics']))<section class="statistics" aria-label="SmartParking in numbers">@foreach($content['statistics'] as $stat)<article><i class="bi bi-{{ $stat['icon'] }}" aria-hidden="true"></i><div><strong>{{ $stat['value'] }}</strong><p>{{ $stat['label'] }}</p></div></article>@endforeach</section>@endif
    </div>
</main>
<footer id="footer"><div class="wrap"><div class="footer-grid"><div><a class="brand" href="{{ route('home') }}"><span class="brand-mark">{{ $content['branding']['mark'] }}</span><span><strong>{{ $content['branding']['name'] }}<em>{{ $content['branding']['highlight'] }}</em></strong><small>{{ $content['branding']['tagline'] }}</small></span></a><p>{{ $content['footer']['description'] }}</p><div class="socials">@foreach($content['socials'] as $social)<a href="{{ $social['url'] }}" aria-label="{{ $social['label'] }}"><i class="bi bi-{{ $social['icon'] }}" aria-hidden="true"></i></a>@endforeach</div></div>
    <div><h3>{{ $content['footer']['links_title'] }}</h3>@foreach($content['navigation'] as $link)<a class="footer-link" href="{{ $link['url'] }}">{{ $link['label'] }}</a>@endforeach</div>
    <div><h3>{{ $content['footer']['support_title'] }}</h3>@foreach($content['support'] as $link)<a class="footer-link" href="{{ $link['url'] }}">{{ $link['label'] }}</a>@endforeach</div>
    <div><h3>{{ $content['footer']['app_title'] }}</h3><p>{{ $content['footer']['app_description'] }}</p><div class="app-badges"><a href="{{ $content['footer']['google_url'] }}"><i class="bi bi-google-play" aria-hidden="true"></i><span><small>{{ $content['footer']['google_caption'] }}</small><strong>{{ $content['footer']['google_label'] }}</strong></span></a><a href="{{ $content['footer']['apple_url'] }}"><i class="bi bi-apple" aria-hidden="true"></i><span><small>{{ $content['footer']['apple_caption'] }}</small><strong>{{ $content['footer']['apple_label'] }}</strong></span></a></div></div>
    </div><div class="footer-bottom"><span>{{ $content['footer']['copyright'] }}</span><span class="footer-tagline">{{ $content['footer']['tagline'] }}</span></div></div></footer>
</body></html>

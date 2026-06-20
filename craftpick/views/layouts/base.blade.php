<!DOCTYPE html>
@include('elements.base')
@php
    $navPosition = theme_config('nav_position', 'top');
    $loaderEnabled = (bool) theme_config('loading_screen_enabled', true);
    $loaderStyle = theme_config('loading_screen_style', 'cinematic');
    $animationLevel = theme_config('animation_level', 'full');
    $pageTransition = theme_config('page_transition', 'fade');
    $heroAnimation = theme_config('hero_animation', 'float');
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ dark_theme(true) ? 'dark' : 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', setting('description', ''))">
    <meta name="theme-color" content="{{ theme_config('color', '#7c3aed') }}">
    <meta name="author" content="Craftpick">
    <meta property="og:title" content="@yield('title', site_name())">
    <meta property="og:type" content="@yield('type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ favicon() }}">
    <meta property="og:description" content="@yield('description', setting('description', ''))">
    <meta property="og:site_name" content="{{ site_name() }}">
    @stack('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', site_name()) | {{ site_name() }}</title>

    <link rel="shortcut icon" href="{{ favicon() }}">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    @includeIf('elements.theme-color', ['color' => theme_config('color', '#7c3aed')])
    <link href="{{ theme_asset('css/style.css') }}" rel="stylesheet">

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('vendor/axios/axios.min.js') }}" defer></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="{{ theme_asset('js/theme.js') }}" defer></script>

    @stack('styles')
    @stack('scripts')
</head>
<body
    class="craftpick-body d-flex flex-column craftpick-nav-{{ $navPosition }}"
    data-bs-theme="{{ dark_theme(true) ? 'dark' : 'light' }}"
    data-nav-position="{{ $navPosition }}"
    data-animation-level="{{ $animationLevel }}"
    data-page-transition="{{ $pageTransition }}"
    data-loader-style="{{ $loaderStyle }}"
    data-craftpick-loader="{{ $loaderEnabled ? '1' : '0' }}"
    data-craftpick-tilt="{{ theme_config('enable_3d_tilt') ? '1' : '0' }}"
    data-craftpick-parallax="{{ theme_config('enable_parallax') ? '1' : '0' }}"
    data-craftpick-cinematic="{{ theme_config('enable_cinematic_intro') ? '1' : '0' }}"
    style="
        --craftpick-primary: {{ theme_config('color', '#7c3aed') }};
        --craftpick-secondary: {{ theme_config('color_secondary', '#22d3ee') }};
        --craftpick-surface-custom: {{ theme_config('surface_color', '#1f115c') }};
        --craftpick-background-custom: {{ theme_config('background_color', '#14082e') }};
        --craftpick-hero-animation: {{ $heroAnimation }};
    "
>
    @if($loaderEnabled)
        <div class="craftpick-loader craftpick-loader-{{ $loaderStyle }}" id="craftpickLoader" aria-hidden="true">
            <div class="craftpick-loader-content">
                <div class="craftpick-loader-logo">{{ site_name() }}</div>
                <div class="craftpick-loader-spinner"></div>
                <div class="craftpick-loader-bars">
                    <span></span><span></span><span></span>
                </div>
                <div class="craftpick-loader-line"></div>
            </div>
        </div>
    @endif

    <div id="app" class="flex-shrink-0 craftpick-app">
        @if($navPosition === 'side')
            <div class="craftpick-shell craftpick-shell-side">
                @include('elements.sidebar')
                <main class="craftpick-shell-content">
                    @yield('app')
                    @include('elements.footer')
                </main>
            </div>
        @else
            <header>
                @include('elements.navbar')
            </header>

            @yield('app')
            @include('elements.footer')
        @endif
    </div>

    @stack('footer-scripts')
</body>
</html>

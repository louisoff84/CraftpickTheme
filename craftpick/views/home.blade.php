@extends('layouts.base')

@section('title', trans('messages.home'))

@section('app')
    @php
        $serverIp = theme_config('server_ip') ?: ($server ? $server->fullAddress() : '');
        $heroBackground = theme_config('hero_background') ?: (setting('background') ? image_url(setting('background')) : null);
        $heroIcon = theme_config('hero_icon');
        $showcaseVideoUrl = theme_config('showcase_video_url');
        $showcaseVideoTitle = theme_config('showcase_video_title') ?: 'Video de presentation';
        $showcaseVideoText = theme_config('showcase_video_text');
        $showcaseEmbedUrl = null;
        $isAdminDesigner = auth()->check() && auth()->user()->hasAdminAccess();
        $modes = [
            ['name' => theme_config('mode_1_name'), 'url' => theme_config('mode_1_url'), 'icon' => 'bi bi-lightning-charge'],
            ['name' => theme_config('mode_2_name'), 'url' => theme_config('mode_2_url'), 'icon' => 'bi bi-crosshair2'],
            ['name' => theme_config('mode_3_name'), 'url' => theme_config('mode_3_url'), 'icon' => 'bi bi-bricks'],
            ['name' => theme_config('mode_4_name'), 'url' => theme_config('mode_4_url'), 'icon' => 'bi bi-grid-3x3-gap'],
        ];
        $modes = array_values(array_filter($modes, fn ($m) => filled($m['name']) && filled($m['url'])));

        if (filled($showcaseVideoUrl)) {
            if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/)([\w-]+)~i', $showcaseVideoUrl, $matches)) {
                $showcaseEmbedUrl = 'https://www.youtube.com/embed/'.$matches[1].'?rel=0';
            } elseif (preg_match('~vimeo\.com/(\d+)~i', $showcaseVideoUrl, $matches)) {
                $showcaseEmbedUrl = 'https://player.vimeo.com/video/'.$matches[1];
            }
        }
    @endphp

    <section class="craftpick-hero craftpick-hero-animated" @if($heroBackground) style="background-image: linear-gradient(180deg, rgba(15, 16, 32, 0.35), rgba(11, 12, 20, 0.88)), url('{{ $heroBackground }}');" @endif>
        <div class="container">
            <div class="craftpick-hero-inner">
                <div class="craftpick-hero-left text-center">
                    @if($heroIcon)
                        <div class="craftpick-hero-logo-wrap">
                            <img src="{{ $heroIcon }}" alt="{{ site_name() }}" class="craftpick-hero-logo">
                        </div>
                    @endif

                    <div class="craftpick-hero-badge">
                        <i class="bi bi-stars"></i>
                        <span>{{ trans('theme::messages.home.badge') }}</span>
                    </div>

                    <h1 class="craftpick-hero-title" data-craftpick-live-hero-title>
                        {{ theme_config('hero_title', trans('messages.welcome', ['name' => site_name()])) }}
                    </h1>

                    @if(theme_config('hero_subtitle'))
                        <p class="craftpick-hero-subtitle">
                            {{ theme_config('hero_subtitle') }}
                        </p>
                    @endif

                    <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                        @if($server)
                            @if($server->join_url)
                                <a href="{{ $server->join_url }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-play-fill"></i> {{ trans('theme::messages.home.cta_play') }}
                                </a>
                            @else
                                <button type="button" class="btn btn-primary btn-lg" data-craftpick-copy="{{ $serverIp }}">
                                    <i class="bi bi-clipboard"></i> {{ $serverIp }}
                                </button>
                            @endif
                        @endif

                        @if(theme_config('discord_url'))
                            <a href="{{ theme_config('discord_url') }}" class="btn btn-outline-light btn-lg" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-discord"></i> {{ trans('theme::messages.home.cta_discord') }}
                            </a>
                        @endif
                    </div>

                    @if($server)
                        <div class="craftpick-hero-meta">
                            @if($server->isOnline())
                                <span class="craftpick-online-dot"></span>
                                <span>{{ trans_choice('messages.server.online', $server->getOnlinePlayers()) }}</span>
                            @else
                                <span class="text-danger">{{ trans('messages.server.offline') }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                @if($server)
                    <div class="craftpick-hero-right">
                        <div class="craftpick-hero-card craftpick-tilt" data-craftpick-tilt-card>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="fs-5">{{ $server->name ?? trans('messages.server') }}</strong>
                                @if($server->isOnline())
                                    <span class="badge text-bg-success">{{ trans('messages.server.online') }}</span>
                                @else
                                    <span class="badge text-bg-danger">{{ trans('messages.server.offline') }}</span>
                                @endif
                            </div>
                            @if($server->isOnline())
                                <div class="progress mb-2" role="progressbar" aria-valuenow="{{ $server->getPlayersPercents() }}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar" style="width: {{ $server->getPlayersPercents() }}%"></div>
                                </div>
                                <div class="text-secondary">
                                    {{ trans_choice('messages.server.total', $server->getOnlinePlayers(), ['max' => $server->getMaxPlayers()]) }}
                                </div>
                            @else
                                <div class="text-secondary">
                                    {{ trans('messages.server.offline') }}
                                </div>
                            @endif

                            @if(filled($serverIp))
                                <button type="button" class="btn btn-outline-secondary w-100 mt-3" data-craftpick-copy="{{ $serverIp }}">
                                    <i class="bi bi-clipboard"></i> {{ $serverIp }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="craftpick-hero-glow" aria-hidden="true" data-craftpick-parallax="18"></div>
    </section>

    @if(filled($showcaseVideoUrl))
        <section class="container my-5">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-5">
                    <div class="craftpick-card craftpick-showcase-copy h-100">
                        <div class="card-body p-4 p-lg-5">
                            <div class="craftpick-section-head mb-3">
                                <h2 class="mb-2">{{ $showcaseVideoTitle }}</h2>
                                @if($showcaseVideoText)
                                    <div class="text-secondary">{{ $showcaseVideoText }}</div>
                                @endif
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-4">
                                @if($serverIp)
                                    <button type="button" class="btn btn-primary" data-craftpick-copy="{{ $serverIp }}">
                                        <i class="bi bi-clipboard"></i> {{ $serverIp }}
                                    </button>
                                @endif

                                <a href="{{ $showcaseVideoUrl }}" class="btn btn-outline-secondary" target="_blank" rel="noopener noreferrer">
                                    <i class="bi bi-play-circle"></i> Ouvrir la video
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="craftpick-card craftpick-showcase-video overflow-hidden">
                        <div class="craftpick-video-frame">
                            @if($showcaseEmbedUrl)
                                <iframe
                                    src="{{ $showcaseEmbedUrl }}"
                                    title="{{ $showcaseVideoTitle }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                ></iframe>
                            @else
                                <video controls preload="metadata" playsinline @if($heroBackground) poster="{{ $heroBackground }}" @endif>
                                    <source src="{{ $showcaseVideoUrl }}">
                                </video>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="container my-5">
        <div class="row gy-3">
            <div class="col-lg-5">
                <div class="card h-100 craftpick-card craftpick-community-card craftpick-tilt" data-craftpick-tilt-card>
                    <div class="card-body p-4">
                        <div class="craftpick-section-head mb-4">
                            <h2 class="mb-2">{{ trans('theme::messages.home.community_title') }}</h2>
                            <div class="text-secondary">{{ trans('theme::messages.home.community_text') }}</div>
                        </div>

                        <div class="craftpick-server-box">
                            <label class="craftpick-server-label">{{ trans('theme::messages.home.server_name') }}</label>
                            <div class="craftpick-server-input" data-craftpick-live-name>{{ site_name() }}</div>
                            <label class="craftpick-server-label mt-3">{{ trans('theme::messages.home.server_address') }}</label>
                            <button type="button" class="craftpick-server-input craftpick-server-button" data-craftpick-copy="{{ $serverIp }}" @if(!filled($serverIp)) disabled @endif>
                                {{ $serverIp ?: 'play.craftpick.mc' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card h-100 craftpick-card craftpick-tilt" data-craftpick-tilt-card>
                    <div class="card-body p-4">
                        <div class="craftpick-section-head mb-4">
                            <h2 class="mb-1">{{ trans('theme::messages.home.join_title') }}</h2>
                            <div class="text-secondary">{{ trans('theme::messages.home.join_subtitle') }}</div>
                        </div>

                        <div class="craftpick-steps-grid">
                            <div class="craftpick-step-card">
                                <div class="craftpick-step-number">1</div>
                                <div class="fw-semibold">{{ trans('theme::messages.home.join_step_1') }}</div>
                            </div>
                            <div class="craftpick-step-card">
                                <div class="craftpick-step-number">2</div>
                                <div class="fw-semibold">{{ trans('theme::messages.home.join_step_2') }}</div>
                                <button type="button" class="btn btn-outline-secondary mt-2" data-craftpick-copy="{{ $serverIp }}" @if(!filled($serverIp)) disabled @endif>
                                    <i class="bi bi-clipboard"></i> {{ trans('theme::messages.home.copy_ip') }}
                                </button>
                            </div>
                            <div class="craftpick-step-card">
                                <div class="craftpick-step-number">3</div>
                                <div class="fw-semibold">{{ trans('theme::messages.home.join_step_3') }}</div>
                                @if($server && $server->join_url)
                                    <a href="{{ $server->join_url }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-play-fill"></i> {{ trans('messages.server.join') }}
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-person-plus-fill"></i> {{ trans('auth.register') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @plugin('shop')
        <section class="container my-5">
            <div class="craftpick-shop card craftpick-card overflow-hidden craftpick-tilt" data-craftpick-tilt-card>
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center gy-3">
                        <div class="col-md-8">
                            <h2 class="mb-2">{{ trans('theme::messages.home.shop_title') }}</h2>
                            <p class="text-secondary mb-0">{{ trans('theme::messages.home.shop_text') }}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="{{ route('shop.home') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-bag-check"></i> {{ trans('theme::messages.home.shop_cta') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endplugin

    @if(!empty($modes))
        <section class="container my-5">
            <div class="craftpick-section-head mb-3">
                <h2 class="mb-0">{{ theme_config('modes_title', trans('theme::messages.home.modes_title_fallback')) }}</h2>
            </div>
            <div class="row gy-3">
                @foreach($modes as $mode)
                    <div class="col-md-3 col-sm-6">
                        <a class="craftpick-mode card h-100 craftpick-card craftpick-tilt" href="{{ url($mode['url']) }}" data-craftpick-tilt-card>
                            <div class="card-body">
                                <div class="craftpick-mode-icon">
                                    <i class="{{ $mode['icon'] }}"></i>
                                </div>
                                <div class="fw-semibold">{{ $mode['name'] }}</div>
                                <div class="text-secondary">{{ trans('theme::messages.home.discover') }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <div class="container content my-5">
        @include('elements.session-alerts')

        @if($message)
            <div class="card mb-4">
                <div class="card-body">
                    {{ $message }}
                </div>
            </div>
        @endif

        @if(! $servers->isEmpty())
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="mb-0">{{ trans('theme::messages.home.servers') }}</h2>
            </div>
            <div class="row gy-3 justify-content-center mb-4">
                @foreach($servers as $server)
                    <div class="col-md-4">
                        <div class="card h-100 craftpick-card craftpick-tilt" data-craftpick-tilt-card>
                            <div class="card-body text-center">
                                <h3 class="card-title">
                                    {{ $server->name }}
                                </h3>
                                @if($server->isOnline())
                                    <div class="progress mb-2">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $server->getPlayersPercents() }}%"></div>
                                    </div>
                                    <p class="mb-1 text-secondary">
                                        {{ trans_choice('messages.server.total', $server->getOnlinePlayers(), ['max' => $server->getMaxPlayers()]) }}
                                    </p>
                                @else
                                    <p class="mb-2">
                                        <span class="badge bg-danger">{{ trans('messages.server.offline') }}</span>
                                    </p>
                                @endif
                                @if($server->join_url)
                                    <a href="{{ $server->join_url }}" class="btn btn-primary">
                                        {{ trans('messages.server.join') }}
                                    </a>
                                @else
                                    <button type="button" class="btn btn-outline-secondary" data-craftpick-copy="{{ $server->fullAddress() }}">
                                        <i class="bi bi-clipboard"></i> {{ $server->fullAddress() }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(! $posts->isEmpty())
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="mb-0">{{ trans('theme::messages.home.news') }}</h2>
            </div>
            <div class="row gy-3">
                @foreach($posts as $post)
                    <div class="col-md-6">
                        <div class="post-preview card h-100 craftpick-card craftpick-tilt" data-craftpick-tilt-card>
                            @if($post->hasImage())
                                <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="card-img-top">
                            @endif
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="card-text text-secondary">{{ Str::limit(strip_tags($post->content), 220) }}</p>
                                <a class="btn btn-outline-light" href="{{ route('posts.show', $post) }}">
                                    {{ trans('messages.posts.read') }}
                                </a>
                            </div>
                            <div class="card-footer text-secondary">
                                {{ trans('messages.posts.posted', ['date' => format_date($post->published_at), 'user' => $post->author->name]) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if($isAdminDesigner)
        <button
            type="button"
            class="craftpick-designer-toggle"
            data-craftpick-designer-toggle
            aria-label="Ouvrir le designer"
        >
            <i class="bi bi-palette-fill"></i>
        </button>

        <aside
            class="craftpick-designer"
            data-craftpick-designer
            data-default-primary="{{ theme_config('color', '#7c3aed') }}"
            data-default-secondary="{{ theme_config('color_secondary', '#22d3ee') }}"
            data-default-surface="{{ theme_config('surface_color', '#1f115c') }}"
            data-default-background="{{ theme_config('background_color', '#14082e') }}"
            data-default-radius="22"
            data-default-display-name="{{ site_name() }}"
            data-default-hero-title="{{ theme_config('hero_title', trans('messages.welcome', ['name' => site_name()])) }}"
            aria-hidden="true"
        >
            <div class="craftpick-designer-header">
                <div>
                    <div class="craftpick-designer-kicker">Admin Only</div>
                    <h2 class="craftpick-designer-title">Designer Index</h2>
                </div>
                <button type="button" class="craftpick-designer-close" data-craftpick-designer-close aria-label="Fermer le designer">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="craftpick-designer-body">
                <div class="craftpick-designer-section">
                    <div class="craftpick-designer-section-title">Presets</div>
                    <div class="craftpick-designer-presets">
                        <button type="button" class="craftpick-designer-preset" data-craftpick-preset="nebula" data-primary="#7c3aed" data-secondary="#22d3ee" data-surface="#1f115c" data-background="#14082e">
                            <span style="--swatch-a:#7c3aed;--swatch-b:#22d3ee;--swatch-c:#1f115c;--swatch-d:#14082e;"></span>
                            <strong>Nebula</strong>
                        </button>
                        <button type="button" class="craftpick-designer-preset" data-craftpick-preset="royal" data-primary="#5b6df3" data-secondary="#9b5cff" data-surface="#1a1f63" data-background="#10153d">
                            <span style="--swatch-a:#5b6df3;--swatch-b:#9b5cff;--swatch-c:#1a1f63;--swatch-d:#10153d;"></span>
                            <strong>Royal</strong>
                        </button>
                        <button type="button" class="craftpick-designer-preset" data-craftpick-preset="sunset" data-primary="#ff6b6b" data-secondary="#f59e0b" data-surface="#54255d" data-background="#24103f">
                            <span style="--swatch-a:#ff6b6b;--swatch-b:#f59e0b;--swatch-c:#54255d;--swatch-d:#24103f;"></span>
                            <strong>Sunset</strong>
                        </button>
                        <button type="button" class="craftpick-designer-preset" data-craftpick-preset="emerald" data-primary="#22c55e" data-secondary="#2dd4bf" data-surface="#173d38" data-background="#0d1f21">
                            <span style="--swatch-a:#22c55e;--swatch-b:#2dd4bf;--swatch-c:#173d38;--swatch-d:#0d1f21;"></span>
                            <strong>Emerald</strong>
                        </button>
                    </div>
                </div>

                <div class="craftpick-designer-section">
                    <div class="craftpick-designer-section-title">Textes</div>
                    <label class="craftpick-designer-text">
                        <span>Nom affiche</span>
                        <input type="text" value="{{ site_name() }}" data-craftpick-text="displayName" placeholder="Craftpick MC">
                    </label>
                    <label class="craftpick-designer-text">
                        <span>Titre hero</span>
                        <input type="text" value="{{ theme_config('hero_title', trans('messages.welcome', ['name' => site_name()])) }}" data-craftpick-text="heroTitle">
                    </label>
                </div>

                <div class="craftpick-designer-section">
                    <div class="craftpick-designer-section-title">Couleurs</div>
                    <label class="craftpick-designer-field">
                        <span>Couleur primaire</span>
                        <input type="color" value="{{ theme_config('color', '#7c3aed') }}" data-craftpick-color="primary">
                    </label>
                    <label class="craftpick-designer-field">
                        <span>Couleur secondaire</span>
                        <input type="color" value="{{ theme_config('color_secondary', '#22d3ee') }}" data-craftpick-color="secondary">
                    </label>
                    <label class="craftpick-designer-field">
                        <span>Couleur cartes</span>
                        <input type="color" value="{{ theme_config('surface_color', '#1f115c') }}" data-craftpick-color="surface">
                    </label>
                    <label class="craftpick-designer-field">
                        <span>Couleur fond</span>
                        <input type="color" value="{{ theme_config('background_color', '#14082e') }}" data-craftpick-color="background">
                    </label>
                </div>

                <div class="craftpick-designer-section">
                    <div class="craftpick-designer-section-title">Ambiance</div>
                    <label class="craftpick-designer-range">
                        <span>Arrondi des cartes</span>
                        <input type="range" min="12" max="32" step="1" value="22" data-craftpick-range="radius">
                    </label>
                    <label class="craftpick-designer-range">
                        <span>Intensite des surfaces</span>
                        <input type="range" min="55" max="95" step="1" value="82" data-craftpick-range="surfaceAlpha">
                    </label>
                    <label class="craftpick-designer-check">
                        <input type="checkbox" checked data-craftpick-toggle="glow">
                        <span>Glow hero</span>
                    </label>
                    <label class="craftpick-designer-check">
                        <input type="checkbox" checked data-craftpick-toggle="compactSidebar">
                        <span>Sidebar compacte</span>
                    </label>
                </div>

                <div class="craftpick-designer-actions">
                    <button type="button" class="btn btn-outline-light" data-craftpick-designer-reset>Reinitialiser</button>
                    <button type="button" class="btn btn-primary" data-craftpick-designer-copy>Copier config</button>
                </div>
            </div>
        </aside>
    @endif
@endsection

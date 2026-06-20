@extends('admin.layouts.admin')

@section('content')
    <form action="{{ route('admin.themes.config', $theme) }}" method="POST">
        @csrf

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">{{ trans('theme::messages.config.designer') }}</h1>
                <p class="text-body-secondary mb-0">Configure le layout, les couleurs, les animations, le loader et les effets du theme.</p>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
            </button>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <strong>{{ trans('theme::messages.config.appearance') }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label" for="colorInput">{{ trans('messages.fields.color') }}</label>
                                <input id="colorInput" name="color" type="text" class="form-control @error('color') is-invalid @enderror" value="{{ old('color', theme_config('color')) }}" placeholder="#7c3aed" required>
                                @error('color')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="colorSecondaryInput">{{ trans('theme::messages.config.color_secondary') }}</label>
                                <input id="colorSecondaryInput" name="color_secondary" type="text" class="form-control @error('color_secondary') is-invalid @enderror" value="{{ old('color_secondary', theme_config('color_secondary')) }}" placeholder="#22d3ee" required>
                                @error('color_secondary')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="surfaceColorInput">{{ trans('theme::messages.config.surface_color') }}</label>
                                <input id="surfaceColorInput" name="surface_color" type="text" class="form-control @error('surface_color') is-invalid @enderror" value="{{ old('surface_color', theme_config('surface_color')) }}" placeholder="#1f115c" required>
                                @error('surface_color')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="backgroundColorInput">{{ trans('theme::messages.config.background_color') }}</label>
                                <input id="backgroundColorInput" name="background_color" type="text" class="form-control @error('background_color') is-invalid @enderror" value="{{ old('background_color', theme_config('background_color')) }}" placeholder="#14082e" required>
                                @error('background_color')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="heroBackgroundInput">{{ trans('theme::messages.config.hero_background') }}</label>
                                <input id="heroBackgroundInput" name="hero_background" type="url" class="form-control @error('hero_background') is-invalid @enderror" value="{{ old('hero_background', theme_config('hero_background')) }}" placeholder="https://.../background.jpg">
                                @error('hero_background')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="heroIconInput">{{ trans('theme::messages.config.hero_icon') }}</label>
                                <input id="heroIconInput" name="hero_icon" type="url" class="form-control @error('hero_icon') is-invalid @enderror" value="{{ old('hero_icon', theme_config('hero_icon')) }}" placeholder="https://.../icon.png">
                                @error('hero_icon')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <strong>{{ trans('theme::messages.config.layout') }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="navPositionInput">{{ trans('theme::messages.config.nav_position') }}</label>
                            <select id="navPositionInput" name="nav_position" class="form-select @error('nav_position') is-invalid @enderror">
                                @foreach(['top', 'side'] as $option)
                                    <option value="{{ $option }}" @selected(old('nav_position', theme_config('nav_position')) === $option)>
                                        {{ trans('theme::messages.options.'.$option) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nav_position')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="pageTransitionInput">{{ trans('theme::messages.config.page_transition') }}</label>
                            <select id="pageTransitionInput" name="page_transition" class="form-select @error('page_transition') is-invalid @enderror">
                                @foreach(['none', 'fade', 'slide'] as $option)
                                    <option value="{{ $option }}" @selected(old('page_transition', theme_config('page_transition')) === $option)>
                                        {{ trans('theme::messages.options.'.$option) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('page_transition')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <input type="hidden" name="enable_background_grid" value="0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="backgroundGridInput" name="enable_background_grid" value="1" @checked(old('enable_background_grid', theme_config('enable_background_grid')))>
                                <label class="form-check-label" for="backgroundGridInput">{{ trans('theme::messages.config.enable_background_grid') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <strong>{{ trans('theme::messages.config.animations') }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="animationLevelInput">{{ trans('theme::messages.config.animation_level') }}</label>
                                <select id="animationLevelInput" name="animation_level" class="form-select @error('animation_level') is-invalid @enderror">
                                    @foreach(['off', 'subtle', 'full', 'cinematic'] as $option)
                                        <option value="{{ $option }}" @selected(old('animation_level', theme_config('animation_level')) === $option)>
                                            {{ trans('theme::messages.options.'.$option) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="heroAnimationInput">{{ trans('theme::messages.config.hero_animation') }}</label>
                                <select id="heroAnimationInput" name="hero_animation" class="form-select @error('hero_animation') is-invalid @enderror">
                                    @foreach(['none', 'float', 'zoom'] as $option)
                                        <option value="{{ $option }}" @selected(old('hero_animation', theme_config('hero_animation')) === $option)>
                                            {{ trans('theme::messages.options.'.$option) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="enable_3d_tilt" value="0">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" role="switch" id="tiltInput" name="enable_3d_tilt" value="1" @checked(old('enable_3d_tilt', theme_config('enable_3d_tilt')))>
                                    <label class="form-check-label" for="tiltInput">{{ trans('theme::messages.config.enable_3d_tilt') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="enable_parallax" value="0">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" role="switch" id="parallaxInput" name="enable_parallax" value="1" @checked(old('enable_parallax', theme_config('enable_parallax')))>
                                    <label class="form-check-label" for="parallaxInput">{{ trans('theme::messages.config.enable_parallax') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="enable_glow" value="0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="glowInput" name="enable_glow" value="1" @checked(old('enable_glow', theme_config('enable_glow')))>
                                    <label class="form-check-label" for="glowInput">{{ trans('theme::messages.config.enable_glow') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="enable_cinematic_intro" value="0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="cinematicIntroInput" name="enable_cinematic_intro" value="1" @checked(old('enable_cinematic_intro', theme_config('enable_cinematic_intro')))>
                                    <label class="form-check-label" for="cinematicIntroInput">{{ trans('theme::messages.config.enable_cinematic_intro') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <strong>Loader</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="hidden" name="loading_screen_enabled" value="0">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="loaderEnabledInput" name="loading_screen_enabled" value="1" @checked(old('loading_screen_enabled', theme_config('loading_screen_enabled')))>
                                    <label class="form-check-label" for="loaderEnabledInput">{{ trans('theme::messages.config.loading_screen_enabled') }}</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="loaderStyleInput">{{ trans('theme::messages.config.loading_screen_style') }}</label>
                                <select id="loaderStyleInput" name="loading_screen_style" class="form-select @error('loading_screen_style') is-invalid @enderror">
                                    @foreach(['spinner', 'bars', 'cinematic'] as $option)
                                        <option value="{{ $option }}" @selected(old('loading_screen_style', theme_config('loading_screen_style')) === $option)>
                                            {{ trans('theme::messages.options.'.$option) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <strong>{{ trans('theme::messages.config.content') }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="heroTitleInput">{{ trans('theme::messages.config.hero_title') }}</label>
                                <input id="heroTitleInput" name="hero_title" type="text" class="form-control @error('hero_title') is-invalid @enderror" value="{{ old('hero_title', theme_config('hero_title')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="serverIpInput">{{ trans('theme::messages.config.server_ip') }}</label>
                                <input id="serverIpInput" name="server_ip" type="text" class="form-control @error('server_ip') is-invalid @enderror" value="{{ old('server_ip', theme_config('server_ip')) }}" placeholder="play.craftpick.mc">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="heroSubtitleInput">{{ trans('theme::messages.config.hero_subtitle') }}</label>
                                <textarea id="heroSubtitleInput" name="hero_subtitle" class="form-control @error('hero_subtitle') is-invalid @enderror" rows="3">{{ old('hero_subtitle', theme_config('hero_subtitle')) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="discordUrlInput">{{ trans('theme::messages.config.discord_url') }}</label>
                                <input id="discordUrlInput" name="discord_url" type="url" class="form-control @error('discord_url') is-invalid @enderror" value="{{ old('discord_url', theme_config('discord_url')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="modesTitleInput">{{ trans('theme::messages.config.modes_title') }}</label>
                                <input id="modesTitleInput" name="modes_title" type="text" class="form-control @error('modes_title') is-invalid @enderror" value="{{ old('modes_title', theme_config('modes_title')) }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="footerTextInput">{{ trans('theme::messages.config.footer_text') }}</label>
                                <textarea id="footerTextInput" name="footer_text" class="form-control @error('footer_text') is-invalid @enderror" rows="3">{{ old('footer_text', theme_config('footer_text')) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="showcaseVideoUrlInput">{{ trans('theme::messages.config.showcase_video_url') }}</label>
                                <input id="showcaseVideoUrlInput" name="showcase_video_url" type="url" class="form-control @error('showcase_video_url') is-invalid @enderror" value="{{ old('showcase_video_url', theme_config('showcase_video_url')) }}" placeholder="https://youtube.com/watch?v=...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="showcaseVideoTitleInput">{{ trans('theme::messages.config.showcase_video_title') }}</label>
                                <input id="showcaseVideoTitleInput" name="showcase_video_title" type="text" class="form-control @error('showcase_video_title') is-invalid @enderror" value="{{ old('showcase_video_title', theme_config('showcase_video_title')) }}" placeholder="Trailer du serveur">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="showcaseVideoTextInput">{{ trans('theme::messages.config.showcase_video_text') }}</label>
                                <textarea id="showcaseVideoTextInput" name="showcase_video_text" class="form-control @error('showcase_video_text') is-invalid @enderror" rows="3">{{ old('showcase_video_text', theme_config('showcase_video_text')) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <strong>Modes</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @for($i = 1; $i <= 4; $i++)
                                <div class="col-md-6">
                                    <label class="form-label" for="mode{{ $i }}NameInput">{{ trans('theme::messages.config.mode_name', ['n' => $i]) }}</label>
                                    <input id="mode{{ $i }}NameInput" name="mode_{{ $i }}_name" type="text" class="form-control @error('mode_'.$i.'_name') is-invalid @enderror" value="{{ old('mode_'.$i.'_name', theme_config('mode_'.$i.'_name')) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="mode{{ $i }}UrlInput">{{ trans('theme::messages.config.mode_url', ['n' => $i]) }}</label>
                                    <input id="mode{{ $i }}UrlInput" name="mode_{{ $i }}_url" type="text" class="form-control @error('mode_'.$i.'_url') is-invalid @enderror" value="{{ old('mode_'.$i.'_url', theme_config('mode_'.$i.'_url')) }}">
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

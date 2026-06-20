<footer class="craftpick-footer mt-auto py-5">
    <div class="container">
        <div class="row gy-4 align-items-start">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="craftpick-brand-mark craftpick-brand-mark-lg"></span>
                    <strong class="fs-5">{{ site_name() }}</strong>
                </div>
                <p class="text-secondary mb-0">
                    {{ theme_config('footer_text') }}
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                @if(theme_config('discord_url'))
                    <a class="btn btn-primary" href="{{ theme_config('discord_url') }}" target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-discord"></i> {{ trans('theme::messages.home.cta_discord') }}
                    </a>
                @endif

                <div class="mt-3">
                    @foreach(social_links() as $link)
                        <a href="{{ $link->value }}" title="{{ $link->title }}" target="_blank" rel="noopener noreferrer" data-bs-toggle="tooltip" class="craftpick-social">
                            <i class="{{ $link->icon }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <hr class="my-4 craftpick-divider">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <small class="text-secondary">
                {{ str_replace('{year}', date('Y'), setting('copyright')) }}
            </small>
            <small class="text-secondary">
                {{ trans('messages.copyright') }}
            </small>
        </div>
    </div>
</footer>

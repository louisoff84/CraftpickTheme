<nav class="navbar navbar-expand-lg craftpick-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="craftpick-brand-logo">
            <div class="d-flex flex-column lh-1">
                <span class="craftpick-brand-name">{{ site_name() }}</span>
                <small class="craftpick-brand-subtitle">Minecraft Network</small>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="{{ trans('messages.nav.toggle') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                @foreach($navbar as $element)
                    @if(!$element->isDropdown())
                        <li class="nav-item">
                            <a class="nav-link @if($element->isCurrent()) active @endif" href="{{ $element->getLink() }}" @if($element->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                <i class="bi bi-chevron-right craftpick-nav-icon"></i>{{ $element->name }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @if($element->isCurrent()) active @endif" href="#" id="navbarDropdown{{ $element->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $element->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown{{ $element->id }}">
                                @foreach($element->elements as $childElement)
                                    <li>
                                        <a class="dropdown-item @if($childElement->isCurrent()) active @endif" href="{{ $childElement->getLink() }}" @if($childElement->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                            {{ $childElement->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>

            <div class="d-flex align-items-center gap-2">
                @include('elements.theme-selector', ['defaultDark' => true])

                @guest
                    <a class="btn btn-outline-light" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
                    </a>
                    @if(Route::has('register'))
                        <a class="btn btn-primary" href="{{ route('register') }}">
                            <i class="bi bi-person-plus-fill"></i> {{ trans('auth.register') }}
                        </a>
                    @endif
                @else
                    @include('elements.notifications')
                    <div class="dropdown">
                        <a class="btn btn-outline-light dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person"></i> {{ trans('messages.nav.profile') }}
                                </a>
                            </li>
                            @foreach(plugins()->getUserNavItems() ?? [] as $navItem)
                                <li>
                                    <a class="dropdown-item" href="{{ route($navItem['route']) }}">
                                        <i class="{{ $navItem['icon'] ?? 'bi bi-three-dots' }}"></i> {{ $navItem['name'] }}
                                    </a>
                                </li>
                            @endforeach
                            @if(Auth::user()->hasAdminAccess())
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> {{ trans('messages.nav.admin') }}
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> {{ trans('auth.logout') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>

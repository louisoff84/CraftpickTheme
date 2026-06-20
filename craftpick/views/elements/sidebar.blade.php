@php
    $navbar = $navbar ?? (view()->make('elements.navbar')->getData()['navbar'] ?? collect());
@endphp

<aside class="craftpick-sidebar">
    <div class="craftpick-sidebar-inner">
        <a class="craftpick-sidebar-brand" href="{{ route('home') }}">
            <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="craftpick-brand-logo">
            <div class="d-flex flex-column lh-1">
                <span class="craftpick-brand-name">{{ site_name() }}</span>
                <small class="craftpick-brand-subtitle">Minecraft Network</small>
            </div>
        </a>

        <nav class="mt-4">
            <ul class="nav flex-column gap-2">
                @foreach($navbar as $element)
                    @if(!$element->isDropdown())
                        <li class="nav-item">
                            <a class="nav-link craftpick-sidebar-link @if($element->isCurrent()) active @endif" href="{{ $element->getLink() }}" @if($element->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                <i class="bi bi-chevron-right craftpick-nav-icon"></i>{{ $element->name }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <div class="craftpick-sidebar-group">{{ $element->name }}</div>
                            <ul class="nav flex-column gap-2 mt-2">
                                @foreach($element->elements as $childElement)
                                    <li class="nav-item">
                                        <a class="nav-link craftpick-sidebar-link @if($childElement->isCurrent()) active @endif" href="{{ $childElement->getLink() }}" @if($childElement->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                            <i class="bi bi-dot"></i>{{ $childElement->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>

        <div class="craftpick-sidebar-footer mt-auto">
            <div class="craftpick-sidebar-theme mb-3">
                @include('elements.theme-selector', ['defaultDark' => true])
            </div>

            <ul class="nav flex-column gap-2">
                @guest
                    <li class="nav-item">
                        <a class="nav-link craftpick-sidebar-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i>{{ trans('auth.login') }}
                        </a>
                    </li>
                    @if(Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link craftpick-sidebar-link craftpick-sidebar-link-primary" href="{{ route('register') }}">
                                <i class="bi bi-person-plus-fill"></i>{{ trans('auth.register') }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link craftpick-sidebar-link" href="{{ route('profile.index') }}">
                            <i class="bi bi-person"></i>{{ Auth::user()->name }}
                        </a>
                    </li>
                    @if(Auth::user()->hasAdminAccess())
                        <li class="nav-item">
                            <a class="nav-link craftpick-sidebar-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i>{{ trans('messages.nav.admin') }}
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link craftpick-sidebar-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>{{ trans('auth.logout') }}
                        </a>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </ul>
        </div>
    </div>
</aside>

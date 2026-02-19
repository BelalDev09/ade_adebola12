{{-- SIDEBAR --}}
<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('Backend/assets/images/logo-sm.png') }}" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('Backend/assets/images/logo-dark.png') }}" height="17">
            </span>
        </a>

        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('Backend/assets/images/logo-sm.png') }}" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('Backend/assets/images/logo-light.png') }}" height="17">
            </span>
        </a>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <ul class="navbar-nav" id="navbar-nav">

                {{-- MENU --}}
                <li class="menu-title"><span>Menu</span></li>

                {{-- DASHBOARD --}}
                @can('dashboard.view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="ri-dashboard-2-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endcan

                {{-- CONTENT --}}
                <li class="menu-title"><span>Content</span></li>

                {{-- USER MANAGEMENT --}}
                @can('users.manage')
                @php
                $isUserOpen = request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*');
                @endphp
                <li class="nav-item">
                    <a class="nav-link menu-link {{ $isUserOpen ? '' : 'collapsed' }}" href="#sidebarUser"
                        data-bs-toggle="collapse" aria-expanded="{{ $isUserOpen ? 'true' : 'false' }}">
                        <i class="ri-user-3-line"></i>
                        <span>User Management</span>
                    </a>
                    <div class="collapse menu-dropdown {{ $isUserOpen ? 'show' : '' }}" id="sidebarUser">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                    Users List
                                </a>
                            </li>
                            @can('roles.manage')
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">
                                    Roles
                                </a>
                            </li>
                            @endcan
                            @can('permissions.manage')
                            <li class="nav-item">
                                <a href="{{ route('admin.permissions.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.permissions.index') ? 'active' : '' }}">
                                    Permissions
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcan

                {{-- CONTENT SECTION --}}
                @php
                $isContentOpen =
                request()->routeIs('backend.admin.reviews.*') ||
                request()->routeIs('backend.admin.reports.*') ||
                request()->routeIs('backend.admin.contact');
                @endphp
                @if (auth()->user()?->can('reviews.manage') || auth()->user()?->can('reports.manage'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ $isContentOpen ? '' : 'collapsed' }}" href="#sidebarContent"
                        data-bs-toggle="collapse" aria-expanded="{{ $isContentOpen ? 'true' : 'false' }}">
                        <i class="ri-shopping-cart-2-line"></i>
                        <span>Content Section</span>
                    </a>
                    <div class="collapse menu-dropdown {{ $isContentOpen ? 'show' : '' }}" id="sidebarContent">
                        <ul class="nav nav-sm flex-column">
                            @can('reviews.manage')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.reviews.index') }}"
                                    class="nav-link {{ request()->routeIs('backend.admin.reviews.index') ? 'active' : '' }}">
                                    <i class="ri-chat-check-line me-1"></i> Reviews
                                </a>
                            </li>
                            @endcan
                            @can('reports.manage')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.reports.index') }}"
                                    class="nav-link {{ request()->routeIs('backend.admin.reports.index') ? 'active' : '' }}">
                                    <i class="ri-file-warning-line me-1"></i> Reports Reviews
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endif

                {{-- DYNAMIC PAGE --}}
                @can('support.manage')
                @php
                $isDynamicOpen = request()->routeIs('admin.support.*');
                @endphp
                <li class="nav-item">
                    <a class="nav-link menu-link {{ $isDynamicOpen ? '' : 'collapsed' }}" href="#sideDynamic"
                        data-bs-toggle="collapse" aria-expanded="{{ $isDynamicOpen ? 'true' : 'false' }}">
                        <i class="ri-file-list-3-line"></i>
                        <span>Dynamic Page</span>
                    </a>
                    <div class="collapse menu-dropdown {{ $isDynamicOpen ? 'show' : '' }}" id="sideDynamic">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.support.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.support.index') ? 'active' : '' }}">
                                    Users Support
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan

                {{-- PAGES --}}
                <li class="menu-title"><span>Pages</span></li>

                {{-- LANDING PAGE CMS --}}
                @can('cms.manage')
                @php
                $isLandingOpen = request()->routeIs('cms.*') || request()->routeIs('backend.cms.index');
                @endphp
                <li class="nav-item">
                    <a class="nav-link menu-link {{ $isLandingOpen ? 'active' : '' }}" href="#sidebarLanding"
                        data-bs-toggle="collapse" aria-expanded="{{ $isLandingOpen ? 'true' : 'false' }}">
                        <i class="ri-pages-line"></i>
                        <span>Landing Page</span>
                    </a>
                    <div class="collapse menu-dropdown {{ $isLandingOpen ? 'show' : '' }}" id="sidebarLanding">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('cms.index') }}"
                                    class="nav-link {{ request()->routeIs('cms.index') ? 'active' : '' }}">
                                    <i class="ri-home-4-line me-1"></i> Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cms.hero.form') }}"
                                    class="nav-link {{ request()->routeIs('cms.hero.*') ? 'active' : '' }}">
                                    <i class="ri-layout-top-line me-1"></i> Hero
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cms.how-it-works.form') }}"
                                    class="nav-link {{ request()->routeIs('cms.how-it-works.*') ? 'active' : '' }}">
                                    <i class="ri-question-answer-line me-1"></i> How It Works
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cms.market-tools.index') }}"
                                    class="nav-link {{ request()->routeIs('cms.market-tools.*') ? 'active' : '' }}">
                                    <i class="ri-bar-chart-2-line me-1"></i> Market
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cms.testimonials.form') }}"
                                    class="nav-link {{ request()->routeIs('cms.testimonials.*') ? 'active' : '' }}">
                                    <i class="ri-star-smile-line me-1"></i> Testimonials
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cms.who-for.index') }}"
                                    class="nav-link {{ request()->routeIs('cms.who-for.*') ? 'active' : '' }}">
                                    <i class="ri-thumb-up-line me-1"></i> Why Choose Us
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan

                {{-- SETTINGS --}}
                @php
                $isSettingsOpen =
                request()->routeIs('profile.*') ||
                request()->routeIs('admin.smtp.*') ||
                request()->routeIs('backend.admin.account.*') ||
                request()->routeIs('backend.admin.contact');
                @endphp
                <li class="nav-item">
                    <a class="nav-link menu-link {{ $isSettingsOpen ? '' : 'collapsed' }}" href="#sidebarSettings"
                        data-bs-toggle="collapse" aria-expanded="{{ $isSettingsOpen ? 'true' : 'false' }}">
                        <i class="ri-settings-3-line"></i>
                        <span>Settings</span>
                    </a>
                    <div class="collapse menu-dropdown {{ $isSettingsOpen ? 'show' : '' }}" id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            @can('profile.view')
                            <li class="nav-item">
                                <a href="{{ route('profile.show') }}"
                                    class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                                    <i class="ri-user-line me-1"></i> Profile
                                </a>
                            </li>
                            @endcan
                            @can('profile.edit')
                            <li class="nav-item">
                                <a href="{{ route('profile.edit') }}"
                                    class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                                    <i class="ri-user-settings-line me-1"></i> Profile Settings
                                </a>
                            </li>
                            @endcan
                            @can('settings.smtp')
                            <li class="nav-item">
                                <a href="{{ route('admin.smtp.index') }}"
                                    class="nav-link {{ request()->routeIs('smtp.*') ? 'active' : '' }}">
                                    <i class="ri-mail-line me-1"></i> SMTP
                                </a>
                            </li>
                            @endcan

                            @can('settings.account')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.account.edit') }}"
                                    class="nav-link {{ request()->routeIs('backend.admin.account.*') ? 'active' : '' }}">
                                    <i class="ri-shield-user-line me-1"></i> Account Setting
                                </a>
                            </li>
                            @endcan
                            @can('cms.manage')
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.contact') }}" class="nav-link">
                                    <i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> Contact Us
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
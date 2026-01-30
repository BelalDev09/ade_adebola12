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
                <li class="menu-title">
                    <span>Menu</span>
                </li>

                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="ri-dashboard-2-line"></i>
                        <span>Dashboard</_toggle>
                    </a>
                </li>

                {{-- PAGES --}}
                <li class="menu-title">
                    <span>Pages</span>
                </li>

                {{-- LANDING PAGE CMS --}}
                <li class="nav-item">
                    @php
                        // Landing Page collapse should stay open for all CMS routes including Home
                        $isLandingActive = request()->routeIs('backend.cms.index') || request()->routeIs('cms.*');
                    @endphp

                    <a class="nav-link menu-link {{ $isLandingActive ? 'active' : '' }}" href="#sidebarLanding"
                        data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $isLandingActive ? 'true' : 'false' }}" aria-controls="sidebarLanding">
                        <i class="ri-pages-line"></i>
                        <span>Landing Page</span>
                    </a>

                    <div class="collapse menu-dropdown {{ $isLandingActive ? 'show' : '' }}" id="sidebarLanding">
                        <ul class="nav nav-sm flex-column">

                            {{-- HOME / OVERVIEW --}}
                            <li class="nav-item">
                                <a href="{{ route('cms.index') }}"
                                    class="nav-link {{ request()->routeIs('cms.index') ? 'active' : '' }}">
                                    <i class="ri-home-4-line me-1"></i> Home
                                </a>

                            </li>

                            {{-- HERO --}}
                            <li class="nav-item">
                                <a href="{{ route('cms.hero.form') }}"
                                    class="nav-link {{ request()->routeIs('cms.hero.*') ? 'active' : '' }}">
                                    <i class="ri-layout-top-line me-1"></i> Hero
                                </a>
                            </li>

                            {{-- HOW IT WORKS --}}
                            <li class="nav-item">
                                <a href="{{ route('cms.how-it-works.form') }}"
                                    class="nav-link {{ request()->routeIs('cms.how-it-works.*') ? 'active' : '' }}">
                                    <i class="ri-question-answer-line me-1"></i> How It Works
                                </a>
                            </li>

                            {{-- MARKET --}}
                            <li class="nav-item">
                                <a href="{{ route('cms.market-tools.index') }}"
                                    class="nav-link {{ request()->routeIs('cms.market-tools.*') ? 'active' : '' }}">
                                    <i class="ri-bar-chart-2-line me-1"></i> Market
                                </a>

                            </li>

                            {{-- TESTIMONIALS --}}
                            <li class="nav-item">
                                <a href="{{ route('cms.testimonials.form') }}"
                                    class="nav-link {{ request()->routeIs('cms.testimonials.*') ? 'active' : '' }}">
                                    <i class="ri-star-smile-line me-1"></i> Testimonials
                                </a>
                            </li>

                            {{-- WHO FOR US --}}
                            <li class="nav-item">
                                <a href="{{ route('cms.who-for.index') }}"
                                    class="nav-link {{ request()->routeIs('cms.who-for.*') ? 'active' : '' }}">
                                    <i class="ri-thumb-up-line me-1"></i> Why Choose Us
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
                {{-- ECOMMERCE --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarEcommerce" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->is('admin/ecommerce*') ? 'true' : 'false' }}"
                        aria-controls="sidebarEcommerce">
                        <i class="ri-shopping-cart-2-line"></i>
                        <span>Setting</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->is('admin/ecommerce*') ? 'show' : '' }}"
                        id="sidebarEcommerce">
                        <ul class="nav nav-sm flex-column">
                            {{-- profile --}}
                            <li class="nav-item">
                                <a href="{{ route('profile.edit') }}"
                                    class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                    <i class="ri-user-line"></i>
                                    <span>Profile</span>
                                </a>
                            </li>
                            <li class="nav-smtp">
                                <a href="{{ route('admin.smtp.index') }}"
                                    class="nav-link
                                    {{ request()->routeIs('smtp.*') ? 'active' : '' }}">
                                    <span>Smtp</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.account.edit') }}" class="nav-link">
                                    Account setting
                                </a>
                            </li>
                            {{-- contract us --}}
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.contact') }}" class="nav-link">
                                    <i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i>Contruct Us
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- PROFILE --}}
                <li class="menu-title">
                    <span>Content</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sidebarUser" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarLanding">
                        <i class="ri-rocket-line"></i> <span data-key="t-landing">User Management</span>
                    </a>
                    <div class="menu-dropdown collapse" id="sidebarUser" style="">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}" class="nav-link">
                                    Users list
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarContent" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->is('admin/ecommerce*') ? 'true' : 'false' }}"
                        aria-controls="sidebarContent">
                        <i class="ri-shopping-cart-2-line"></i>
                        <span>Content Section</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->is('admin/ecommerce*') ? 'show' : '' }}"
                        id="sidebarContent">
                        <ul class="nav nav-sm flex-column">
                            {{-- reviews --}}

                            <li class="nav-item">
                                <a href="{{ route('backend.admin.reviews.index') }}" class="nav-link">
                                    Reviews
                                </a>
                            </li>
                            {{-- reports --}}
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.reports.index') }}" class="nav-link">
                                    Reports reviews
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ asset('Backend/assets/pages/apps-ecommerce-customers.html') }}"
                                    class="nav-link">
                                    Customers
                                </a>
                            </li>
                            {{-- contract us --}}
                            <li class="nav-item">
                                <a href="{{ route('backend.admin.contact') }}" class="nav-link">
                                    <i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i>Contruct Us
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link collapsed" href="#sideDynamic" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarLanding">
                        <i class="ri-rocket-line"></i> <span data-key="t-landing">Dynamic Page</span>
                    </a>
                    <div class="menu-dropdown collapse" id="sideDynamic" style="">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.support.index') }}" class="nav-link">
                                    Users support
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>

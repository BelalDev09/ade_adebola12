<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Laravel 12 User Roles and Permissions Tutorial
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li><a class="nav-link" href="{{ route('users.index') }}">Manage Users</a></li>
                            <li><a class="nav-link" href="{{ route('roles.index') }}">Manage Role</a></li>
                            <li><a class="nav-link" href="{{ route('products.index') }}">Manage Product</a></li>
                            <li><a class="nav-link" href="{{ route('categories.index') }}">Manage Category</a></li>
                            {{-- <li><a class="nav-link" href="{{ route('notifications.index') }}">Manage Notifications</a></li> --}}
                            {{-- notifications --}}
                            @php
                                $unreadCount = auth()->user()->unreadNotifications->count();
                            @endphp

                            <li class="nav-item dropdown">
                                <a id="notificationDropdown" class="nav-link position-relative" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                    <i class="fa-solid fa-bell"></i>

                                    @if ($unreadCount > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-end p-2" style="width: 300px">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>Notifications</strong>

                                        @if ($unreadCount > 0)
                                            <a href="{{ route('notifications.markAllRead') }}"
                                                class="text-sm text-decoration-none text-red-400">
                                                Mark all read
                                            </a>
                                        @endif
                                    </div>

                                    <hr>

                                    @forelse (auth()->user()->notifications->take(5) as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                            class="dropdown-item {{ $notification->read_at ? '' : 'fw-bold' }}">

                                            {{ $notification->data['title'] ?? 'Notification' }}

                                            @if (!$notification->read_at)
                                                <span class="badge bg-primary ms-2">New</span>
                                            @endif
                                        </a>
                                    @empty
                                        <div class="dropdown-item text-muted">No notifications</div>
                                    @endforelse
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>
</body>

</html>

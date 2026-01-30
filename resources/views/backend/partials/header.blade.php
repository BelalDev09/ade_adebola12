    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    @yield('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="shortcut icon" href="{{ asset('Backend/assets/images/favicon.ico') }}">

    <!-- CSS Libraries -->
    <link href="{{ asset('Backend/assets/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('Backend/assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />

    <!-- Core CSS -->
    <link href="{{ asset('Backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('Backend/assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('Backend/assets/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('Backend/assets/css/custom.min.css') }}" rel="stylesheet" />


    <!-- Layout JS -->
    {{-- <script src="{{ asset('Backend/assets/js/layout.js') }}"></script> --}}

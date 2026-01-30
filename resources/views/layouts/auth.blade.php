<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Auth')</title>

    <link rel="shortcut icon" href="{{ asset('Backend/assets/images/favicon.ico') }}">
    <link href="{{ asset('Backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Backend/assets/css/app.min.css') }}" rel="stylesheet">
</head>

<body>

    @yield('content')

    <script src="{{ asset('Backend/assets/js/app.js') }}"></script>
</body>

</html>

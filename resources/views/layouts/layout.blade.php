<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="png" href="{{ asset('images/favgiatlogo.png') }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/sidebarstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/snstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/newdashboardstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/newwarroom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/newsummary.css') }}">
    <link rel="stylesheet" href="{{ asset('css/logstyle.css') }}">
    @stack('styles')
</head>
<body>

    @include('layouts.newsidebar')

    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>
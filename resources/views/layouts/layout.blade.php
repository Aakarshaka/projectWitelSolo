<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/sidebarstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboardstyle.css') }}">

    <script type="text/javascript" src="{{ asset('js/sidebarscript.js') }}" defer></script>
</head>

<body>

    @include('layouts.newsidebar')

    <main>
        @yield('content')
    </main>

</body>

</html>
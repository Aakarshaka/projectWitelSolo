<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tablestyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/summarystyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboardstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/warroomstyle.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    @include('layouts.sidebar')

    <div class="main-content">
        @yield('content')
    </div>

    <script>
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function () {
                document.querySelectorAll('.nav-item').forEach(navItem => {
                    navItem.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>

</body>
</html>

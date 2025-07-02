<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tablestyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/formstyle.css') }}">
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

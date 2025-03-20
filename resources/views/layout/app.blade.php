<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- CSS -->
    <link href="{{ asset('template/assets/vendor/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/vendor/fontawesome/css/solid.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/vendor/fontawesome/css/brands.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/master.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/vendor/flagiconcss/css/flag-icon.min.css') }}" rel="stylesheet">
</head>

<body>


    @yield('content')

    <!-- JS -->
    <script src="{{ asset('template/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/chartsjs/Chart.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/dashboard-charts.js') }}"></script>
    <script src="{{ asset('template/assets/js/script.js') }}"></script>

    @yield('script')
</body>

</html>



<!-- Konten halaman -->
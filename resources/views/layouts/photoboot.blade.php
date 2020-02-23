<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CatLab Photobooth</title>

    @yield('head')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('res/photobooth/css/app.css') }}" rel="stylesheet">

@include('blocks.favicon')


</head>
<body>

<script>
    var ORGANISATION_ID = {{ $organisation->id }};
</script>

@include('blocks.gtm')
@include('blocks.airbrake')

<div id="app" style="min-height: 1000px">
    @yield('content')
</div>

@yield('footer')
<script src="{{ mix('res/photobooth/js/app.js') }}"></script>
</body>
</html>

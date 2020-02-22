<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CatLab Photobooth</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        @include('blocks.favicon')

    @if(config('services.gtm'))
        <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','{{ config('services.gtm') }}');</script>
            <!-- End Google Tag Manager -->
        @endif
    </head>
    <body>

    @if(config('services.gtm'))
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.gtm') }}"
                          height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif

    <header>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container d-flex justify-content-between">
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <strong>CatLab Photobooth</strong>
                </a>
            </div>
        </div>
    </header>

    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <br />
                <h1>CatLab Photobooth</h1>
                <p>
                    Open source photobooth. Source available at
                    <a href="https://github.com/catlabinteractive/catlab-photobooth">GitHub</a>.
                </p>

                <p>
                    <a href="{{ action('PhotoboothController@index') }}" class="btn btn-primary">Launch photobooth</a>
                </p>

            </div>

        </div>

    </div>

    </body>
</html>

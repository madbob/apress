<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ env('APP_NAME') }}</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <meta property="og:site_name" content="apress" />
        <meta property="og:title" content="A simple Twitter scheduler" />
        <meta property="og:url" content="http://apress.madbob.org/" />
        <meta property="og:image" content="http://apress.madbob.org/img/fb.png" />
        <meta property="og:type" content="website" />
        <meta property="og:country-name" content="Italy" />
        <meta property="og:email" content="bob@linux.it" />
        <meta property="og:locale" content="it_IT" />

        <meta name="twitter:title" content="apress" />
        <meta name="twitter:creator" content="@madbob" />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:url" content="http://apress.madbob.org/" />
    </head>
    <body>
        @yield('contents')
    </body>

    <script src="{{ mix('js/app.js') }}"></script>
</html>

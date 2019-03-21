<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>window.Laravel = {csrfToken:'{{csrf_token()}}'}</script>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <script src="https://www.google.com/recaptcha/api.js?render=6Ld7M5AUAAAAAEl3J6SLIsF2PSUgNi5PDyleRU6q"></script>
        
    </head>
    <body>
        <div id="app">
            @csrf
            <router-view class="view one" name="nav" style="margin-bottom:2%"></router-view>
            <router-view class="view two" name="con"></router-view>
        </div>

        <script src="{{asset('js/app.js')}}"> </script>
    </body>
</html>

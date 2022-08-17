<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
        @vite('resources/scripts/admin.ts')
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>

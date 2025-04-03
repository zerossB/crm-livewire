<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-base-200">
    <x-theme-toggle/>

    <div>
        <x-app-brand/>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-base-100 shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>

{{--  TOAST area --}}
<x-toast/>
</body>
</html>

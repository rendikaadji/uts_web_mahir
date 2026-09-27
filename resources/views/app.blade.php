<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F8FAFC] text-[#07132B] antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MTsN 32 Jakarta') }} - Perpustakaan & Presensi Digital</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-mtsn32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-mtsn32.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:400,500,600,700|plus-jakarta-sans:400,500,600,700" rel="stylesheet" />

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/main.jsx'])
</head>
<body class="h-full font-sans bg-[#F8FAFC] text-[#07132B] flex flex-col selection:bg-[#0B1B3D] selection:text-white">
    <div id="root" class="h-full flex flex-col flex-1"></div>
</body>
</html>

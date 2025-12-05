<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        {{-- Alerts globais em overlay, por cima do conteúdo --}}
        <div
            class="fixed right-0 top-20 z-50 flex justify-center px-4 pointer-events-none">
            @if (session('success'))
            <div class="pointer-events-auto">
                <x-alert type="success" :message="session('success')" />
            </div>
            @endif

            @if (session('error'))
            <div class="pointer-events-auto">
                <x-alert type="error" :message="session('error')" />
            </div>
            @endif

            @if ($errors->any())
            <div class="pointer-events-auto">
                <x-alert type="error" title="Verifique os dados informados">
                    Existem campos que precisam ser corrigidos.
                </x-alert>
            </div>
            @endif
        </div>


        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
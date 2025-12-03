<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Meu Controle') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-100">

<div class="min-h-screen flex flex-col items-center justify-center px-4">

    {{-- Logo / título --}}
    <div class="mb-8 text-center">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-slate-900 text-white text-sm font-semibold shadow">
            Meu Controle
        </div>
        <p class="mt-3 text-sm text-slate-500">
            Organize receitas, despesas e parcelas em um só lugar.
        </p>
    </div>

    {{-- Card principal --}}
    <div class="w-full max-w-md">
        <div class="bg-white shadow-lg rounded-2xl border border-slate-200 p-6 sm:p-8">
            {{ $slot }}
        </div>

        <p class="mt-4 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Meu Controle. Todos os direitos reservados.
        </p>
    </div>
</div>

</body>
</html>

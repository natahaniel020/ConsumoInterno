<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google" content="notranslate">

    <title>@yield('title', 'ConsumoInterno') — {{ config('app.name') }}</title>

    {{-- ── Fuentes ─────────────────────────────────────────────────────── --}}
    {{-- Cargadas via app.css para evitar bloqueo de render --}}

    {{-- ── Estilos adicionales por vista ─────────────────────────────── --}}
    @stack('styles')

    {{-- ── Vite: CSS + JS principal ───────────────────────────────────── --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-on-background antialiased">

    {{-- ── Sidebar fijo ───────────────────────────────────────────────── --}}
    @include('layouts.sedebar')

    {{-- ── Wrapper principal (desplazado por el sidebar) ─────────────── --}}
    <div class="ml-64 min-h-screen flex flex-col">

        {{-- ── Navbar sticky ─────────────────────────────────────────── --}}
        @include('layouts.navbar')

        {{-- ── Mensajes flash ────────────────────────────────────────── --}}
        @if (session('success') || session('error'))
            <div class="px-xl pt-lg -mb-md">
                @if (session('success'))
                    <div class="flex items-center gap-sm
                                px-md py-sm rounded-lg
                                bg-emerald-50 border border-emerald-200
                                text-emerald-800 font-body-md text-body-md">
                        <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="flex items-center gap-sm
                                px-md py-sm rounded-lg
                                bg-error-container border border-red-200
                                text-on-error-container font-body-md text-body-md">
                        <span class="material-symbols-outlined text-[18px]">error</span>
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        {{-- ── Contenido principal ────────────────────────────────────── --}}
        <main class="flex-1 p-xl max-w-container-max w-full mx-auto">
            @yield('content')
        </main>

    </div>

    {{-- ── Scripts adicionales por vista ─────────────────────────────── --}}
    @stack('scripts')

</body>
</html>
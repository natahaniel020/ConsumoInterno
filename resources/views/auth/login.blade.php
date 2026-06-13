<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-container-low min-h-screen flex items-center justify-center p-md overflow-hidden">

<main class="w-full max-w-[440px]">

    <div class="bg-surface-container-lowest rounded-2xl shadow-md border border-outline-variant overflow-hidden">

        {{-- ── Branding ────────────────────────────────────────────────────── --}}
        <div class="px-xl pt-xl pb-lg text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-xl mb-md">
                <span class="material-symbols-outlined text-on-primary text-[32px]">inventory_2</span>
            </div>
            <h1 class="font-headline-md text-headline-md text-on-surface mb-xs">ConsumoInterno</h1>
            <p class="font-body-md text-body-md text-secondary">Gestiona solicitudes de suministros fácilmente</p>
        </div>

        {{-- ── Session status ──────────────────────────────────────────────── --}}
        @if (session('status'))
            <div class="px-xl mb-sm">
                <div class="bg-secondary-container text-on-secondary-container p-md rounded-lg
                            flex items-start gap-sm border border-outline-variant">
                    <span class="material-symbols-outlined text-body-lg">check_circle</span>
                    <span class="font-label-md text-label-md">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        {{-- ── Errores de validación ───────────────────────────────────────── --}}
        @if ($errors->any())
            <div class="px-xl mb-sm">
                <div class="bg-error-container text-on-error-container p-md rounded-lg
                            flex items-start gap-sm border border-red-200">
                    <span class="material-symbols-outlined text-body-lg">error</span>
                    <span class="font-label-md text-label-md">{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        {{-- ── Formulario ──────────────────────────────────────────────────── --}}
        <form method="POST" action="{{ route('login') }}" class="px-xl pb-xl space-y-lg">
            @csrf

            {{-- Email --}}
            <div class="space-y-xs">
                <label class="font-label-md text-label-md text-on-surface-variant block ml-1" for="email">
                    Correo electrónico
                </label>
                <div class="relative group">
                    <div class="absolute left-md top-1/2 -translate-y-1/2">
                        <span class="material-symbols-outlined text-outline group-focus-within:text-primary transition-colors">mail</span>
                    </div>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="usuario@empresa.com"
                           required autofocus autocomplete="username"
                           class="w-full h-12 pl-11 pr-md
                                  bg-surface-container-low border border-outline-variant rounded-lg
                                  font-body-md text-body-md
                                  focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                  transition-all placeholder:text-outline">
                </div>
            </div>

            {{-- Contraseña --}}
            <div class="space-y-xs">
                <div class="flex justify-between items-center px-1">
                    <label class="font-label-md text-label-md text-on-surface-variant" for="password">
                        Contraseña
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="font-label-md text-label-md text-primary hover:underline">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>
                <div class="relative group">
                    <div class="absolute left-md top-1/2 -translate-y-1/2">
                        <span class="material-symbols-outlined text-outline group-focus-within:text-primary transition-colors">lock</span>
                    </div>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••"
                           required autocomplete="current-password"
                           class="w-full h-12 pl-11 pr-11
                                  bg-surface-container-low border border-outline-variant rounded-lg
                                  font-body-md text-body-md
                                  focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                                  transition-all placeholder:text-outline">
                    <button type="button"
                            onclick="togglePassword()"
                            class="absolute right-md top-1/2 -translate-y-1/2
                                   text-outline hover:text-on-surface-variant transition-colors">
                        <span id="password-toggle-icon" class="material-symbols-outlined">visibility</span>
                    </button>
                </div>
            </div>

            {{-- Recuérdame --}}
            <div class="flex items-center gap-sm px-1">
                <input type="checkbox" id="remember_me" name="remember"
                       class="w-5 h-5 rounded border-outline-variant text-primary
                              focus:ring-primary cursor-pointer">
                <label for="remember_me"
                       class="font-body-md text-body-md text-on-surface-variant cursor-pointer select-none">
                    Recordar este dispositivo
                </label>
            </div>

            {{-- Botón submit --}}
            <button type="submit"
                    class="w-full h-12 bg-primary text-on-primary
                           font-label-md text-label-md uppercase tracking-wider
                           rounded-lg shadow-sm
                           hover:opacity-90 active:scale-[0.98] transition-all
                           flex items-center justify-center gap-sm">
                <span>Iniciar sesión</span>
                <span class="material-symbols-outlined text-[18px]">login</span>
            </button>

            {{-- Footer --}}
            <div class="pt-md text-center">
                <p class="font-label-md text-label-md text-secondary">
                    Acceso interno exclusivo.<br>
                    ¿Necesitas ayuda?
                    <a href="mailto:admin@empresa.com" class="text-primary hover:underline">
                        Contacta al administrador
                    </a>
                </p>
            </div>

        </form>
    </div>

    {{-- Versión --}}
    <div class="mt-lg text-center">
        <div class="flex items-center justify-center gap-xs opacity-60">
            <span class="font-label-sm text-label-sm text-on-surface">CONSUMO INTERNO</span>
            <span class="w-1 h-1 bg-outline rounded-full"></span>
            <span class="font-label-sm text-label-sm text-on-surface">V 1.0.0</span>
        </div>
    </div>

</main>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('password-toggle-icon');
        if (input.type === 'password') {
            input.type     = 'text';
            icon.innerText = 'visibility_off';
        } else {
            input.type     = 'password';
            icon.innerText = 'visibility';
        }
    }
</script>

</body>
</html>
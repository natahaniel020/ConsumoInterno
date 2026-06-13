{{--
|─────────────────────────────────────────────────────────────────────────────
| navbar.blade.php
|
| Barra superior sticky compartida por todos los roles.
| Muestra: logo del sistema, buscador (decorativo por ahora),
| iconos de acción, nombre del usuario autenticado y logout.
|─────────────────────────────────────────────────────────────────────────────
--}}

<header class="sticky top-0 z-50
               flex items-center justify-between
               px-lg py-sm w-full
               bg-surface-container-lowest
               border-b border-outline-variant shadow-sm">

    {{-- ── Logo del sistema ───────────────────────────────────────────── --}}
    <div class="flex items-center gap-md">
        <span class="font-headline-md text-headline-md font-bold text-on-surface">
            ConsumoInterno
        </span>
    </div>

    {{-- ── Acciones derechas ──────────────────────────────────────────── --}}
    <div class="flex items-center gap-lg">

        {{-- Buscador — visible en pantallas grandes --}}
        <div class="relative hidden lg:block">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline
                         material-symbols-outlined text-[18px]">search</span>
            <input type="text"
                   placeholder="Buscar..."
                   class="pl-9 pr-4 py-1.5
                          bg-surface-container rounded-full
                          border border-outline-variant
                          focus:ring-2 focus:ring-primary focus:border-transparent
                          w-56 font-body-md text-body-md text-on-surface
                          placeholder:text-secondary
                          outline-none transition-all">
        </div>

        <div class="flex items-center gap-md">

            {{-- Notificaciones --}}
            <button class="p-2 rounded-full text-secondary
                           hover:bg-surface-container
                           transition-colors active:opacity-80"
                    title="Notificaciones">
                <span class="material-symbols-outlined text-[20px]">notifications</span>
            </button>

            {{-- Ayuda --}}
            <button class="p-2 rounded-full text-secondary
                           hover:bg-surface-container
                           transition-colors active:opacity-80"
                    title="Ayuda">
                <span class="material-symbols-outlined text-[20px]">help</span>
            </button>

            {{-- Divisor --}}
            <div class="h-6 w-px bg-outline-variant"></div>

            {{-- Usuario autenticado + Logout ──────────────────────────── --}}
            <div class="flex items-center gap-sm">

                {{-- Avatar con iniciales --}}
                <div class="h-8 w-8 rounded-full
                            bg-secondary-container text-on-secondary-container
                            flex items-center justify-center
                            font-label-md text-label-md font-semibold
                            ring-2 ring-primary-container
                            select-none"
                     title="{{ auth()->user()->name }}">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(strstr(auth()->user()->name, ' '), 1, 1)) }}
                </div>

                {{-- Nombre --}}
                <span class="hidden md:block font-body-md text-body-md text-on-surface
                             max-w-[140px] truncate"
                      title="{{ auth()->user()->name }}">
                    {{ auth()->user()->name }}
                </span>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="font-body-md text-body-md text-secondary font-medium
                                   hover:text-on-surface transition-colors
                                   active:opacity-70 cursor-pointer">
                        Logout
                    </button>
                </form>

            </div>
        </div>
    </div>

</header>
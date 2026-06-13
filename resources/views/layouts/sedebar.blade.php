{{--
|─────────────────────────────────────────────────────────────────────────────
| sidebar.blade.php
|
| Sidebar fijo compartido por todos los roles.
| La navegación se genera dinámicamente según auth()->user()->role.
| Para agregar un nuevo rol: añade un @case dentro del @switch.
|─────────────────────────────────────────────────────────────────────────────
--}}

<aside class="fixed left-0 top-0 h-screen w-64 z-40
              flex flex-col
              bg-surface-container-low border-r border-outline-variant
              sidebar-transition">

    {{-- ── Logo y rol ─────────────────────────────────────────────────── --}}
    <div class="px-md pt-lg pb-xl">
        <h1 class="font-headline-sm text-headline-sm font-bold text-on-surface">
            Inventory Portal
        </h1>
        <p class="font-label-md text-label-md text-secondary mt-xs">
            @switch(auth()->user()->role)
                @case('admin')    Administrador @break
                @case('manager')  Manager       @break
                @case('employee') Employee Access @break
                @default          {{ ucfirst(auth()->user()->role) }}
            @endswitch
        </p>
    </div>

    {{-- ── Navegación por rol ──────────────────────────────────────────── --}}
    <nav class="flex-1 px-sm space-y-base overflow-y-auto">

        @switch(auth()->user()->role)

            {{-- ── Employee ─────────────────────────────────────────── --}}
            @case('employee')
                <a href="{{ route('employee.dashboard') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          font-label-md text-label-md
                          side-nav-transition active:scale-95
                          {{ request()->routeIs('employee.dashboard')
                             ? 'bg-secondary-container text-on-secondary-container font-semibold'
                             : 'text-secondary hover:bg-surface-container-highest hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('employee.requests.index') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          font-label-md text-label-md
                          side-nav-transition active:scale-95
                          {{ request()->routeIs('employee.requests.*')
                             ? 'bg-secondary-container text-on-secondary-container font-semibold'
                             : 'text-secondary hover:bg-surface-container-highest hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[20px]">assignment</span>
                    <span>Mis Solicitudes</span>
                </a>
            @break

            {{-- ── Manager ───────────────────────────────────────────── --}}
            @case('manager')
                <a href="{{ route('manager.requests.index') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          font-label-md text-label-md
                          side-nav-transition active:scale-95
                          {{ request()->routeIs('manager.requests.*')
                             ? 'bg-secondary-container text-on-secondary-container font-semibold'
                             : 'text-secondary hover:bg-surface-container-highest hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[20px]">pending_actions</span>
                    <span>Solicitudes</span>
                </a>
            @break

            {{-- ── Admin ─────────────────────────────────────────────── --}}
            @case('admin')
                <a href="{{ route('admin.departments.index') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          font-label-md text-label-md
                          side-nav-transition active:scale-95
                          {{ request()->routeIs('admin.departments.*')
                             ? 'bg-secondary-container text-on-secondary-container font-semibold'
                             : 'text-secondary hover:bg-surface-container-highest hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[20px]">corporate_fare</span>
                    <span>Departamentos</span>
                </a>

                <a href="{{ route('admin.items.index') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          font-label-md text-label-md
                          side-nav-transition active:scale-95
                          {{ request()->routeIs('admin.items.*')
                             ? 'bg-secondary-container text-on-secondary-container font-semibold'
                             : 'text-secondary hover:bg-surface-container-highest hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                    <span>Ítems</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          font-label-md text-label-md
                          side-nav-transition active:scale-95
                          {{ request()->routeIs('admin.users.*')
                             ? 'bg-secondary-container text-on-secondary-container font-semibold'
                             : 'text-secondary hover:bg-surface-container-highest hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                    <span>Usuarios</span>
                </a>
            @break

        @endswitch

    </nav>

    {{-- ── Footer del sidebar (espacio para expansión futura) ─────────── --}}
    {{-- Aquí se pueden agregar: perfil compacto, versión, etc. --}}

</aside>
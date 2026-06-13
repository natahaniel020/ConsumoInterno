@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    {{-- ── Encabezado ─────────────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-xl">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Admin Dashboard</h2>
            <p class="font-body-md text-body-md text-secondary">
                Resumen administrativo del sistema y monitoreo operacional.
            </p>
        </div>
    </div>

    {{-- ── KPI Cards ───────────────────────────────────────────────────────── --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter mb-xl">

        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Departamentos</span>
                <span class="p-2 bg-secondary-container text-on-secondary-container
                             rounded-lg material-symbols-outlined text-[20px]">business</span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">{{ $departmentsCount }}</span>
            <p class="text-[11px] text-secondary mt-xs">Departamentos registrados</p>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Ítems</span>
                <span class="p-2 bg-emerald-100 text-emerald-700
                             rounded-lg material-symbols-outlined text-[20px]">inventory_2</span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">{{ $itemsCount }}</span>
            <p class="text-[11px] text-secondary mt-xs">Ítems disponibles en catálogo</p>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Managers</span>
                <span class="p-2 bg-amber-100 text-amber-700
                             rounded-lg material-symbols-outlined text-[20px]">badge</span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">{{ $managersCount }}</span>
            <p class="text-[11px] text-secondary mt-xs">Managers de departamento</p>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Empleados</span>
                <span class="p-2 bg-blue-100 text-blue-700
                             rounded-lg material-symbols-outlined text-[20px]">groups</span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">{{ $employeesCount }}</span>
            <p class="text-[11px] text-secondary mt-xs">Empleados registrados</p>
        </div>

    </section>

    {{-- ── Tabla + Quick Actions ───────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">

        {{-- Tabla: Aprobadas esperando entrega --}}
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl shadow-sm
                    border border-outline-variant overflow-hidden">

            <div class="p-lg border-b border-outline-variant">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">
                    Aprobadas — En espera de entrega
                </h3>
                <p class="font-body-md text-body-md text-secondary mt-xs">
                    Solicitudes aprobadas pendientes de registro de entrega.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low">
                            <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Código</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Empleado</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Departamento</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Aprobado el</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">

                        @forelse ($approvedWaitingDelivery as $request)
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="px-lg py-md font-body-md text-body-md text-on-surface font-semibold">
                                    {{ $request->code ?? 'SOL-' . str_pad($request->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-lg py-md">
                                    <div class="flex items-center gap-sm">
                                        <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container
                                                    flex items-center justify-center font-label-md font-semibold select-none flex-shrink-0">
                                            {{ strtoupper(substr($request->employee->name, 0, 1)) }}{{ strtoupper(substr(strstr($request->employee->name, ' '), 1, 1)) }}
                                        </div>
                                        <span class="font-body-md text-body-md text-on-surface">
                                            {{ $request->employee->name ?? '—' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-lg py-md font-body-md text-body-md text-secondary">
                                    {{ $request->department->name ?? '—' }}
                                </td>
                                <td class="px-lg py-md font-body-md text-body-md text-secondary">
                                    {{ $request->approved_at ? \Carbon\Carbon::parse($request->approved_at)->format('d/m/Y') : '—' }}
                                </td>
                                <td class="px-lg py-md text-right">
                                    <a href="{{ route('admin.deliveries.show', $request) }}"
                                       class="inline-flex items-center gap-xs
                                              bg-primary text-on-primary
                                              px-md py-xs rounded-lg
                                              font-label-md text-label-md font-bold
                                              hover:opacity-90 active:scale-95 transition-all">
                                        <span class="material-symbols-outlined text-[16px]">local_shipping</span>
                                        Entregar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-lg py-xl text-center font-body-md text-body-md text-secondary">
                                    <span class="material-symbols-outlined text-[40px] block mb-sm text-outline">inventory</span>
                                    No hay entregas pendientes.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-surface-container-low p-xl rounded-xl border border-outline-variant flex flex-col gap-md">

            <h3 class="font-headline-sm text-headline-sm text-on-surface">Acciones rápidas</h3>
            <p class="font-body-md text-body-md text-secondary -mt-xs">Navega a los módulos de administración.</p>

            <div class="flex flex-col gap-sm mt-sm">

                <a href="{{ route('admin.departments.index') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          bg-surface-container-lowest border border-outline-variant
                          text-on-surface font-label-md text-label-md font-medium
                          hover:bg-surface-container hover:border-primary transition-all group">
                    <span class="material-symbols-outlined text-[18px] text-secondary group-hover:text-primary transition-colors">business</span>
                    Gestionar departamentos
                </a>

                <a href="{{ route('admin.items.index') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          bg-surface-container-lowest border border-outline-variant
                          text-on-surface font-label-md text-label-md font-medium
                          hover:bg-surface-container hover:border-primary transition-all group">
                    <span class="material-symbols-outlined text-[18px] text-secondary group-hover:text-primary transition-colors">inventory_2</span>
                    Gestionar ítems
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          bg-surface-container-lowest border border-outline-variant
                          text-on-surface font-label-md text-label-md font-medium
                          hover:bg-surface-container hover:border-primary transition-all group">
                    <span class="material-symbols-outlined text-[18px] text-secondary group-hover:text-primary transition-colors">groups</span>
                    Gestionar usuarios
                </a>

                <a href="{{ route('admin.reports.departments') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          bg-surface-container-lowest border border-outline-variant
                          text-on-surface font-label-md text-label-md font-medium
                          hover:bg-surface-container hover:border-primary transition-all group">
                    <span class="material-symbols-outlined text-[18px] text-secondary group-hover:text-primary transition-colors">analytics</span>
                    Reportes por departamento
                </a>

            </div>
        </div>

    </div>

@endsection
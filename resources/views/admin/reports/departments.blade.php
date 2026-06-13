@extends('layouts.app')

@section('title', 'Reporte por Departamento')

@section('content')

<div class="space-y-lg">

    {{-- ── Encabezado ─────────────────────────────────────────────────────── --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-md">
        <div class="space-y-xs">
            <h1 class="font-headline-lg text-headline-lg text-on-surface">Reporte por Departamento</h1>
            <p class="font-body-lg text-body-lg text-secondary max-w-2xl">
                Reportes operacionales y estadísticas de solicitudes de suministros por unidad organizacional.
            </p>
        </div>
        <a href="#"
           class="inline-flex items-center gap-sm
                  bg-primary text-on-primary
                  px-lg py-md rounded-lg
                  font-label-md text-label-md
                  hover:opacity-90 transition-opacity active:scale-95">
            <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
            Exportar PDF
        </a>
    </header>

    {{-- ── Grid: métricas + tabla detallada ───────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">

        {{-- ── Métricas resumen ────────────────────────────────────────────── --}}
        <section class="lg:col-span-4 h-full">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm
                        h-full flex flex-col">

                <div class="flex items-center gap-sm mb-md">
                    <span class="material-symbols-outlined text-primary">analytics</span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Métricas resumen</h2>
                </div>

                <div class="flex-grow overflow-hidden border border-outline-variant rounded-lg">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant">
                                <th class="px-md py-sm font-label-sm text-label-sm text-secondary uppercase tracking-wider">Métrica</th>
                                <th class="px-md py-sm font-label-sm text-label-sm text-secondary uppercase tracking-wider text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-md py-md font-body-md text-body-md">Total solicitudes</td>
                                <td class="px-md py-md font-headline-md text-headline-md text-right text-on-surface">
                                    {{ $totalRequests }}
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-md py-md font-body-md text-body-md">Aprobadas</td>
                                <td class="px-md py-md font-headline-md text-headline-md text-right text-on-surface">
                                    {{ $approvedCount }}
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-md py-md font-body-md text-body-md">Rechazadas</td>
                                <td class="px-md py-md font-headline-md text-headline-md text-right text-error">
                                    {{ $rejectedCount }}
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container transition-colors">
                                <td class="px-md py-md font-body-md text-body-md">Entregadas</td>
                                <td class="px-md py-md font-headline-md text-headline-md text-right text-on-surface">
                                    {{ $deliveredCount }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="mt-md font-label-sm text-label-sm text-outline flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[14px]">info</span>
                    Datos actualizados en tiempo real.
                </p>

            </div>
        </section>

        {{-- ── Tabla por departamento ───────────────────────────────────────── --}}
        <section class="lg:col-span-8 h-full">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm
                        h-full flex flex-col">

                <div class="flex items-center gap-sm mb-md">
                    <span class="material-symbols-outlined text-primary">domain</span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Solicitudes por departamento</h2>
                </div>

                <div class="flex-grow overflow-x-auto border border-outline-variant rounded-lg">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant">
                                <th class="px-md py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Departamento</th>
                                <th class="px-md py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-center">Total</th>
                                <th class="px-md py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-center">Aprobadas</th>
                                <th class="px-md py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-center">Rechazadas</th>
                                <th class="px-md py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-center">Entregadas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse ($departmentStats as $stat)
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="px-md py-md font-body-md font-semibold text-on-surface">
                                        {{ $stat->name }}
                                    </td>
                                    <td class="px-md py-md font-body-md text-body-md text-center">
                                        {{ $stat->supply_requests_count }}
                                    </td>
                                    <td class="px-md py-md text-center">
                                        <span class="inline-flex items-center justify-center px-sm py-xs
                                                     bg-secondary-container text-on-secondary-container
                                                     rounded font-label-md text-label-md">
                                            {{ $stat->approved_count }}
                                        </span>
                                    </td>
                                    <td class="px-md py-md text-center">
                                        <span class="inline-flex items-center justify-center px-sm py-xs
                                                     bg-error-container text-on-error-container
                                                     rounded font-label-md text-label-md">
                                            {{ $stat->rejected_count }}
                                        </span>
                                    </td>
                                    <td class="px-md py-md text-center">
                                        <span class="inline-flex items-center justify-center px-sm py-xs
                                                     bg-surface-container-high text-secondary
                                                     rounded font-label-md text-label-md">
                                            {{ $stat->delivered_count }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-md py-xl text-center text-secondary font-body-md">
                                        <span class="material-symbols-outlined text-[40px] block mb-sm text-outline">
                                            domain
                                        </span>
                                        No hay datos disponibles.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </section>

    </div>

</div>

@endsection
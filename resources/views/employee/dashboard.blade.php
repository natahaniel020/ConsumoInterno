{{--
|─────────────────────────────────────────────────────────────────────────────
| employee/dashboard.blade.php
|
| Dashboard del empleado.
| Controlador: Employee\SupplyRequestController@dashboard
| Variables: $stats (array), $recentRequests (Collection)
|─────────────────────────────────────────────────────────────────────────────
--}}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- ── Encabezado de página ───────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-xl">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">
                Dashboard Overview
            </h2>
            <p class="font-body-md text-body-md text-secondary">
                Track and manage your inventory requests in real-time.
            </p>
        </div>

        <a href="{{ route('employee.requests.create') }}"
           class="inline-flex items-center gap-sm
                  bg-primary text-on-primary
                  px-lg py-md rounded-xl
                  font-label-md text-label-md
                  shadow-md hover:shadow-lg
                  transition-all active:scale-95 self-start">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            New Request
        </a>
    </div>

    {{-- ── Grid de estadísticas ────────────────────────────────────────────── --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter mb-xl">

        {{-- Draft --}}
        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant
                    flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Draft Requests</span>
                <span class="p-2 bg-primary-container text-on-primary-container
                             rounded-lg material-symbols-outlined text-[20px]">
                    inventory_2
                </span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">
                {{ $stats['draft'] }}
            </span>
            <div class="flex items-center gap-xs text-secondary mt-xs">
                <span class="material-symbols-outlined text-[14px]">edit_note</span>
                <span class="text-[11px] font-medium">Awaiting completion</span>
            </div>
        </div>

        {{-- Rejected --}}
        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant
                    flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Rejected Requests</span>
                <span class="p-2 bg-error-container text-on-error-container
                             rounded-lg material-symbols-outlined text-[20px]">
                    cancel
                </span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">
                {{ $stats['rejected'] }}
            </span>
            <p class="text-[11px] text-secondary mt-xs">Requires attention</p>
        </div>

        {{-- Pending --}}
        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant
                    flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Pending Requests</span>
                <span class="p-2 bg-amber-100 text-amber-700
                             rounded-lg material-symbols-outlined text-[20px]">
                    pending_actions
                </span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">
                {{ $stats['pending'] }}
            </span>
            <p class="text-[11px] text-secondary mt-xs">Pending approval</p>
        </div>

        {{-- Approved --}}
        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant
                    flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Approved Requests</span>
                <span class="p-2 bg-emerald-100 text-emerald-700
                             rounded-lg material-symbols-outlined text-[20px]">
                    check_circle
                </span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">
                {{ $stats['approved'] }}
            </span>
            <p class="text-[11px] text-secondary mt-xs">Ready for collection</p>
        </div>

    </section>

    {{-- ── Tabla de solicitudes recientes ─────────────────────────────────── --}}
    <section class="bg-surface-container-lowest rounded-xl shadow-sm
                    border border-outline-variant overflow-hidden">

        {{-- Cabecera de la tabla --}}
        <div class="p-lg border-b border-outline-variant flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm text-on-surface">
                Recent Requests
            </h3>
            <a href="{{ route('employee.requests.index') }}"
               class="text-primary font-label-md text-label-md
                      inline-flex items-center gap-xs hover:underline">
                View all
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low">
                        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                            Request Code
                        </th>
                        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                            Items
                        </th>
                        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-right">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">

                    @forelse ($recentRequests as $request)
                        <tr class="hover:bg-surface-container-low transition-colors group js-hover-row">
                            {{-- Código --}}
                            <td class="px-lg py-md font-body-md text-body-md text-on-surface font-semibold">
                                SOL-{{ str_pad($request->id, 3, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- Fecha --}}
                            <td class="px-lg py-md font-body-md text-body-md text-secondary">
                                {{ $request->created_at->format('d/m/Y') }}
                            </td>

                            {{-- Badge de estado --}}
                            <td class="px-lg py-md">
                                @switch($request->status)
                                    @case('draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                                     text-xs font-medium
                                                     bg-surface-container-highest text-on-surface-variant
                                                     border border-outline-variant">
                                            Draft
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                                     text-xs font-medium
                                                     bg-amber-100 text-amber-800 border border-amber-200">
                                            Pending
                                        </span>
                                        @break
                                    @case('approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                                     text-xs font-medium
                                                     bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Approved
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                                     text-xs font-medium
                                                     bg-error-container text-on-error-container border border-red-200">
                                            Rejected
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                                     text-xs font-medium
                                                     bg-surface-container text-secondary border border-outline-variant">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                @endswitch
                            </td>

                            {{-- Cantidad de ítems --}}
                            <td class="px-lg py-md font-body-md text-body-md text-on-surface">
                                {{ $request->requestItems->count() }}
                            </td>

                            {{-- Acción --}}
                            <td class="px-lg py-md text-right">
                                <a href="{{ route('employee.requests.show', $request) }}"
                                   class="inline-flex items-center gap-xs
                                          text-primary font-label-md text-label-md
                                          hover:bg-surface-container p-2 rounded-lg
                                          transition-all">
                                    View
                                    <span class="material-symbols-outlined text-[14px]">visibility</span>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-lg py-xl text-center font-body-md text-body-md text-secondary">
                                No tienes solicitudes aún.
                                <a href="{{ route('employee.requests.create') }}"
                                   class="text-primary hover:underline ml-xs">
                                    Crear la primera
                                </a>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pie de la tabla --}}
        <div class="px-lg py-md bg-surface-container-low border-t border-outline-variant text-center">
            <p class="font-label-md text-label-md text-secondary">
                Showing {{ $recentRequests->count() }} of {{ $stats['draft'] + $stats['pending'] + $stats['approved'] + $stats['rejected'] }} requests
            </p>
        </div>

    </section>

    {{-- ── Quick Insights ──────────────────────────────────────────────────── --}}
    <section class="mt-xl grid grid-cols-1 lg:grid-cols-3 gap-gutter">

        {{-- Banner decorativo --}}
        <div class="lg:col-span-2
                    bg-gradient-to-br from-primary-container to-secondary-container
                    p-xl rounded-2xl relative overflow-hidden group">
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <h4 class="font-headline-md text-headline-md text-on-primary-container mb-xs">
                        Inventory Status Tip
                    </h4>
                    <p class="text-on-secondary-container max-w-md font-body-md text-body-md">
                        Did you know? Bulk requests are processed 20% faster when submitted
                        before 10:00 AM. Check the warehouse schedule for optimal timing.
                    </p>
                </div>
                <div class="mt-lg">
                    <button class="bg-surface-container-lowest text-primary
                                   px-lg py-sm rounded-lg
                                   font-label-md text-label-md
                                   shadow-sm hover:shadow-md transition-shadow">
                        View Logistics Schedule
                    </button>
                </div>
            </div>
            {{-- Ícono decorativo de fondo --}}
            <div class="absolute -right-10 -bottom-10 opacity-10
                        group-hover:scale-110 transition-transform duration-700
                        pointer-events-none">
                <span class="material-symbols-outlined text-[240px]">warehouse</span>
            </div>
        </div>

        {{-- Card de soporte --}}
        <div class="bg-surface-container-low p-xl rounded-2xl
                    border border-outline-variant
                    flex flex-col items-center justify-center text-center gap-md">
            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-inner">
                <span class="material-symbols-outlined text-primary text-[32px]">support_agent</span>
            </div>
            <div>
                <h4 class="font-headline-sm text-headline-sm text-on-surface">Need Help?</h4>
                <p class="font-body-md text-body-md text-secondary mt-xs">
                    Our inventory specialist team is online from 8am to 6pm.
                </p>
            </div>
            <button class="w-full py-sm rounded-lg
                           border-2 border-primary text-primary
                           font-label-md text-label-md
                           hover:bg-primary hover:text-on-primary
                           transition-all">
                Chat with Support
            </button>
        </div>

    </section>

@endsection

{{-- ── Micro-interacciones ────────────────────────────────────────────────── --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.js-hover-row').forEach(row => {
            row.style.transition = 'transform 0.2s ease-out';
            row.addEventListener('mouseenter', () => row.style.transform = 'translateX(4px)');
            row.addEventListener('mouseleave', () => row.style.transform = 'translateX(0)');
        });
    });
</script>
@endpush
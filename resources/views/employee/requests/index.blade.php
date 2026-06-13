{{--
|─────────────────────────────────────────────────────────────────────────────
| employee/requests/index.blade.php
|
| Listado paginado de solicitudes del empleado autenticado.
| Controlador: Employee\SupplyRequestController@index
| Variables: $requests (LengthAwarePaginator)
|─────────────────────────────────────────────────────────────────────────────
--}}

@extends('layouts.app')

@section('title', 'Mis Solicitudes')

@section('content')

    {{-- ── Encabezado de página ───────────────────────────────────────────── --}}
    <div class="flex justify-between items-end mb-lg">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-background">
                My Requests
            </h1>
            <p class="font-body-lg text-body-lg text-secondary">
                Manage and track your inventory requisitions
            </p>
        </div>
    </div>

    {{-- ── Tabla de solicitudes ────────────────────────────────────────────── --}}
    <div class="bg-surface-container-lowest border border-outline-variant
                rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">

                <thead class="bg-surface-container-low border-b border-outline-variant">
                    <tr>
                        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                            Request Code
                        </th>
                        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-right">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-outline-variant">

                    @forelse ($requests as $request)
                        <tr class="hover:bg-surface-container transition-colors duration-150">

                            {{-- Código --}}
                            <td class="px-lg py-md font-medium text-primary">
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
                                        <span class="inline-flex items-center gap-xs px-sm py-1 rounded-full
                                                     font-label-md font-semibold
                                                     bg-surface-container-high text-secondary
                                                     border border-outline-variant">
                                            <span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                                            Draft
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="inline-flex items-center gap-xs px-sm py-1 rounded-full
                                                     font-label-md font-semibold
                                                     bg-amber-100 text-amber-700
                                                     border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                        @break
                                    @case('approved')
                                        <span class="inline-flex items-center gap-xs px-sm py-1 rounded-full
                                                     font-label-md font-semibold
                                                     bg-green-100 text-green-700
                                                     border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            Approved
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="inline-flex items-center gap-xs px-sm py-1 rounded-full
                                                     font-label-md font-semibold
                                                     bg-error-container text-on-error-container
                                                     border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                                            Rejected
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center gap-xs px-sm py-1 rounded-full
                                                     font-label-md font-semibold
                                                     bg-surface-container text-secondary
                                                     border border-outline-variant">
                                            <span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                                            {{ ucfirst($request->status) }}
                                        </span>
                                @endswitch
                            </td>

                            {{-- Acción: Edit si draft, View si cualquier otro estado --}}
                            <td class="px-lg py-md text-right">
                                @if ($request->status === 'draft')
                                    <a href="{{ route('employee.requests.show', $request) }}"
                                       class="text-primary font-semibold font-body-md
                                              hover:underline active:opacity-70 transition-opacity">
                                        Edit
                                    </a>
                                @else
                                    <a href="{{ route('employee.requests.show', $request) }}"
                                       class="text-primary font-semibold font-body-md
                                              hover:underline active:opacity-70 transition-opacity">
                                        View
                                    </a>
                                @endif
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="4"
                                class="px-lg py-xl text-center font-body-md text-body-md text-secondary">
                                No tienes solicitudes aún.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- ── Paginación ──────────────────────────────────────────────────── --}}
        @if ($requests->hasPages())
            <div class="px-lg py-md flex items-center justify-between
                        bg-surface-container-lowest border-t border-outline-variant">

                {{-- Texto informativo --}}
                <span class="font-body-md text-body-md text-secondary">
                    Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }}
                    of {{ $requests->total() }} entries
                </span>

                {{-- Links de Laravel con estilos de Stitch --}}
                <div class="flex items-center gap-xs">
                    {{-- Anterior --}}
                    @if ($requests->onFirstPage())
                        <span class="p-sm rounded-lg text-secondary opacity-30 cursor-not-allowed">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </span>
                    @else
                        <a href="{{ $requests->previousPageUrl() }}"
                           class="p-sm rounded-lg text-secondary
                                  hover:bg-surface-container transition-colors active:scale-95">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    @endif

                    {{-- Números de página --}}
                    @foreach ($requests->getUrlRange(1, $requests->lastPage()) as $page => $url)
                        @if ($page == $requests->currentPage())
                            <span class="w-10 h-10 flex items-center justify-center rounded-lg
                                         bg-primary text-on-primary font-semibold
                                         font-body-md text-body-md">
                                {{ $page }}
                            </span>
                        @elseif (
                            $page == 1 ||
                            $page == $requests->lastPage() ||
                            abs($page - $requests->currentPage()) <= 1
                        )
                            <a href="{{ $url }}"
                               class="w-10 h-10 flex items-center justify-center rounded-lg
                                      text-secondary font-medium
                                      hover:bg-surface-container transition-colors active:scale-95
                                      font-body-md text-body-md">
                                {{ $page }}
                            </a>
                        @elseif (
                            $page == $requests->currentPage() - 2 ||
                            $page == $requests->currentPage() + 2
                        )
                            <span class="px-xs text-secondary">...</span>
                        @endif
                    @endforeach

                    {{-- Siguiente --}}
                    @if ($requests->hasMorePages())
                        <a href="{{ $requests->nextPageUrl() }}"
                           class="p-sm rounded-lg text-secondary
                                  hover:bg-surface-container transition-colors active:scale-95">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                    @else
                        <span class="p-sm rounded-lg text-secondary opacity-30 cursor-not-allowed">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </span>
                    @endif
                </div>

            </div>
        @else
            {{-- Sin paginación: solo muestra el total --}}
            <div class="px-lg py-md bg-surface-container-lowest border-t border-outline-variant">
                <span class="font-body-md text-body-md text-secondary">
                    {{ $requests->total() }} {{ $requests->total() === 1 ? 'solicitud' : 'solicitudes' }}
                </span>
            </div>
        @endif

    </div>

    {{-- ── Cards de contexto (Bento) ──────────────────────────────────────── --}}
    <div class="mt-xl grid grid-cols-1 md:grid-cols-3 gap-lg">

        <div class="p-md bg-white border border-outline-variant rounded-xl shadow-sm
                    flex flex-col gap-sm">
            <div class="w-10 h-10 rounded-full bg-secondary-container
                        flex items-center justify-center text-primary">
                <span class="material-symbols-outlined">info</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary">Need help?</h3>
            <p class="font-body-md text-body-md text-secondary">
                Check our documentation for requisition guidelines and approval timelines.
            </p>
        </div>

        <div class="p-md bg-white border border-outline-variant rounded-xl shadow-sm
                    flex flex-col gap-sm">
            <div class="w-10 h-10 rounded-full bg-secondary-container
                        flex items-center justify-center text-primary">
                <span class="material-symbols-outlined">history</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary">History</h3>
            <p class="font-body-md text-body-md text-secondary">
                View past requests from previous fiscal years in the archived section.
            </p>
        </div>

        <div class="relative overflow-hidden p-md bg-primary-container
                    text-on-primary-container rounded-xl shadow-sm flex flex-col gap-sm">
            <div class="z-10">
                <h3 class="font-headline-sm text-headline-sm text-on-primary">
                    Inventory Policy
                </h3>
                <p class="font-body-md text-body-md opacity-80">
                    New guidelines for hardware requests are now in effect.
                </p>
            </div>
            <div class="absolute -right-8 -bottom-8 w-32 h-32
                        bg-white/10 rounded-full blur-2xl pointer-events-none">
            </div>
        </div>

    </div>

@endsection
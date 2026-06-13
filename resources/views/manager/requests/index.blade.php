@extends('layouts.app')

@section('title', 'Solicitudes')

@section('content')

    {{-- ── Cabecera de página ──────────────────────────────────────────────── --}}
    <div class="flex justify-between items-end mb-lg">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-background">Solicitudes</h1>
            <p class="text-body-lg text-secondary">Revisa y gestiona las requisiciones del equipo</p>
        </div>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('manager.requests.index') }}" class="flex gap-sm">
            <select name="status"
                    onchange="this.form.submit()"
                    class="px-md py-sm border border-outline-variant rounded-lg
                           bg-surface-container-lowest text-secondary
                           font-body-md text-body-md
                           hover:bg-surface-container transition-colors
                           focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                <option value="">Todos los estados</option>
                @foreach(['pending' => 'Pendiente', 'approved' => 'Aprobado', 'rejected' => 'Rechazado', 'delivered' => 'Entregado'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <select name="priority"
                    onchange="this.form.submit()"
                    class="px-md py-sm border border-outline-variant rounded-lg
                           bg-surface-container-lowest text-secondary
                           font-body-md text-body-md
                           hover:bg-surface-container transition-colors
                           focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                <option value="">Todas las prioridades</option>
                @foreach(['low' => 'Baja', 'medium' => 'Media', 'high' => 'Alta'] as $val => $label)
                    <option value="{{ $val }}" {{ request('priority') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- ── Tabla ───────────────────────────────────────────────────────────── --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-low border-b border-outline-variant">
                    <tr>
                        <th class="px-lg py-md text-label-sm font-label-sm text-secondary uppercase tracking-wider">Código</th>
                        <th class="px-lg py-md text-label-sm font-label-sm text-secondary uppercase tracking-wider">Empleado</th>
                        <th class="px-lg py-md text-label-sm font-label-sm text-secondary uppercase tracking-wider">Departamento</th>
                        <th class="px-lg py-md text-label-sm font-label-sm text-secondary uppercase tracking-wider">Fecha</th>
                        <th class="px-lg py-md text-label-sm font-label-sm text-secondary uppercase tracking-wider">Prioridad</th>
                        <th class="px-lg py-md text-label-sm font-label-sm text-secondary uppercase tracking-wider">Estado</th>
                        <th class="px-lg py-md text-label-sm font-label-sm text-secondary uppercase tracking-wider text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">

                    @forelse ($requests as $req)
                        @php
                            $priorityMap = [
                                'low'    => ['bg-surface-container-high text-secondary border-outline-variant', 'bg-outline',   'Baja'],
                                'medium' => ['bg-amber-100 text-amber-700 border-amber-200',                    'bg-amber-500', 'Media'],
                                'high'   => ['bg-red-100 text-red-700 border-red-200',                         'bg-red-500',   'Alta'],
                            ];
                            $statusMap = [
                                'pending'   => ['bg-amber-100 text-amber-700 border-amber-200',                      'bg-amber-500',  'Pendiente'],
                                'approved'  => ['bg-green-100 text-green-700 border-green-200',                      'bg-green-500',  'Aprobado'],
                                'rejected'  => ['bg-error-container text-on-error-container border-red-200',         'bg-error',      'Rechazado'],
                                'delivered' => ['bg-blue-100 text-blue-700 border-blue-200',                         'bg-blue-500',   'Entregado'],
                            ];
                            [$pClasses, $pDot, $pLabel] = $priorityMap[$req->priority] ?? $priorityMap['low'];
                            [$sClasses, $sDot, $sLabel] = $statusMap[$req->status]     ?? ['bg-surface-container-high text-secondary border-outline-variant', 'bg-outline', ucfirst($req->status)];
                        @endphp

                        <tr class="hover:bg-surface-container transition-colors duration-150">

                            <td class="px-lg py-md font-medium text-primary">
                                {{ $req->code ?? 'SOL-' . str_pad($req->id, 3, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="px-lg py-md text-body-md text-on-surface">
                                {{ $req->employee->name ?? '—' }}
                            </td>

                            <td class="px-lg py-md text-body-md text-secondary">
                                {{ $req->department->name ?? '—' }}
                            </td>

                            <td class="px-lg py-md text-body-md text-secondary">
                                {{ $req->created_at->format('d/m/Y') }}
                            </td>

                            <td class="px-lg py-md">
                                <span class="inline-flex items-center gap-xs px-sm py-1 rounded-full text-label-md font-semibold border {{ $pClasses }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $pDot }}"></span>
                                    {{ $pLabel }}
                                </span>
                            </td>

                            <td class="px-lg py-md">
                                <span class="inline-flex items-center gap-xs px-sm py-1 rounded-full text-label-md font-semibold border {{ $sClasses }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sDot }}"></span>
                                    {{ $sLabel }}
                                </span>
                            </td>

                            <td class="px-lg py-md text-right">
                                <a href="{{ route('manager.requests.show', $req) }}"
                                   class="text-primary font-semibold text-body-md hover:underline active:opacity-70 transition-opacity">
                                    Ver
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-lg py-xl text-center text-secondary text-body-md">
                                <span class="material-symbols-outlined text-[40px] block mb-sm text-outline">inbox</span>
                                No hay solicitudes que coincidan con los filtros seleccionados.
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

                <span class="text-body-md text-secondary">
                    Mostrando {{ $requests->firstItem() }}–{{ $requests->lastItem() }} de {{ $requests->total() }} solicitudes
                </span>

                <div class="flex items-center gap-xs">

                    @if ($requests->onFirstPage())
                        <button disabled class="p-sm rounded-lg text-secondary opacity-30 cursor-not-allowed">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                    @else
                        <a href="{{ $requests->previousPageUrl() }}"
                           class="p-sm rounded-lg hover:bg-surface-container text-secondary transition-colors active:scale-95">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    @endif

                    @foreach ($requests->getUrlRange(1, $requests->lastPage()) as $page => $url)
                        @if ($page === $requests->currentPage())
                            <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-on-primary font-semibold text-body-md">
                                {{ $page }}
                            </span>
                        @elseif (abs($page - $requests->currentPage()) <= 2 || $page === 1 || $page === $requests->lastPage())
                            <a href="{{ $url }}"
                               class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-surface-container text-secondary font-medium transition-colors active:scale-95 text-body-md">
                                {{ $page }}
                            </a>
                        @elseif (abs($page - $requests->currentPage()) === 3)
                            <span class="px-xs text-secondary">…</span>
                        @endif
                    @endforeach

                    @if ($requests->hasMorePages())
                        <a href="{{ $requests->nextPageUrl() }}"
                           class="p-sm rounded-lg hover:bg-surface-container text-secondary transition-colors active:scale-95">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                    @else
                        <button disabled class="p-sm rounded-lg text-secondary opacity-30 cursor-not-allowed">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    @endif

                </div>
            </div>
        @endif
    </div>

    {{-- ── Tarjetas de contexto ────────────────────────────────────────────── --}}
    <div class="mt-xl grid grid-cols-1 md:grid-cols-3 gap-lg">

        <div class="p-md bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm flex flex-col gap-sm">
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600">pending_actions</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Pendientes de revisión</h3>
            <p class="text-body-md text-secondary">Filtra por estado "Pendiente" para ver las solicitudes que requieren tu atención.</p>
        </div>

        <div class="p-md bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm flex flex-col gap-sm">
            <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">local_shipping</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Por entregar</h3>
            <p class="text-body-md text-secondary">Las solicitudes aprobadas quedan en espera de entrega. Filtra por "Aprobado" para rastrearlas.</p>
        </div>

        <div class="relative overflow-hidden p-md bg-primary-container rounded-xl shadow-sm flex flex-col gap-sm">
            <div class="z-10 relative">
                <h3 class="font-headline-sm text-headline-sm text-on-primary-container">Política de inventario</h3>
                <p class="text-body-md text-on-primary-container opacity-80 mt-xs">
                    Revisa los lineamientos vigentes antes de aprobar solicitudes de alto valor.
                </p>
            </div>
            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        </div>

    </div>

@endsection
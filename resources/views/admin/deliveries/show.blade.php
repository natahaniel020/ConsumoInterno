@extends('layouts.app')

@section('title', 'Entrega — ' . ($supplyRequest->code ?? 'SOL-' . str_pad($supplyRequest->id, 3, '0', STR_PAD_LEFT)))

@section('content')

    @php
        $code = $supplyRequest->code ?? 'SOL-' . str_pad($supplyRequest->id, 3, '0', STR_PAD_LEFT);
        $priorityMap = [
            'low'    => ['bg-surface-container-high text-secondary',          'low_priority',  'Baja'],
            'medium' => ['bg-amber-100 text-amber-700',                       'priority_high', 'Media'],
            'high'   => ['bg-error-container text-on-error-container',        'priority_high', 'Alta'],
        ];
        [$pClasses, $pIcon, $pLabel] = $priorityMap[$supplyRequest->priority] ?? $priorityMap['low'];
    @endphp

    {{-- ── Navegación ─────────────────────────────────────────────────────── --}}
    <div class="mb-md">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-xs text-secondary hover:text-on-surface transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span class="font-label-md text-label-md">Volver al Dashboard</span>
        </a>
    </div>

    {{-- ── Encabezado ──────────────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-lg">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Solicitud de Entrega</h1>
            <p class="font-body-md text-body-md text-secondary">Gestiona los detalles de entrega para procesamiento logístico.</p>
        </div>
        <div class="inline-flex items-center gap-sm bg-surface-container px-md py-sm rounded-lg border border-outline-variant">
            <span class="material-symbols-outlined text-secondary">schedule</span>
            <span class="font-label-md text-label-md text-on-surface-variant">
                Aprobado el {{ $supplyRequest->approved_at?->format('d/m/Y') ?? '—' }}
            </span>
        </div>
    </div>

    {{-- ── Grid principal ───────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">

        {{-- ── Columna izquierda ───────────────────────────────────────────── --}}
        <div class="lg:col-span-2 flex flex-col gap-lg">

            {{-- Detalles de la solicitud --}}
            <section class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm">
                <div class="flex items-center justify-between mb-lg">
                    <div class="flex items-center gap-sm">
                        <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-primary-fixed">assignment_turned_in</span>
                        </div>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">Detalles de la solicitud</h2>
                    </div>
                    <span class="inline-flex items-center gap-xs px-md py-xs rounded-full font-label-md text-label-md {{ $pClasses }}">
                        <span class="material-symbols-outlined text-[16px]">{{ $pIcon }}</span>
                        Prioridad {{ $pLabel }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-xl">
                    <div class="flex flex-col gap-xs">
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Código</span>
                        <span class="font-headline-sm text-headline-sm text-on-surface">{{ $code }}</span>
                    </div>
                    <div class="flex flex-col gap-xs">
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Empleado</span>
                        <span class="font-body-lg text-body-lg text-on-surface font-semibold">
                            {{ $supplyRequest->employee->name }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-xs">
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Departamento</span>
                        <span class="font-body-lg text-body-lg text-secondary">
                            {{ $supplyRequest->department->name ?? '—' }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-xs">
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aprobado el</span>
                        <span class="font-body-lg text-body-lg text-secondary">
                            {{ $supplyRequest->approved_at?->format('d/m/Y') ?? '—' }}
                        </span>
                    </div>
                </div>
            </section>

            {{-- Ítems solicitados --}}
            <section class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="p-lg border-b border-outline-variant">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Ítems solicitados</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container">
                                <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Ítem</th>
                                <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Unidad</th>
                                <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-right">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse ($supplyRequest->requestItems as $requestItem)
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="px-lg py-md font-body-md text-body-md text-on-surface font-medium">
                                        {{ $requestItem->item->name }}
                                    </td>
                                    <td class="px-lg py-md font-body-md text-body-md text-secondary capitalize">
                                        {{ $requestItem->item->unit }}
                                    </td>
                                    <td class="px-lg py-md font-headline-sm text-headline-sm text-on-surface text-right">
                                        x{{ $requestItem->quantity }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-lg py-md text-center text-secondary font-body-md">
                                        Sin ítems registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

        {{-- ── Columna derecha ─────────────────────────────────────────────── --}}
        <div class="flex flex-col gap-lg">

            {{-- Info de aprobación --}}
            <section class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm flex flex-col gap-md">
                <div class="flex items-center gap-sm mb-xs">
                    <span class="material-symbols-outlined text-secondary">verified_user</span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Info de aprobación</h2>
                </div>

                <div class="flex flex-col gap-xs p-md bg-surface-container-low rounded-lg border border-outline-variant">
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aprobado por</span>
                    <div class="flex items-center gap-sm mt-xs">
                        <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container
                                    flex items-center justify-center font-label-md font-semibold select-none flex-shrink-0">
                            {{ strtoupper(substr($supplyRequest->approver->name, 0, 1)) }}{{ strtoupper(substr(strstr($supplyRequest->approver->name, ' '), 1, 1)) }}
                        </div>
                        <span class="font-body-md text-body-md text-on-surface font-semibold">
                            {{ $supplyRequest->approver->name }}
                        </span>
                    </div>
                </div>

                @if ($supplyRequest->notes)
                    <div class="flex flex-col gap-xs">
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Notas</span>
                        <div class="p-md bg-primary-fixed border-l-4 border-primary-fixed-dim rounded-r-lg
                                    italic text-secondary font-body-md text-body-md">
                            "{{ $supplyRequest->notes }}"
                        </div>
                    </div>
                @endif
            </section>

            {{-- Acciones --}}
            <section class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm flex flex-col gap-sm">

                @if ($supplyRequest->status === 'approved')
                    <form method="POST"
                          action="{{ route('admin.deliveries.deliver', $supplyRequest) }}"
                          onsubmit="return confirm('¿Confirmar entrega de {{ addslashes($code) }}?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full bg-primary text-on-primary py-md px-lg rounded-lg
                                       font-body-md text-body-md font-semibold
                                       hover:opacity-90 transition-opacity active:scale-95
                                       flex items-center justify-center gap-sm">
                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                            Marcar como entregado
                        </button>
                    </form>
                @else
                    <div class="w-full py-md px-lg rounded-lg
                                bg-emerald-100 border border-emerald-200
                                flex items-center justify-center gap-sm">
                        <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                        <span class="font-body-md text-body-md text-emerald-700 font-semibold">
                            Entregado el {{ $supplyRequest->delivered_at?->format('d/m/Y') }}
                        </span>
                    </div>
                @endif

                <a href="{{ route('admin.dashboard') }}"
                   class="w-full border border-outline-variant text-secondary py-md px-lg rounded-lg
                          font-body-md text-body-md
                          hover:bg-surface-container transition-colors
                          flex items-center justify-center gap-sm">
                    <span class="material-symbols-outlined text-[18px]">keyboard_backspace</span>
                    Volver al dashboard
                </a>

                <p class="text-center font-label-sm text-label-sm text-on-surface-variant mt-sm">
                    La acción quedará registrada para auditoría.
                </p>

            </section>

        </div>

    </div>

@endsection
@extends('layouts.app')

@section('title', 'Revisar Solicitud')

@section('content')

    @php
        $statusMap = [
            'pending'   => ['bg-amber-100 text-amber-700',                      'pending_actions', 'Pendiente'],
            'approved'  => ['bg-green-100 text-green-700',                       'check_circle',   'Aprobado'],
            'rejected'  => ['bg-error-container text-on-error-container',        'cancel',         'Rechazado'],
            'delivered' => ['bg-blue-100 text-blue-700',                         'local_shipping', 'Entregado'],
        ];
        $priorityMap = [
            'low'    => ['bg-surface-container-high text-secondary',             'low_priority',   'Baja'],
            'medium' => ['bg-amber-100 text-amber-700',                          'priority_high',  'Media'],
            'high'   => ['bg-error-container text-on-error-container',           'priority_high',  'Alta'],
        ];
        [$sClasses, $sIcon, $sLabel] = $statusMap[$supplyRequest->status]     ?? $statusMap['pending'];
        [$pClasses, $pIcon, $pLabel] = $priorityMap[$supplyRequest->priority] ?? $priorityMap['low'];

        $code = $supplyRequest->code ?? 'SOL-' . str_pad($supplyRequest->id, 3, '0', STR_PAD_LEFT);
        $isPending = $supplyRequest->status === 'pending';
    @endphp

    {{-- ── Barra superior de navegación ──────────────────────────────────── --}}
    <div class="mb-lg flex items-center justify-between">
        <a href="{{ route('manager.requests.index') }}"
           class="group flex items-center gap-xs text-secondary hover:text-on-surface transition-colors py-sm">
            <span class="material-symbols-outlined text-body-lg">arrow_back</span>
            <span class="font-label-md text-label-md">Volver a Solicitudes</span>
        </a>
    </div>

    <div class="max-w-[800px] mx-auto flex flex-col gap-lg">

        {{-- ── Encabezado ─────────────────────────────────────────────────── --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-md
                    border-b border-outline-variant pb-lg">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-background tracking-tight">
                    Revisar Solicitud
                </h1>
                <p class="font-headline-sm text-headline-sm text-secondary">{{ $code }}</p>
            </div>
            <div class="flex items-center gap-sm flex-wrap">
                <span class="inline-flex items-center gap-xs px-md py-xs rounded-full font-label-md text-label-md {{ $sClasses }}">
                    <span class="material-symbols-outlined text-[16px]">{{ $sIcon }}</span>
                    {{ $sLabel }}
                </span>
                <span class="inline-flex items-center gap-xs px-md py-xs rounded-full font-label-md text-label-md {{ $pClasses }}">
                    <span class="material-symbols-outlined text-[16px]">{{ $pIcon }}</span>
                    Prioridad {{ $pLabel }}
                </span>
            </div>
        </div>

        {{-- ── Bento: Empleado / Departamento / Fecha ──────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-md">

            {{-- Empleado --}}
            <div class="bg-surface-container-lowest border border-outline-variant shadow-sm p-md rounded-xl">
                <p class="font-label-sm text-label-sm text-on-surface-variant mb-xs tracking-wider">EMPLEADO</p>
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container
                                flex items-center justify-center font-label-md font-semibold select-none flex-shrink-0">
                        {{ strtoupper(substr($supplyRequest->employee->name, 0, 1)) }}{{ strtoupper(substr(strstr($supplyRequest->employee->name, ' '), 1, 1)) }}
                    </div>
                    <div>
                        <p class="font-label-md text-label-md font-bold text-on-surface">
                            {{ $supplyRequest->employee->name }}
                        </p>
                        <p class="font-label-sm text-label-sm text-secondary">
                            {{ $supplyRequest->employee->email }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Departamento --}}
            <div class="bg-surface-container-lowest border border-outline-variant shadow-sm p-md rounded-xl">
                <p class="font-label-sm text-label-sm text-on-surface-variant mb-xs tracking-wider">DEPARTAMENTO</p>
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-primary flex-shrink-0">
                        <span class="material-symbols-outlined">corporate_fare</span>
                    </div>
                    <p class="font-label-md text-label-md font-bold text-on-surface">
                        {{ $supplyRequest->department->name ?? '—' }}
                    </p>
                </div>
            </div>

            {{-- Fecha --}}
            <div class="bg-surface-container-lowest border border-outline-variant shadow-sm p-md rounded-xl">
                <p class="font-label-sm text-label-sm text-on-surface-variant mb-xs tracking-wider">FECHA DE SOLICITUD</p>
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-primary flex-shrink-0">
                        <span class="material-symbols-outlined">calendar_today</span>
                    </div>
                    <p class="font-label-md text-label-md font-bold text-on-surface">
                        {{ $supplyRequest->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>

        </div>

        {{-- ── Justificación ───────────────────────────────────────────────── --}}
        @if ($supplyRequest->justification)
            <div class="bg-surface-container-lowest border border-outline-variant shadow-sm p-lg rounded-xl">
                <h3 class="font-label-sm text-label-sm text-on-surface-variant mb-md tracking-wider">JUSTIFICACIÓN</h3>
                <div class="relative pl-lg border-l-4 border-primary-fixed-dim">
                    <p class="font-body-lg text-body-lg text-on-surface italic leading-relaxed">
                        "{{ $supplyRequest->justification }}"
                    </p>
                </div>
            </div>
        @endif

        {{-- ── Ítems solicitados ───────────────────────────────────────────── --}}
        <div class="bg-surface-container-lowest border border-outline-variant shadow-sm rounded-xl overflow-hidden">
            <div class="bg-surface-container-low px-lg py-sm border-b border-outline-variant">
                <h3 class="font-label-sm text-label-sm text-on-surface-variant tracking-wider">ÍTEMS SOLICITADOS</h3>
            </div>
            <table class="w-full text-left">
                <thead class="bg-surface-container-lowest">
                    <tr>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant">Ítem</th>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant text-right">Cantidad</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($supplyRequest->requestItems as $requestItem)
                        <tr class="hover:bg-surface-container transition-colors">
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container border border-outline-variant
                                                flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-secondary">inventory_2</span>
                                    </div>
                                    <p class="font-body-md text-body-md font-semibold text-on-surface">
                                        {{ $requestItem->item->name }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-lg py-md text-right font-headline-sm text-headline-sm text-on-surface">
                                {{ $requestItem->quantity }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-lg py-md text-center text-secondary text-body-md">
                                Sin ítems registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Área de decisión (solo si está pendiente) ──────────────────── --}}
        @if ($isPending)
            <div class="bg-surface-container-lowest border-2 border-primary-fixed shadow-sm p-lg rounded-xl">
                <p class="font-label-sm text-label-sm text-on-surface-variant mb-md tracking-wider">DECISIÓN</p>

                <div class="flex flex-col md:flex-row items-center justify-between gap-md">
                    <div class="flex flex-col md:flex-row gap-sm w-full md:w-auto">

                        {{-- Aprobar --}}
                        <form method="POST"
                              action="{{ route('manager.requests.approve', $supplyRequest) }}"
                              class="w-full md:w-auto">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full bg-primary text-on-primary font-label-md text-label-md
                                           px-xl py-md rounded-lg flex items-center justify-center gap-sm
                                           hover:opacity-90 active:scale-95 transition-all shadow-md">
                                <span class="material-symbols-outlined text-[20px]"
                                      style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                Aprobar solicitud
                            </button>
                        </form>

                        {{-- Rechazar --}}
                        <form method="POST"
                              action="{{ route('manager.requests.reject', $supplyRequest) }}"
                              class="w-full md:w-auto">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full border border-error text-error font-label-md text-label-md
                                           px-xl py-md rounded-lg flex items-center justify-center gap-sm
                                           hover:bg-error-container transition-all active:scale-95">
                                <span class="material-symbols-outlined text-[20px]">cancel</span>
                                Rechazar solicitud
                            </button>
                        </form>

                    </div>

                    <a href="{{ route('manager.requests.index') }}"
                       class="text-secondary hover:text-on-surface font-label-md text-label-md
                              underline underline-offset-4 decoration-outline-variant
                              hover:decoration-on-surface transition-all">
                        Cancelar y volver
                    </a>
                </div>
            </div>
        @endif

        {{-- ── Info de auditoría ───────────────────────────────────────────── --}}
        <div class="flex items-center gap-sm text-on-surface-variant px-xs">
            <span class="material-symbols-outlined text-[14px]">info</span>
            <p class="font-label-sm text-label-sm">
                Solicitud creada el {{ $supplyRequest->created_at->format('d/m/Y \a \l\a\s H:i') }}
                por {{ $supplyRequest->employee->name }}.
                @if ($supplyRequest->approver)
                    {{ ucfirst($supplyRequest->status) }} el {{ $supplyRequest->approved_at?->format('d/m/Y') }}
                    por {{ $supplyRequest->approver->name }}.
                @endif
            </p>
        </div>

    </div>

@endsection
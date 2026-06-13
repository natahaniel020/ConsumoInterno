@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')

    {{-- ── Encabezado ─────────────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-xl">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">
                Manager Dashboard
            </h2>
            <p class="font-body-md text-body-md text-secondary">
                Resumen del departamento — estado de solicitudes de suministros.
            </p>
        </div>
    </div>

    {{-- ── KPI Cards ───────────────────────────────────────────────────────── --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter mb-xl">

        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Pendientes</span>
                <span class="p-2 bg-amber-100 text-amber-700 rounded-lg material-symbols-outlined text-[20px]">pending_actions</span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">{{ $pendingCount }}</span>
            <p class="text-[11px] text-secondary mt-xs">Esperando tu aprobación</p>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Aprobadas</span>
                <span class="p-2 bg-emerald-100 text-emerald-700 rounded-lg material-symbols-outlined text-[20px]">check_circle</span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">{{ $approvedCount }}</span>
            <p class="text-[11px] text-secondary mt-xs">Listas para entrega</p>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Rechazadas</span>
                <span class="p-2 bg-error-container text-on-error-container rounded-lg material-symbols-outlined text-[20px]">cancel</span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">{{ $rejectedCount }}</span>
            <p class="text-[11px] text-secondary mt-xs">Requieren atención</p>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-xl shadow-sm
                    border border-outline-variant flex flex-col gap-xs
                    hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-xs">
                <span class="text-secondary font-label-md text-label-md">Entregadas</span>
                <span class="p-2 bg-secondary-container text-on-secondary-container rounded-lg material-symbols-outlined text-[20px]">local_shipping</span>
            </div>
            <span class="font-headline-md text-headline-md text-on-surface">{{ $deliveredCount }}</span>
            <p class="text-[11px] text-secondary mt-xs">Entregadas exitosamente</p>
        </div>

    </section>

    {{-- ── Tabla + Quick Actions ───────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">

        {{-- Tabla: Aprobadas esperando entrega --}}
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl shadow-sm
                    border border-outline-variant overflow-hidden">

            <div class="p-lg border-b border-outline-variant">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Aprobadas — En espera de entrega</h3>
                <p class="font-body-md text-body-md text-secondary mt-xs">Solicitudes aprobadas que aún no han sido entregadas.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low">
                            <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Código</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Empleado</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Fecha aprobación</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">

                        @forelse ($approvedWaitingDelivery as $request)
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="px-lg py-md font-body-md text-body-md text-on-surface font-semibold">
                                    {{ $request->code ?? 'SOL-' . str_pad($request->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-lg py-md font-body-md text-body-md text-secondary">
                                    {{ $request->employee->name ?? '—' }}
                                </td>
                                <td class="px-lg py-md font-body-md text-body-md text-secondary">
                                    {{ $request->approved_at ? \Carbon\Carbon::parse($request->approved_at)->format('d/m/Y') : '—' }}
                                </td>
                                <td class="px-lg py-md text-right">
                                    <a href="{{ route('manager.requests.show', $request) }}"
                                       class="inline-flex items-center gap-xs text-primary font-label-md text-label-md
                                              hover:bg-surface-container p-2 rounded-lg transition-all">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-lg py-xl text-center font-body-md text-body-md text-secondary">
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
            <p class="font-body-md text-body-md text-secondary -mt-xs">Navega a las secciones clave.</p>

            <div class="flex flex-col gap-sm mt-sm">

                <a href="{{ route('manager.requests.index', ['status' => 'pending']) }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          bg-surface-container-lowest border border-outline-variant
                          text-on-surface font-label-md text-label-md font-medium
                          hover:bg-surface-container hover:border-primary transition-all group">
                    <span class="material-symbols-outlined text-[18px] text-secondary group-hover:text-primary transition-colors">pending_actions</span>
                    Solicitudes pendientes
                    @if ($pendingCount > 0)
                        <span class="ml-auto px-sm py-0.5 rounded-full text-[11px] font-bold
                                     bg-amber-100 text-amber-700 border border-amber-200">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('manager.requests.index') }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          bg-surface-container-lowest border border-outline-variant
                          text-on-surface font-label-md text-label-md font-medium
                          hover:bg-surface-container hover:border-primary transition-all group">
                    <span class="material-symbols-outlined text-[18px] text-secondary group-hover:text-primary transition-colors">assignment</span>
                    Todas las solicitudes
                </a>

                <a href="{{ route('manager.requests.index', ['status' => 'approved']) }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          bg-surface-container-lowest border border-outline-variant
                          text-on-surface font-label-md text-label-md font-medium
                          hover:bg-surface-container hover:border-primary transition-all group">
                    <span class="material-symbols-outlined text-[18px] text-secondary group-hover:text-primary transition-colors">local_shipping</span>
                    Por entregar
                </a>

                <a href="{{ route('manager.requests.index', ['status' => 'rejected']) }}"
                   class="flex items-center gap-sm px-md py-sm rounded-lg
                          bg-surface-container-lowest border border-outline-variant
                          text-on-surface font-label-md text-label-md font-medium
                          hover:bg-surface-container hover:border-primary transition-all group">
                    <span class="material-symbols-outlined text-[18px] text-secondary group-hover:text-primary transition-colors">history</span>
                    Rechazadas
                </a>

            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('tbody tr').forEach(row => {
            row.style.transition = 'transform 0.2s ease-out';
            row.addEventListener('mouseenter', () => row.style.transform = 'translateX(4px)');
            row.addEventListener('mouseleave', () => row.style.transform = 'translateX(0)');
        });
    });
</script>
@endpush
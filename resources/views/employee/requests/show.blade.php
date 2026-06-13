{{--
|─────────────────────────────────────────────────────────────────────────────
| employee/requests/show.blade.php
|
| Vista de detalle de una solicitud.
| Bifurca según $supplyRequest->isDraft():
|   - Draft    → gestión activa de ítems (Doc 8)
|   - Enviada  → solo lectura con breadcrumb (Doc 9)
|
| Controlador: Employee\SupplyRequestController@show
| Variables:
|   $supplyRequest  (SupplyRequest con requestItems.item cargados)
|   $items          (Collection de Item activos — solo usado en vista draft)
|─────────────────────────────────────────────────────────────────────────────
--}}

@extends('layouts.app')

@section('title', 'SOL-' . str_pad($supplyRequest->id, 3, '0', STR_PAD_LEFT))

@section('content')

@if ($supplyRequest->isDraft())
    {{-- ════════════════════════════════════════════════════════════════════
    |  VISTA DRAFT — Gestión activa de ítems (Doc 8)
    ══════════════════════════════════════════════════════════════════════ --}}

    {{-- ── Encabezado ─────────────────────────────────────────────────────── --}}
    <div class="flex justify-between items-center mb-xl">
        <h2 class="font-headline-lg text-headline-lg text-primary">
            Create Request
        </h2>
        <a href="{{ route('employee.requests.index') }}"
           class="inline-flex items-center gap-sm
                  text-secondary font-bold
                  hover:bg-surface-container px-md py-sm rounded-lg
                  transition-all">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to List
        </a>
    </div>

    {{-- ── Grid: Ítems disponibles + Ítems seleccionados ──────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-xl">

        {{-- ── Ítems disponibles ───────────────────────────────────────────── --}}
        <section class="space-y-md bg-surface-container-lowest p-lg rounded-xl
                        border border-outline-variant shadow-sm">

            <div class="flex items-center justify-between">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">
                    Available Items
                </h3>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-sm top-1/2
                                 -translate-y-1/2 text-outline text-[18px]">search</span>
                    <input type="text"
                           id="item-search"
                           placeholder="Search inventory..."
                           class="pl-xl pr-sm py-xs
                                  font-label-md text-label-md
                                  border border-outline-variant rounded-lg
                                  focus:ring-2 focus:ring-primary outline-none
                                  w-48 bg-surface-container-low
                                  placeholder:text-secondary">
                </div>
            </div>

            <div class="border border-outline-variant rounded-lg overflow-hidden">
                <table class="w-full text-left" id="items-table">
                    <thead class="bg-surface-container-low
                                  font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                        <tr>
                            <th class="px-md py-sm">Item Name</th>
                            <th class="px-md py-sm w-24">Qty</th>
                            <th class="px-md py-sm text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">

                        @forelse ($items as $item)
                            <tr class="hover:bg-surface-container-low transition-colors js-item-row"
                                data-name="{{ strtolower($item->name) }}">

                                <td class="px-md py-md font-body-md text-body-md text-on-surface">
                                    {{ $item->name }}
                                </td>

                                <td class="px-md py-md">
                                    <input type="number"
                                           id="qty-{{ $item->id }}"
                                           value="1" min="1"
                                           class="w-16 px-xs py-xs text-center
                                                  border border-outline-variant rounded
                                                  focus:border-primary focus:ring-1 focus:ring-primary
                                                  outline-none font-body-md text-body-md">
                                </td>

                                <td class="px-md py-md text-right">
                                    {{-- Formulario POST para agregar ítem --}}
                                    <form method="POST"
                                          action="{{ route('employee.requests.items.add', $supplyRequest) }}"
                                          class="inline js-add-form"
                                          data-item-id="{{ $item->id }}">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="quantity" class="js-qty-input" value="1">
                                        <button type="submit"
                                                class="inline-flex items-center gap-xs
                                                       bg-primary text-on-primary
                                                       px-sm py-xs rounded
                                                       font-label-md text-label-md
                                                       transition-transform active:scale-95
                                                       hover:opacity-90">
                                            <span class="material-symbols-outlined text-[16px]">add</span>
                                            Add
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"
                                    class="px-md py-lg text-center font-body-md text-body-md text-secondary">
                                    No hay ítems disponibles.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </section>

        {{-- ── Ítems seleccionados ──────────────────────────────────────────── --}}
        <section class="space-y-md bg-surface-container-lowest p-lg rounded-xl
                        border border-outline-variant shadow-sm h-fit">

            <div class="flex items-center justify-between">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">
                    Selected Items
                </h3>
                <span class="px-sm py-xs bg-secondary-container text-on-secondary-container
                             rounded-full font-label-md text-label-md font-bold">
                    {{ $supplyRequest->requestItems->count() }}
                    {{ $supplyRequest->requestItems->count() === 1 ? 'Item' : 'Items' }}
                </span>
            </div>

            <div class="border border-outline-variant rounded-lg overflow-hidden bg-white">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low
                                  font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                        <tr>
                            <th class="px-md py-sm">Item</th>
                            <th class="px-md py-sm w-24 text-center">Quantity</th>
                            <th class="px-md py-sm text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">

                        @forelse ($supplyRequest->requestItems as $requestItem)
                            <tr class="hover:bg-error/5 transition-colors">

                                <td class="px-md py-md font-body-md text-body-md text-on-surface">
                                    {{ $requestItem->item->name }}
                                </td>

                                <td class="px-md py-md font-bold text-center font-body-md text-body-md">
                                    {{ $requestItem->quantity }}
                                </td>

                                <td class="px-md py-md text-right">
                                    {{-- Formulario DELETE para eliminar ítem --}}
                                    <form method="POST"
                                          action="{{ route('employee.requests.items.remove', [$supplyRequest, $requestItem->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-error hover:bg-error/10
                                                       p-xs rounded transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"
                                    class="px-md py-lg text-center font-body-md text-body-md text-secondary italic">
                                    Aún no has agregado ítems.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Nota informativa --}}
            @if ($supplyRequest->requestItems->count() > 0)
                <div class="flex items-center gap-sm p-md
                            bg-surface-container-low rounded-lg border-l-4 border-primary">
                    <span class="material-symbols-outlined text-primary text-[18px]">info</span>
                    <p class="font-label-md text-label-md text-secondary">
                        Revisa los ítems antes de enviar. Una vez enviada, no podrás modificar la solicitud.
                    </p>
                </div>
            @endif

        </section>

    </div>

    {{-- ── Sección de notas/justificación ─────────────────────────────────── --}}
    {{-- Justificación editable inline --}}
    <section class="mt-xl space-y-md bg-surface-container-lowest p-lg rounded-xl
                    border border-outline-variant shadow-sm">

        <div class="flex items-center gap-sm">
            <span class="material-symbols-outlined text-primary"
                style="font-variation-settings: 'FILL' 1;">description</span>
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Justification</h3>
        </div>

        <form method="POST" action="{{ route('employee.requests.update', $supplyRequest) }}">
            @csrf
            @method('PATCH')

            {{-- Campo oculto para mantener priority actual --}}
            <input type="hidden" name="priority" value="{{ $supplyRequest->priority }}">

            <textarea name="notes"
                    id="justification"
                    rows="6"
                    maxlength="500"
                    placeholder="Explain why these items are required for your department/project..."
                    class="w-full p-md bg-white border border-outline-variant rounded-xl
                            focus:ring-2 focus:ring-primary outline-none
                            font-body-md text-body-md shadow-sm
                            transition-all resize-none
                            placeholder:text-secondary">{{ old('notes', $supplyRequest->notes) }}</textarea>

            <div class="flex items-center justify-between mt-sm">
                <span class="font-label-md text-label-md text-outline" id="char-count">
                    {{ strlen($supplyRequest->notes ?? '') }} / 500 characters
                </span>
                <button type="submit"
                        class="px-lg py-sm bg-surface-container-low
                            border border-outline-variant text-secondary font-bold rounded-lg
                            hover:bg-surface-container transition-all active:scale-95
                            font-label-md text-label-md">
                    Save Notes
                </button>
            </div>

        </form>

    </section>

    {{-- ── Footer de acciones ──────────────────────────────────────────────── --}}
    <div class="mt-xl pt-md border-t border-outline-variant
                flex items-center justify-between pb-xl">

        {{-- Delete Draft — conectado a destroy() --}}
        <form method="POST"
              action="{{ route('employee.requests.destroy', $supplyRequest) }}"
              onsubmit="return confirm('¿Estás seguro de que deseas eliminar este borrador? Esta acción no se puede deshacer.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-sm
                           text-error font-bold
                           hover:bg-error-container/20 px-md py-sm rounded-lg
                           transition-all active:scale-95">
                <span class="material-symbols-outlined">delete_forever</span>
                Delete Draft
            </button>
        </form>

        <div class="flex items-center gap-md">

            {{-- Save Draft → edit --}}
            <a href="{{ route('employee.requests.index', $supplyRequest) }}"
               class="px-xl py-sm bg-white border border-outline
                      text-secondary font-bold rounded-lg
                      hover:bg-surface-container transition-all active:scale-95">
                Save Draft
            </a>

            {{-- Submit Request --}}
            <form method="POST"
                  action="{{ route('employee.requests.submit', $supplyRequest) }}"
                  onsubmit="return confirm('¿Enviar esta solicitud? No podrás modificarla después.')">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="px-xl py-sm bg-primary text-on-primary font-bold rounded-lg
                               shadow-lg hover:shadow-xl hover:-translate-y-0.5
                               transition-all active:scale-95
                               {{ $supplyRequest->requestItems->count() === 0
                                  ? 'opacity-50 cursor-not-allowed'
                                  : '' }}"
                        {{ $supplyRequest->requestItems->count() === 0 ? 'disabled' : '' }}>
                    Submit Request
                </button>
            </form>

        </div>
    </div>

@else
    {{-- ════════════════════════════════════════════════════════════════════
    |  VISTA SOLO LECTURA — Solicitud enviada (Doc 9)
    ══════════════════════════════════════════════════════════════════════ --}}

    {{-- ── Encabezado con breadcrumb ──────────────────────────────────────── --}}
    <div class="flex flex-col gap-md mb-xl">

        <div class="flex items-center gap-md">
            <h1 class="font-headline-lg text-headline-lg text-primary">
                Request Details
            </h1>

            {{-- Badge de estado --}}
            @switch($supplyRequest->status)
                @case('pending')
                    <span class="px-md py-0.5 rounded-full
                                 bg-amber-100 text-amber-800
                                 font-label-md text-label-md
                                 inline-flex items-center gap-xs
                                 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Pending
                    </span>
                    @break
                @case('approved')
                    <span class="px-md py-0.5 rounded-full
                                 bg-emerald-100 text-emerald-800
                                 font-label-md text-label-md
                                 inline-flex items-center gap-xs
                                 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Approved
                    </span>
                    @break
                @case('rejected')
                    <span class="px-md py-0.5 rounded-full
                                 bg-error-container text-on-error-container
                                 font-label-md text-label-md
                                 inline-flex items-center gap-xs
                                 border border-red-200">
                        <span class="w-2 h-2 rounded-full bg-error"></span>
                        Rejected
                    </span>
                    @break
                @default
                    <span class="px-md py-0.5 rounded-full
                                 bg-surface-container text-secondary
                                 font-label-md text-label-md border border-outline-variant">
                        {{ ucfirst($supplyRequest->status) }}
                    </span>
            @endswitch
        </div>

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-xs font-label-sm text-label-sm text-secondary">
            <a href="{{ route('employee.requests.index') }}"
               class="hover:text-primary transition-colors">
                My Requests
            </a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-on-surface">
                SOL-{{ str_pad($supplyRequest->id, 3, '0', STR_PAD_LEFT) }}
            </span>
        </nav>

    </div>

    {{-- ── Card principal ──────────────────────────────────────────────────── --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant
                shadow-sm overflow-hidden max-w-4xl mx-auto">

        <div class="p-xl space-y-xl">

            {{-- Info: código + fecha --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div class="p-md bg-surface-container-low rounded-lg
                            border border-outline-variant flex flex-col gap-xs">
                    <span class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                        Request Code
                    </span>
                    <span class="font-body-lg text-body-lg text-on-surface font-semibold">
                        SOL-{{ str_pad($supplyRequest->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <div class="p-md bg-surface-container-low rounded-lg
                            border border-outline-variant flex flex-col gap-xs">
                    <span class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                        Date Requested
                    </span>
                    <span class="font-body-lg text-body-lg text-on-surface font-semibold">
                        {{ $supplyRequest->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            {{-- Tabla de ítems (solo lectura) --}}
            <section>
                <div class="flex items-center gap-sm mb-md">
                    <span class="material-symbols-outlined text-primary"
                          style="font-variation-settings: 'FILL' 1;">inventory_2</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface">
                        Requested Items
                    </h3>
                </div>

                <div class="border border-outline-variant rounded-xl overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-surface-container-high">
                            <tr>
                                <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase">
                                    Item
                                </th>
                                <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase text-right">
                                    Quantity
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant bg-surface-container-lowest">

                            @forelse ($supplyRequest->requestItems as $requestItem)
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="px-lg py-md flex items-center gap-md">
                                        <div class="w-10 h-10 rounded-lg bg-surface-container-high
                                                    flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-secondary text-[18px]">
                                                inventory_2
                                            </span>
                                        </div>
                                        <span class="font-body-md text-body-md text-on-surface font-medium">
                                            {{ $requestItem->item->name }}
                                        </span>
                                    </td>
                                    <td class="px-lg py-md text-right font-body-md text-body-md text-on-surface">
                                        x{{ $requestItem->quantity }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2"
                                        class="px-lg py-lg text-center font-body-md text-body-md text-secondary">
                                        Sin ítems registrados.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Justificación --}}
            <section>
                <div class="flex items-center gap-sm mb-md">
                    <span class="material-symbols-outlined text-primary"
                          style="font-variation-settings: 'FILL' 1;">description</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface">
                        Justification
                    </h3>
                </div>
                <div class="p-lg bg-surface-container-low rounded-xl
                            border border-outline-variant border-dashed">
                    <p class="font-body-md text-body-md text-secondary leading-relaxed italic">
                        @if ($supplyRequest->notes)
                            "{{ $supplyRequest->notes }}"
                        @else
                            <span class="not-italic">Sin justificación registrada.</span>
                        @endif
                    </p>
                </div>
            </section>

        </div>

        {{-- Footer --}}
        <div class="px-xl py-lg border-t border-outline-variant
                    bg-surface-container-low flex justify-start">
            <a href="{{ route('employee.requests.index') }}"
               class="px-xl py-sm bg-primary text-on-primary rounded-lg
                      font-label-md text-label-md
                      hover:opacity-90 active:scale-95 transition-all shadow-md">
                Back to My Requests
            </a>
        </div>

    </div>

@endif

@endsection

{{-- ── Scripts ─────────────────────────────────────────────────────────────── --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── Sincronizar input de cantidad con el hidden del form ──────────────
    document.querySelectorAll('.js-item-row').forEach(row => {
        const itemId  = row.querySelector('.js-add-form')?.dataset.itemId;
        const qtyInput = document.getElementById('qty-' + itemId);
        const hiddenQty = row.querySelector('.js-qty-input');

        if (qtyInput && hiddenQty) {
            qtyInput.addEventListener('input', () => {
                hiddenQty.value = qtyInput.value;
            });
        }
    });

    // ── Búsqueda en tiempo real sobre la tabla de ítems ──────────────────
    const searchInput = document.getElementById('item-search');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase().trim();
            document.querySelectorAll('.js-item-row').forEach(row => {
                const name = row.dataset.name ?? '';
                row.style.display = name.includes(query) ? '' : 'none';
            });
        });
    }

});
</script>
@endpush
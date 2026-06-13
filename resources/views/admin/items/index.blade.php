<x-admin.crud-list-layout
    title="Ítems"
    description="Gestiona el catálogo de ítems disponibles para solicitudes."
    :createRoute="route('admin.items.create')"
    createLabel="Nuevo ítem">

    {{-- ── Filtros ──────────────────────────────────────────────────────── --}}
    <x-slot:filters>
        <form method="GET" action="{{ route('admin.items.index') }}"
              class="flex flex-col md:flex-row gap-md items-center justify-between">

            <div class="relative w-full md:w-96">
                <span class="absolute left-md top-1/2 -translate-y-1/2
                             material-symbols-outlined text-outline text-[20px]">search</span>
                <input name="search"
                       type="text"
                       value="{{ request('search') }}"
                       placeholder="Buscar ítem..."
                       class="w-full pl-xl pr-md py-sm
                              bg-surface-container-lowest border border-outline-variant rounded-lg
                              focus:ring-2 focus:ring-primary focus:border-transparent
                              outline-none transition-all text-body-md font-body-md">
            </div>

            <div class="relative flex-shrink-0">
                <select name="active"
                        onchange="this.form.submit()"
                        class="appearance-none w-full md:w-40 bg-surface-container-lowest
                               border border-outline-variant rounded-lg
                               px-md py-sm pr-xl text-body-md font-body-md
                               focus:ring-2 focus:ring-primary outline-none cursor-pointer">
                    <option value="">Todos</option>
                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
                <span class="absolute right-sm top-1/2 -translate-y-1/2
                             material-symbols-outlined text-outline pointer-events-none text-[20px]">
                    expand_more
                </span>
            </div>

        </form>
    </x-slot:filters>

    {{-- ── Columnas ─────────────────────────────────────────────────────── --}}
    <x-slot:columns>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Nombre</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Descripción</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Unidad</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Precio estimado</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Estado</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-right">Acciones</th>
    </x-slot:columns>

    {{-- ── Filas ────────────────────────────────────────────────────────── --}}
    <x-slot:rows>
        @forelse ($items as $item)
            <tr class="hover:bg-surface-container transition-colors group">

                <td class="px-lg py-md font-body-md font-semibold text-on-surface">
                    {{ $item->name }}
                </td>

                <td class="px-lg py-md font-body-md text-secondary max-w-[200px] truncate">
                    {{ $item->description ?? '—' }}
                </td>

                <td class="px-lg py-md">
                    <span class="inline-flex items-center gap-xs px-sm py-xs
                                 rounded-full bg-surface-container-high text-secondary
                                 font-label-md text-label-md capitalize">
                        {{ $item->unit }}
                    </span>
                </td>

                <td class="px-lg py-md font-body-md text-on-surface">
                    ${{ number_format($item->estimated_price, 2) }}
                </td>

                <td class="px-lg py-md">
                    @if ($item->active)
                        <span class="inline-flex items-center gap-xs px-sm py-xs
                                     rounded-full bg-emerald-100 text-emerald-700
                                     border border-emerald-200 font-label-md text-label-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-xs px-sm py-xs
                                     rounded-full bg-surface-container-high text-secondary
                                     border border-outline-variant font-label-md text-label-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                            Inactivo
                        </span>
                    @endif
                </td>

                <td class="px-lg py-md text-right">
                    <div class="flex items-center justify-end gap-sm
                                opacity-0 group-hover:opacity-100 transition-opacity">

                        {{-- Toggle activo/inactivo --}}
                        <form method="POST"
                              action="{{ route('admin.items.toggle', $item) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="p-xs rounded-lg transition-colors
                                           {{ $item->active
                                               ? 'text-secondary hover:text-amber-600 hover:bg-amber-50'
                                               : 'text-secondary hover:text-emerald-600 hover:bg-emerald-50' }}"
                                    title="{{ $item->active ? 'Desactivar' : 'Activar' }}">
                                <span class="material-symbols-outlined text-[20px]">
                                    {{ $item->active ? 'toggle_on' : 'toggle_off' }}
                                </span>
                            </button>
                        </form>

                        <a href="{{ route('admin.items.edit', $item) }}"
                           class="p-xs rounded-lg text-secondary hover:text-primary
                                  hover:bg-surface-container transition-colors"
                           title="Editar">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.items.destroy', $item) }}"
                              onsubmit="return confirm('¿Eliminar el ítem {{ addslashes($item->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="p-xs rounded-lg text-secondary hover:text-error
                                           hover:bg-error-container transition-colors"
                                    title="Eliminar">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>

                    </div>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-lg py-xl text-center text-secondary font-body-md">
                    <span class="material-symbols-outlined text-[40px] block mb-sm text-outline">
                        inventory_2
                    </span>
                    No se encontraron ítems.
                </td>
            </tr>
        @endforelse
    </x-slot:rows>

    {{-- ── Paginación ───────────────────────────────────────────────────── --}}
    <x-slot:pagination>
        @if ($items->hasPages())
            <span class="text-body-md text-secondary">
                Mostrando {{ $items->firstItem() }}–{{ $items->lastItem() }}
                de {{ $items->total() }} ítems
            </span>
            <div>
                {{ $items->links() }}
            </div>
        @endif
    </x-slot:pagination>

</x-admin.crud-list-layout>
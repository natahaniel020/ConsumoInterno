<x-admin.crud-list-layout
    title="Departamentos"
    description="Crea, actualiza y gestiona los departamentos organizacionales."
    :createRoute="route('admin.departments.create')"
    createLabel="Nuevo departamento">

    {{-- ── Filtros ──────────────────────────────────────────────────────── --}}
    <x-slot:filters>
        <form method="GET" action="{{ route('admin.departments.index') }}"
              class="flex flex-col md:flex-row gap-md items-center justify-between">
            <div class="relative w-full md:w-96">
                <span class="absolute left-md top-1/2 -translate-y-1/2
                             material-symbols-outlined text-outline text-[20px]">search</span>
                <input name="search"
                       type="text"
                       value="{{ request('search') }}"
                       placeholder="Buscar departamento..."
                       class="w-full pl-xl pr-md py-sm
                              bg-surface-container-lowest border border-outline-variant rounded-lg
                              focus:ring-2 focus:ring-primary focus:border-transparent
                              outline-none transition-all text-body-md font-body-md">
            </div>
        </form>
    </x-slot:filters>

    {{-- ── Columnas ─────────────────────────────────────────────────────── --}}
    <x-slot:columns>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Nombre</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Descripción</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Usuarios</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Solicitudes</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-right">Acciones</th>
    </x-slot:columns>

    {{-- ── Filas ────────────────────────────────────────────────────────── --}}
    <x-slot:rows>
        @forelse ($departments as $department)
            <tr class="hover:bg-surface-container transition-colors group">

                <td class="px-lg py-md font-body-md font-semibold text-on-surface">
                    {{ $department->name }}
                </td>

                <td class="px-lg py-md font-body-md text-secondary">
                    {{ $department->description ?? '—' }}
                </td>

                <td class="px-lg py-md">
                    <span class="inline-flex items-center gap-xs px-sm py-xs
                                 rounded-full bg-secondary-container text-on-secondary-container
                                 font-label-md text-label-md">
                        <span class="material-symbols-outlined text-[14px]">group</span>
                        {{ $department->users_count }}
                    </span>
                </td>

                <td class="px-lg py-md">
                    <span class="inline-flex items-center gap-xs px-sm py-xs
                                 rounded-full bg-surface-container-high text-secondary
                                 font-label-md text-label-md">
                        <span class="material-symbols-outlined text-[14px]">assignment</span>
                        {{ $department->supply_requests_count }}
                    </span>
                </td>

                <td class="px-lg py-md text-right">
                    <div class="flex items-center justify-end gap-sm
                                opacity-0 group-hover:opacity-100 transition-opacity">

                        <a href="{{ route('admin.departments.edit', $department) }}"
                           class="p-xs rounded-lg text-secondary hover:text-primary
                                  hover:bg-surface-container transition-colors"
                           title="Editar">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.departments.destroy', $department) }}"
                              onsubmit="return confirm('¿Eliminar el departamento {{ addslashes($department->name) }}?')">
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
                <td colspan="5" class="px-lg py-xl text-center text-secondary font-body-md">
                    <span class="material-symbols-outlined text-[40px] block mb-sm text-outline">
                        corporate_fare
                    </span>
                    No se encontraron departamentos.
                </td>
            </tr>
        @endforelse
    </x-slot:rows>

    {{-- ── Paginación ───────────────────────────────────────────────────── --}}
    <x-slot:pagination>
        @if ($departments->hasPages())
            <span class="text-body-md text-secondary">
                Mostrando {{ $departments->firstItem() }}–{{ $departments->lastItem() }}
                de {{ $departments->total() }} departamentos
            </span>
            <div>
                {{ $departments->links() }}
            </div>
        @endif
    </x-slot:pagination>

</x-admin.crud-list-layout>
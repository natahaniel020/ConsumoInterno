<x-admin.crud-list-layout
    title="Usuarios"
    description="Gestiona los usuarios del sistema, sus roles y departamentos."
    :createRoute="route('admin.users.create')"
    createLabel="Nuevo usuario">

    {{-- ── Filtros ──────────────────────────────────────────────────────── --}}
    <x-slot:filters>
        <form method="GET" action="{{ route('admin.users.index') }}"
              class="flex flex-col md:flex-row gap-md items-center justify-between">

            <div class="relative w-full md:w-96">
                <span class="absolute left-md top-1/2 -translate-y-1/2
                             material-symbols-outlined text-outline text-[20px]">search</span>
                <input name="search"
                       type="text"
                       value="{{ request('search') }}"
                       placeholder="Buscar por nombre o email..."
                       class="w-full pl-xl pr-md py-sm
                              bg-surface-container-lowest border border-outline-variant rounded-lg
                              focus:ring-2 focus:ring-primary focus:border-transparent
                              outline-none transition-all text-body-md font-body-md">
            </div>

            <div class="relative flex-shrink-0">
                <select name="role"
                        onchange="this.form.submit()"
                        class="appearance-none w-full md:w-40 bg-surface-container-lowest
                               border border-outline-variant rounded-lg
                               px-md py-sm pr-xl text-body-md font-body-md
                               focus:ring-2 focus:ring-primary outline-none cursor-pointer">
                    <option value="">Todos los roles</option>
                    <option value="manager"  {{ request('role') === 'manager'  ? 'selected' : '' }}>Manager</option>
                    <option value="employee" {{ request('role') === 'employee' ? 'selected' : '' }}>Empleado</option>
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
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Usuario</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Email</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Rol</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider">Departamento</th>
        <th class="px-lg py-md font-label-sm text-label-sm text-secondary uppercase tracking-wider text-right">Acciones</th>
    </x-slot:columns>

    {{-- ── Filas ────────────────────────────────────────────────────────── --}}
    <x-slot:rows>
        @forelse ($users as $user)
            @php
                $roleMap = [
                    'manager'  => ['bg-secondary-container text-on-secondary-container', 'badge',  'Manager'],
                    'employee' => ['bg-surface-container-high text-secondary',           'person', 'Empleado'],
                ];
                [$rClasses, $rIcon, $rLabel] = $roleMap[$user->role] ?? ['bg-surface-container-high text-secondary', 'person', ucfirst($user->role)];
            @endphp

            <tr class="hover:bg-surface-container transition-colors group">

                {{-- Usuario --}}
                <td class="px-lg py-md">
                    <div class="flex items-center gap-md">
                        <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container
                                    flex items-center justify-center font-label-md font-semibold
                                    select-none flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' '), 1, 1)) }}
                        </div>
                        <span class="font-body-md font-semibold text-on-surface">{{ $user->name }}</span>
                    </div>
                </td>

                {{-- Email --}}
                <td class="px-lg py-md font-body-md text-secondary">
                    {{ $user->email }}
                </td>

                {{-- Rol --}}
                <td class="px-lg py-md">
                    <span class="inline-flex items-center gap-xs px-sm py-xs
                                 rounded-full font-label-md text-label-md {{ $rClasses }}">
                        <span class="material-symbols-outlined text-[14px]">{{ $rIcon }}</span>
                        {{ $rLabel }}
                    </span>
                </td>

                {{-- Departamento --}}
                <td class="px-lg py-md font-body-md text-secondary">
                    {{ $user->department->name ?? '—' }}
                </td>

                {{-- Acciones --}}
                <td class="px-lg py-md text-right">
                    <div class="flex items-center justify-end gap-sm
                                opacity-0 group-hover:opacity-100 transition-opacity">

                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="p-xs rounded-lg text-secondary hover:text-primary
                                  hover:bg-surface-container transition-colors"
                           title="Editar">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('¿Eliminar al usuario {{ addslashes($user->name) }}?')">
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
                        group
                    </span>
                    No se encontraron usuarios.
                </td>
            </tr>
        @endforelse
    </x-slot:rows>

    {{-- ── Paginación ───────────────────────────────────────────────────── --}}
    <x-slot:pagination>
        @if ($users->hasPages())
            <span class="text-body-md text-secondary">
                Mostrando {{ $users->firstItem() }}–{{ $users->lastItem() }}
                de {{ $users->total() }} usuarios
            </span>
            <div>
                {{ $users->links() }}
            </div>
        @endif
    </x-slot:pagination>

</x-admin.crud-list-layout>
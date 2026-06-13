<x-admin.crud-form-layout
    title="Nuevo Usuario"
    description="Completa los datos para registrar un nuevo usuario en el sistema."
    :action="route('admin.users.store')"
    method="POST"
    submitLabel="Crear usuario"
    :cancelRoute="route('admin.users.index')">

    <x-slot:formFields>

        {{-- Nombre --}}
        <div class="space-y-xs">
            <label class="block font-label-md text-label-md text-on-surface-variant" for="name">
                Nombre completo <span class="text-error">*</span>
            </label>
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}"
                   placeholder="e.g. Juan Pérez"
                   required
                   class="w-full bg-surface border border-outline-variant rounded-lg
                          px-md py-[10px] text-body-md
                          focus:ring-2 focus:ring-primary focus:border-transparent
                          outline-none transition-all placeholder:text-outline">
            @error('name')
                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="space-y-xs">
            <label class="block font-label-md text-label-md text-on-surface-variant" for="email">
                Correo electrónico <span class="text-error">*</span>
            </label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="e.g. juan@empresa.com"
                   required
                   class="w-full bg-surface border border-outline-variant rounded-lg
                          px-md py-[10px] text-body-md
                          focus:ring-2 focus:ring-primary focus:border-transparent
                          outline-none transition-all placeholder:text-outline">
            @error('email')
                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
            @enderror
        </div>

        {{-- Rol + Departamento --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-lg">

            <div class="space-y-xs">
                <label class="block font-label-md text-label-md text-on-surface-variant" for="role">
                    Rol <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <select id="role" name="role" required
                            class="w-full appearance-none bg-surface border border-outline-variant rounded-lg
                                   px-md py-[10px] pr-xl text-body-md
                                   focus:ring-2 focus:ring-primary focus:border-transparent
                                   outline-none transition-all cursor-pointer">
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Seleccionar...</option>
                        <option value="manager"  {{ old('role') === 'manager'  ? 'selected' : '' }}>Manager</option>
                        <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Empleado</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-md pointer-events-none">
                        <span class="material-symbols-outlined text-outline text-[20px]">expand_more</span>
                    </div>
                </div>
                @error('role')
                    <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-xs">
                <label class="block font-label-md text-label-md text-on-surface-variant" for="department_id">
                    Departamento
                </label>
                <div class="relative">
                    <select id="department_id" name="department_id"
                            class="w-full appearance-none bg-surface border border-outline-variant rounded-lg
                                   px-md py-[10px] pr-xl text-body-md
                                   focus:ring-2 focus:ring-primary focus:border-transparent
                                   outline-none transition-all cursor-pointer">
                        <option value="">Sin departamento</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-md pointer-events-none">
                        <span class="material-symbols-outlined text-outline text-[20px]">expand_more</span>
                    </div>
                </div>
                @error('department_id')
                    <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Contraseña --}}
        <div class="space-y-xs">
            <label class="block font-label-md text-label-md text-on-surface-variant" for="password">
                Contraseña <span class="text-error">*</span>
            </label>
            <div class="relative">
                <input type="password" id="password" name="password"
                       placeholder="Mínimo 8 caracteres"
                       required
                       class="w-full bg-surface border border-outline-variant rounded-lg
                              px-md py-[10px] pr-xl text-body-md
                              focus:ring-2 focus:ring-primary focus:border-transparent
                              outline-none transition-all placeholder:text-outline">
                <button type="button"
                        onclick="togglePassword('password', 'icon-password')"
                        class="absolute inset-y-0 right-0 flex items-center pr-md
                               text-outline hover:text-primary transition-colors cursor-pointer">
                    <span id="icon-password" class="material-symbols-outlined text-[20px]">visibility</span>
                </button>
            </div>
            @error('password')
                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmar contraseña --}}
        <div class="space-y-xs">
            <label class="block font-label-md text-label-md text-on-surface-variant" for="password_confirmation">
                Confirmar contraseña <span class="text-error">*</span>
            </label>
            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation"
                       placeholder="Repite la contraseña"
                       required
                       class="w-full bg-surface border border-outline-variant rounded-lg
                              px-md py-[10px] pr-xl text-body-md
                              focus:ring-2 focus:ring-primary focus:border-transparent
                              outline-none transition-all placeholder:text-outline">
                <button type="button"
                        onclick="togglePassword('password_confirmation', 'icon-confirm')"
                        class="absolute inset-y-0 right-0 flex items-center pr-md
                               text-outline hover:text-primary transition-colors cursor-pointer">
                    <span id="icon-confirm" class="material-symbols-outlined text-[20px]">visibility</span>
                </button>
            </div>
            @error('password_confirmation')
                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
            @enderror
        </div>

    </x-slot:formFields>

</x-admin.crud-form-layout>

@push('scripts')
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type  = 'text';
            icon.innerText = 'visibility_off';
        } else {
            input.type  = 'password';
            icon.innerText = 'visibility';
        }
    }
</script>
@endpush
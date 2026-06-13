<x-admin.crud-form-layout
    title="Editar Departamento"
    description="Modifica los datos del departamento."
    :action="route('admin.departments.update', $department)"
    method="PUT"
    submitLabel="Guardar cambios"
    :cancelRoute="route('admin.departments.index')">

    <x-slot:formFields>

        <div class="space-y-xs">
            <label class="block font-label-md text-label-md text-on-surface-variant" for="name">
                Nombre <span class="text-error">*</span>
            </label>
            <input type="text" id="name" name="name"
                   value="{{ old('name', $department->name) }}"
                   required
                   class="w-full bg-surface border border-outline-variant rounded-lg
                          px-md py-[10px] text-body-md
                          focus:ring-2 focus:ring-primary focus:border-transparent
                          outline-none transition-all placeholder:text-outline">
            @error('name')
                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-xs">
            <label class="block font-label-md text-label-md text-on-surface-variant" for="description">
                Descripción
            </label>
            <textarea id="description" name="description" rows="4"
                      class="w-full bg-surface border border-outline-variant rounded-lg
                             px-md py-[10px] text-body-md
                             focus:ring-2 focus:ring-primary focus:border-transparent
                             outline-none transition-all resize-none">{{ old('description', $department->description) }}</textarea>
            @error('description')
                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
            @enderror
        </div>

    </x-slot:formFields>

</x-admin.crud-form-layout>
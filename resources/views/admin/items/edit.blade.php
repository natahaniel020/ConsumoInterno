<x-admin.crud-form-layout
    title="Editar Ítem"
    description="Modifica los datos del ítem del catálogo."
    :action="route('admin.items.update', $item)"
    method="PUT"
    submitLabel="Guardar cambios"
    :cancelRoute="route('admin.items.index')">

    <x-slot:formFields>

        {{-- Nombre --}}
        <div class="space-y-xs">
            <label class="block font-label-md text-label-md text-on-surface-variant" for="name">
                Nombre <span class="text-error">*</span>
            </label>
            <input type="text" id="name" name="name"
                   value="{{ old('name', $item->name) }}"
                   required
                   class="w-full bg-surface border border-outline-variant rounded-lg
                          px-md py-[10px] text-body-md
                          focus:ring-2 focus:ring-primary focus:border-transparent
                          outline-none transition-all placeholder:text-outline">
            @error('name')
                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="space-y-xs">
            <label class="block font-label-md text-label-md text-on-surface-variant" for="description">
                Descripción
            </label>
            <textarea id="description" name="description" rows="3"
                      class="w-full bg-surface border border-outline-variant rounded-lg
                             px-md py-[10px] text-body-md
                             focus:ring-2 focus:ring-primary focus:border-transparent
                             outline-none transition-all resize-none">{{ old('description', $item->description) }}</textarea>
            @error('description')
                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
            @enderror
        </div>

        {{-- Unidad + Precio estimado --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-lg">

            <div class="space-y-xs">
                <label class="block font-label-md text-label-md text-on-surface-variant" for="unit">
                    Unidad <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <select id="unit" name="unit" required
                            class="w-full appearance-none bg-surface border border-outline-variant rounded-lg
                                   px-md py-[10px] pr-xl text-body-md
                                   focus:ring-2 focus:ring-primary focus:border-transparent
                                   outline-none transition-all cursor-pointer">
                        <option value="unidad" {{ old('unit', $item->unit) === 'unidad' ? 'selected' : '' }}>Unidad</option>
                        <option value="caja"   {{ old('unit', $item->unit) === 'caja'   ? 'selected' : '' }}>Caja</option>
                        <option value="kg"     {{ old('unit', $item->unit) === 'kg'     ? 'selected' : '' }}>Kilogramo (kg)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-md pointer-events-none">
                        <span class="material-symbols-outlined text-outline text-[20px]">expand_more</span>
                    </div>
                </div>
                @error('unit')
                    <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-xs">
                <label class="block font-label-md text-label-md text-on-surface-variant" for="estimated_price">
                    Precio estimado <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-md top-1/2 -translate-y-1/2
                                 font-body-md text-body-md text-outline select-none">$</span>
                    <input type="number" id="estimated_price" name="estimated_price"
                           value="{{ old('estimated_price', $item->estimated_price) }}"
                           min="0" step="0.01" required
                           class="w-full bg-surface border border-outline-variant rounded-lg
                                  pl-lg pr-md py-[10px] text-body-md
                                  focus:ring-2 focus:ring-primary focus:border-transparent
                                  outline-none transition-all">
                </div>
                @error('estimated_price')
                    <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Estado activo --}}
        <div class="flex items-center justify-between p-md
                    bg-surface-container-low rounded-lg border border-outline-variant">
            <div>
                <h4 class="font-label-md text-label-md text-on-surface">Estado activo</h4>
                <p class="text-[11px] text-secondary mt-xs">
                    Define si el ítem estará disponible para solicitudes.
                </p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="active" value="1" class="sr-only peer"
                       {{ old('active', $item->active) ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-outline-variant rounded-full peer
                            peer-checked:bg-primary
                            after:content-[''] after:absolute after:top-[2px] after:start-[2px]
                            after:bg-white after:border after:border-gray-300 after:rounded-full
                            after:h-5 after:w-5 after:transition-all
                            peer-checked:after:translate-x-full peer-checked:after:border-white">
                </div>
            </label>
        </div>

    </x-slot:formFields>

</x-admin.crud-form-layout>
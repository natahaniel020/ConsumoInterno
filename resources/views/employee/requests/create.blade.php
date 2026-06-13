{{--
|─────────────────────────────────────────────────────────────────────────────
| employee/requests/create.blade.php
|
| Formulario de creación de una nueva solicitud.
| Controlador: Employee\SupplyRequestController@create / store
| Variables: ninguna
|
| Al hacer submit → store() crea la solicitud y redirige a show()
| donde el empleado agrega los ítems.
|─────────────────────────────────────────────────────────────────────────────
--}}

@extends('layouts.app')

@section('title', 'Nueva Solicitud')

@section('content')

    {{-- ── Encabezado ─────────────────────────────────────────────────────── --}}
    <div class="flex justify-between items-center mb-xl">
        <h2 class="font-headline-lg text-headline-lg text-primary">
            New Request
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

    {{-- ── Formulario ──────────────────────────────────────────────────────── --}}
    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('employee.requests.store') }}">
            @csrf

            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant
                        shadow-sm overflow-hidden">

                <div class="p-xl space-y-xl">

                    {{-- ── Prioridad ───────────────────────────────────────── --}}
                    <div class="space-y-sm">
                        <label for="priority"
                               class="block font-headline-sm text-headline-sm text-on-surface">
                            Priority
                        </label>
                        <p class="font-body-md text-body-md text-secondary -mt-xs">
                            Selecciona el nivel de urgencia de esta solicitud.
                        </p>

                        <div class="grid grid-cols-3 gap-md mt-md">

                            {{-- Low --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority" value="low"
                                       class="sr-only peer"
                                       {{ old('priority') === 'low' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center gap-sm p-md rounded-xl
                                            border-2 border-outline-variant
                                            bg-surface-container-low
                                            peer-checked:border-primary
                                            peer-checked:bg-primary-fixed
                                            peer-checked:text-on-primary-fixed
                                            hover:bg-surface-container
                                            transition-all cursor-pointer">
                                    <span class="material-symbols-outlined text-[24px] text-secondary
                                                 peer-checked:text-primary">
                                        low_priority
                                    </span>
                                    <span class="font-label-md text-label-md font-semibold text-on-surface">
                                        Low
                                    </span>
                                    <span class="font-body-md text-body-md text-secondary text-center text-[11px]">
                                        No urgente
                                    </span>
                                </div>
                            </label>

                            {{-- Medium --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority" value="medium"
                                       class="sr-only peer"
                                       {{ old('priority', 'medium') === 'medium' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center gap-sm p-md rounded-xl
                                            border-2 border-outline-variant
                                            bg-surface-container-low
                                            peer-checked:border-primary
                                            peer-checked:bg-primary-fixed
                                            hover:bg-surface-container
                                            transition-all cursor-pointer">
                                    <span class="material-symbols-outlined text-[24px] text-secondary">
                                        density_medium
                                    </span>
                                    <span class="font-label-md text-label-md font-semibold text-on-surface">
                                        Medium
                                    </span>
                                    <span class="font-body-md text-body-md text-secondary text-center text-[11px]">
                                        Prioridad normal
                                    </span>
                                </div>
                            </label>

                            {{-- High --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority" value="high"
                                       class="sr-only peer"
                                       {{ old('priority') === 'high' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center gap-sm p-md rounded-xl
                                            border-2 border-outline-variant
                                            bg-surface-container-low
                                            peer-checked:border-primary
                                            peer-checked:bg-primary-fixed
                                            hover:bg-surface-container
                                            transition-all cursor-pointer">
                                    <span class="material-symbols-outlined text-[24px] text-secondary">
                                        priority_high
                                    </span>
                                    <span class="font-label-md text-label-md font-semibold text-on-surface">
                                        High
                                    </span>
                                    <span class="font-body-md text-body-md text-secondary text-center text-[11px]">
                                        Urgente
                                    </span>
                                </div>
                            </label>

                        </div>

                        @error('priority')
                            <p class="font-label-md text-label-md text-error mt-xs">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- ── Nota informativa ────────────────────────────────── --}}
                    <div class="flex items-start gap-sm p-md
                                bg-surface-container-low rounded-lg border-l-4 border-primary">
                        <span class="material-symbols-outlined text-primary text-[18px] mt-xs shrink-0">
                            info
                        </span>
                        <p class="font-label-md text-label-md text-secondary">
                            Al crear la solicitud serás redirigido a la pantalla de gestión
                            donde podrás agregar los ítems necesarios antes de enviarla.
                        </p>
                    </div>

                </div>

                {{-- ── Footer de acciones ──────────────────────────────────── --}}
                <div class="px-xl py-lg border-t border-outline-variant
                            bg-surface-container-low flex items-center justify-end gap-md">

                    <a href="{{ route('employee.requests.index') }}"
                       class="px-xl py-sm bg-white border border-outline
                              text-secondary font-bold rounded-lg
                              hover:bg-surface-container transition-all active:scale-95
                              font-label-md text-label-md">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-xl py-sm bg-primary text-on-primary font-bold rounded-lg
                                   shadow-lg hover:shadow-xl hover:-translate-y-0.5
                                   transition-all active:scale-95
                                   font-label-md text-label-md">
                        Create Request
                    </button>

                </div>

            </div>

        </form>
    </div>

@endsection

{{-- ── Scripts ─────────────────────────────────────────────────────────────── --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── Contador de caracteres en justificación ───────────────────────────
    const textarea  = document.getElementById('notes');
    const charCount = document.getElementById('char-count');

    if (textarea && charCount) {
        textarea.addEventListener('input', () => {
            const len = textarea.value.length;
            charCount.textContent = `${len} / 500 characters`;
            charCount.classList.toggle('text-error', len > 500);
        });
    }

});
</script>
@endpush
@props([
    'title',
    'description' => '',
    'action',
    'method'      => 'POST',
    'submitLabel' => 'Guardar',
    'cancelRoute',
])

@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="w-full max-w-[640px] mx-auto flex flex-col gap-lg">

    {{-- ── Encabezado ─────────────────────────────────────────────────────── --}}
    <header class="space-y-sm px-sm">
        <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">{{ $title }}</h1>
        @if ($description)
            <p class="font-body-lg text-body-lg text-secondary">{{ $description }}</p>
        @endif
    </header>

    {{-- ── Tarjeta del formulario ──────────────────────────────────────────── --}}
    <section class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm
                    transition-shadow hover:shadow-md">

        <form action="{{ $action }}"
              method="POST"
              class="flex flex-col">

            @csrf

            @if (!in_array(strtoupper($method), ['GET', 'POST']))
                @method($method)
            @endif

            {{-- Campos del formulario (slot) --}}
            <div class="p-lg md:p-xl space-y-lg">
                {{ $formFields }}
            </div>

            {{-- ── Footer con acciones ─────────────────────────────────────── --}}
            <div class="bg-surface-container px-lg py-md
                        flex flex-col sm:flex-row-reverse gap-md
                        border-t border-outline-variant">

                <button type="submit"
                        class="sm:flex-1 bg-primary text-on-primary
                               font-label-md text-label-md
                               px-xl py-md rounded-lg
                               hover:opacity-90 active:scale-95 transition-all
                               flex items-center justify-center gap-sm shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    {{ $submitLabel }}
                </button>

                <a href="{{ $cancelRoute }}"
                   class="sm:flex-1 bg-transparent text-secondary
                          border border-outline-variant
                          font-label-md text-label-md
                          px-xl py-md rounded-lg
                          hover:bg-surface-container-highest active:scale-95 transition-all
                          flex items-center justify-center gap-sm">
                    Cancelar
                </a>

            </div>

        </form>
    </section>

</div>

@endsection
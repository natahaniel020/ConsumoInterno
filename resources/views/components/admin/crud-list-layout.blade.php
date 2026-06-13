@props([
    'title',
    'description' => '',
    'createRoute'  => null,
    'createLabel'  => 'Crear nuevo',
])

@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="w-full bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm">

    {{-- ── 1. Encabezado + botón crear ────────────────────────────────────── --}}
    <header class="p-lg border-b border-outline-variant
                   flex flex-col md:flex-row md:items-center justify-between gap-md
                   bg-surface-container-lowest">
        <div class="space-y-xs">
            <h1 class="font-headline-md text-headline-md text-on-surface">{{ $title }}</h1>
            @if ($description)
                <p class="font-body-md text-body-md text-secondary">{{ $description }}</p>
            @endif
        </div>

        @if ($createRoute)
            <a href="{{ $createRoute }}"
               class="inline-flex items-center gap-xs
                      bg-primary text-on-primary
                      font-label-md text-label-md
                      px-lg py-md rounded-lg
                      hover:opacity-90 transition-all active:scale-95">
                <span class="material-symbols-outlined text-[20px]">add</span>
                {{ $createLabel }}
            </a>
        @endif
    </header>

    {{-- ── 2. Barra de búsqueda y filtros ─────────────────────────────────── --}}
    @isset($filters)
        <section class="px-lg py-md bg-surface-container-low border-b border-outline-variant">
            {{ $filters }}
        </section>
    @endisset

    {{-- ── 3. Tabla ────────────────────────────────────────────────────────── --}}
    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-[700px]">

            <thead>
                <tr class="bg-surface-container-high border-b border-outline-variant">
                    {{ $columns }}
                </tr>
            </thead>

            <tbody class="divide-y divide-outline-variant">
                {{ $rows }}
            </tbody>

        </table>
    </div>

    {{-- ── 4. Paginación ───────────────────────────────────────────────────── --}}
    @isset($pagination)
        <footer class="p-lg flex items-center justify-between
                       border-t border-outline-variant bg-surface-container-lowest">
            {{ $pagination }}
        </footer>
    @endisset

</div>

@endsection
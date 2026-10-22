@extends('layouts.app')
@section('title', $unit->name . ' — StarDeck')
@section('page_label', 'Rincian Unit')

@section('content')
<div class="mb-6">
    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-cyan-300 transition">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <span>Kembali ke Katalog Unit</span>
    </a>
</div>

@php
    $gradient = $unit->type === 'board_game' ? 'from-orange-500/30 to-rose-900/20' : 'from-violet-500/30 to-indigo-900/20';
    $iconSymbol = $unit->type === 'board_game' ? '◈' : '✦';
@endphp

<div class="max-w-2xl mx-auto rounded-3xl border border-slate-800/80 bg-slate-900/60 p-6 sm:p-8 backdrop-blur-xl shadow-2xl">
    <!-- Visual Header Banner -->
    <div class="flex h-44 items-center justify-center rounded-2xl bg-gradient-to-br {{ $gradient }} text-6xl text-white shadow-inner">
        {{ $iconSymbol }}
    </div>

    <!-- Title and Status -->
    <div class="mt-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white font-heading">{{ $unit->name }}</h1>
            <div class="mt-1 text-xs text-slate-500 font-mono tracking-wider">{{ $unit->unit_code }}</div>
        </div>
        <div>
            @if($unit->status === 'available')
                <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-300">
                    Tersedia
                </span>
            @else
                <span class="inline-flex items-center rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1 text-xs font-semibold text-amber-300">
                    Sedang Dipinjam
                </span>
            @endif
        </div>
    </div>

    <!-- Description -->
    <p class="mt-5 text-sm leading-6 text-slate-400">
        {{ $unit->description ?: 'Tidak ada deskripsi detail untuk unit rekreasi stasiun ini.' }}
    </p>

    <!-- Attribute Grid -->
    <div class="mt-6 grid grid-cols-3 gap-3 text-center">
        <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-3.5">
            <div class="text-[10px] uppercase tracking-wider text-slate-500">Tipe</div>
            <div class="mt-1 text-xs font-semibold text-slate-200">
                {{ $unit->type === 'board_game' ? 'Permainan Papan' : 'Konsol' }}
            </div>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-3.5">
            <div class="text-[10px] uppercase tracking-wider text-slate-500">Kategori</div>
            <div class="mt-1 text-xs font-semibold text-slate-200 truncate">
                {{ $unit->categories->pluck('name')->implode(', ') ?: '-' }}
            </div>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-3.5">
            <div class="text-[10px] uppercase tracking-wider text-slate-500">Kondisi Fisik</div>
            <div class="mt-1 text-xs font-semibold text-slate-200 truncate">
                {{ $unit->condition }}
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="mt-7 pt-6 border-t border-slate-800/80 flex items-center justify-between gap-4">
        @if(auth()->user()->isAdmin())
            <div class="flex gap-2">
                <a href="{{ route('units.edit', $unit->id) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-900 px-4 py-2.5 text-xs font-semibold text-slate-300 hover:border-cyan-300/40 hover:text-cyan-200 transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                    <span>Edit Unit</span>
                </a>
            </div>
            @if($unit->status === 'available')
                <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-cyan-300 px-5 py-2.5 text-xs font-semibold text-slate-950 hover:bg-cyan-200 transition">
                    <span>Catat Peminjaman Unit</span>
                </a>
            @endif
        @else
            <div class="text-xs text-slate-500">
                Hubungi Komandan Stasiun di Modul Komando untuk meminjam unit fisik ini.
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-800 px-4 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition">
                Tutup
            </a>
        @endif
    </div>
</div>
@endsection

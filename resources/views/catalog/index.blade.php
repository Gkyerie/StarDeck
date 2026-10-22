@extends('layouts.app')
@section('title', 'Katalog Unit — StarDeck')
@section('page_label', 'Katalog Rekreasi')

@section('content')
<!-- Page Header -->
<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-2xl font-bold text-white font-heading sm:text-3xl">Katalog unit</h1>
        <p class="mt-1 text-sm text-slate-400">Temukan permainan papan dan konsol yang tersedia di pusat rekreasi orbital.</p>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="mb-6 rounded-2xl border border-slate-800/80 bg-slate-900/60 p-4 backdrop-blur-sm">
    <form method="GET" action="{{ route('catalog.index') }}" class="flex flex-col gap-3 md:flex-row">
        <!-- Search bar -->
        <div class="relative flex-1">
            <svg class="absolute left-4 top-3.5 h-5 w-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari berdasarkan nama unit..."
                class="w-full rounded-xl border border-slate-700 bg-slate-950/60 py-3 pl-12 pr-4 text-sm text-white outline-none placeholder:text-slate-500 transition focus:border-cyan-300/50"
            >
        </div>

        <!-- Category Dropdown Filter -->
        <div class="md:w-60">
            <select
                name="category"
                onchange="this.form.submit()"
                class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-slate-200 outline-none transition focus:border-cyan-300/50"
            >
                <option value="" class="bg-slate-900 text-slate-200">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }} class="bg-slate-900 text-slate-200">
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button
                type="submit"
                class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-5 text-sm font-semibold text-slate-950 transition hover:bg-cyan-200 active:scale-[.98]"
            >
                Filter
            </button>
            @if(request('search') || request('category'))
                <a
                    href="{{ route('catalog.index') }}"
                    class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-900/80 px-3 text-sm text-slate-400 hover:text-white transition"
                    title="Reset Filter"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Units Grid -->
@if($units->isEmpty())
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-12 text-center backdrop-blur-sm">
        <svg class="mx-auto h-10 w-10 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
        <div class="mt-4 font-semibold text-white">Unit tidak ditemukan</div>
        <p class="mt-2 text-sm text-slate-500">Coba ubah kata kunci pencarian atau kategori filter.</p>
        <a href="{{ route('catalog.index') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-900/80 px-4 py-2.5 text-xs font-semibold text-slate-200 hover:border-cyan-300/40 hover:text-cyan-200 transition">
            Atur ulang pencarian
        </a>
    </div>
@else
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($units as $unit)
            @php
                $gradient = $unit->type === 'board_game' ? 'from-orange-500/30 to-rose-900/20' : 'from-violet-500/30 to-indigo-900/20';
                $iconSymbol = $unit->type === 'board_game' ? '◈' : '✦';
            @endphp
            <a
                href="{{ route('catalog.show', $unit->id) }}"
                class="group block overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/60 text-left transition-all hover:-translate-y-1 hover:border-cyan-300/25 hover:shadow-[0_18px_45px_rgba(0,0,0,.28)] active:translate-y-0"
            >
                <!-- Hero Unit Image / Gradient Card -->
                <div class="relative flex h-36 items-center justify-center bg-gradient-to-br {{ $gradient }}">
                    <span class="text-5xl text-white/90 transition-transform duration-300 group-hover:scale-110">
                        {{ $iconSymbol }}
                    </span>
                    <div class="absolute left-3 top-3">
                        <span class="inline-flex items-center rounded-full border border-slate-600/40 bg-slate-900/70 backdrop-blur-md px-2.5 py-1 text-[11px] font-semibold text-slate-300">
                            {{ $unit->type === 'board_game' ? 'Permainan Papan' : 'Konsol' }}
                        </span>
                    </div>
                    <div class="absolute right-3 top-3">
                        @if($unit->status === 'available')
                            <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-950/80 backdrop-blur-md px-2.5 py-1 text-[11px] font-semibold text-emerald-300">
                                Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full border border-amber-400/20 bg-amber-950/80 backdrop-blur-md px-2.5 py-1 text-[11px] font-semibold text-amber-300">
                                Dipinjam
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Unit Details -->
                <div class="p-5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h2 class="font-bold text-white text-base font-heading group-hover:text-cyan-300 transition-colors">{{ $unit->name }}</h2>
                            <div class="mt-0.5 text-xs text-slate-500 font-mono">{{ $unit->unit_code }}</div>
                        </div>
                    </div>

                    <p class="mt-3 text-xs leading-5 text-slate-400 line-clamp-2">
                        {{ $unit->description ?: 'Unit hiburan untuk relaksasi kru stasiun luar angkasa.' }}
                    </p>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @foreach($unit->categories as $c)
                            <span class="rounded-lg border border-slate-800 bg-slate-950/50 px-2 py-0.5 text-[10px] text-slate-400">
                                {{ $c->name }}
                            </span>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-500">
                        <span>Kondisi: <strong class="text-slate-300 font-medium">{{ $unit->condition }}</strong></span>
                        <span class="text-cyan-400 font-medium group-hover:underline flex items-center gap-1">
                            Lihat Rincian
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8 flex justify-center">
        {{ $units->links() }}
    </div>
@endif
@endsection

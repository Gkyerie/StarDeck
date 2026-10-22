@extends('layouts.app')
@section('title', 'Beranda Astronot — StarDeck')
@section('page_label', 'Portal Astronot')

@section('content')
<!-- Hero Welcome Banner with Orbital Gradients -->
<div class="relative mb-7 overflow-hidden rounded-3xl border border-cyan-300/15 bg-gradient-to-r from-cyan-950/60 to-violet-950/50 p-7 sm:p-9 shadow-xl backdrop-blur-md">
    <div class="absolute -right-14 -top-16 h-52 w-52 rounded-full border border-cyan-300/10 pointer-events-none"></div>
    <div class="absolute -right-3 -top-5 h-32 w-32 rounded-full border border-violet-300/10 pointer-events-none"></div>

    <div class="relative max-w-2xl">
        <span class="inline-flex items-center rounded-full border border-violet-400/20 bg-violet-400/10 px-2.5 py-1 text-[11px] font-semibold text-violet-300">
            Kru Aktif • Modul {{ auth()->user()->memberProfile->station_module ?? 'Stasiun' }}
        </span>
        <h1 class="mt-4 text-3xl font-bold text-white font-heading sm:text-4xl">
            Selamat kembali, {{ auth()->user()->name }}.
        </h1>
        <p class="mt-2 text-sm leading-6 text-slate-300">
            Koleksi hiburan orbital siap menemani waktu santai setelah rotasi tugas stasiun hari ini.
        </p>

        <!-- Search shortcut -->
        <form action="{{ route('catalog.index') }}" method="GET" class="mt-6 flex max-w-xl gap-2">
            <input
                type="text"
                name="search"
                placeholder="Cari board game atau konsol di stasiun..."
                class="min-w-0 flex-1 rounded-xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none placeholder:text-slate-500 transition focus:border-cyan-300/60 focus:ring-4 focus:ring-cyan-300/10"
            >
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-300 px-5 text-sm font-semibold text-slate-950 transition hover:bg-cyan-200 active:scale-[.98]"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <span>Cari</span>
            </button>
        </form>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-[1.2fr_.8fr]">
    <!-- Active Loans Slot Section -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 backdrop-blur-sm">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-bold text-white text-base font-heading">Pinjaman Aktif Anda</h2>
                <div class="mt-1 text-xs text-slate-500">
                    {{ $activeLoansCount }} dari 2 slot kuota digunakan
                </div>
            </div>
            <div class="rounded-xl border border-cyan-300/20 bg-cyan-300/10 px-3 py-1.5 text-xs font-bold text-cyan-200">
                Sisa kuota: {{ $remainingQuota }} unit
            </div>
        </div>

        @if($activeLoans->isEmpty())
            <div class="rounded-xl border border-dashed border-slate-800 p-8 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-300/10 text-cyan-300 mb-3">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m4 7 8-4 8 4-8 4-8-4Z" /><path d="m4 7 8 4 8-4v10l-8 4-8-4V7Z" /></svg>
                </div>
                <h3 class="text-sm font-semibold text-white">Tidak Ada Pinjaman Aktif</h3>
                <p class="mt-1 text-xs text-slate-500">Anda sedang tidak memegang unit hiburan stasiun.</p>
                <a href="{{ route('catalog.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-slate-800 px-4 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition">
                    Jelajahi Katalog
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($activeLoans as $loan)
                    @php
                        $isOverdue = $loan->due_date < now()->toDateString();
                        $daysOverdue = $isOverdue ? \Carbon\Carbon::parse($loan->due_date)->diffInDays(now()->toDateString()) : 0;
                    @endphp
                    <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4 transition hover:border-slate-700">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $loan->unit->type === 'board_game' ? 'bg-orange-400/10 text-orange-300' : 'bg-violet-400/10 text-violet-300' }} text-xl font-bold">
                                {{ $loan->unit->type === 'board_game' ? '◈' : '✦' }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-white truncate">{{ $loan->unit->name }}</div>
                                <div class="mt-0.5 text-xs text-slate-500 font-mono">
                                    {{ $loan->unit->unit_code }} • {{ $loan->unit->type === 'board_game' ? 'Board Game' : 'Console' }}
                                </div>
                            </div>
                            <div class="sm:text-right">
                                @if($isOverdue)
                                    <span class="inline-flex items-center rounded-full border border-rose-400/20 bg-rose-400/10 px-2.5 py-1 text-[11px] font-semibold text-rose-300">
                                        Terlambat {{ $daysOverdue }} hari
                                    </span>
                                    <div class="mt-1 text-[11px] font-semibold text-rose-400">
                                        Est. Denda: Rp{{ number_format($daysOverdue * 5000, 0, ',', '.') }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-300">
                                        Jatuh tempo {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recent Loan History -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 backdrop-blur-sm">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="font-bold text-white text-base font-heading">Riwayat Anda</h2>
            <a href="{{ route('loans.my') }}" class="text-xs font-semibold text-cyan-400 hover:underline">
                Lihat Semua
            </a>
        </div>

        @if($recentLoans->isEmpty())
            <div class="py-6 text-center text-xs text-slate-500">
                Belum ada riwayat pengembalian unit.
            </div>
        @else
            <div class="divide-y divide-slate-800/60">
                @foreach($recentLoans as $loan)
                    <div class="py-3 flex items-center justify-between gap-3 text-xs">
                        <div class="min-w-0">
                            <div class="font-semibold text-slate-200 truncate">{{ $loan->unit->name }}</div>
                            <div class="text-[10px] text-slate-500 font-mono mt-0.5">
                                {{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d M') }} — {{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d M Y') : 'Aktif' }}
                            </div>
                        </div>
                        <div>
                            @if($loan->status === 'returned')
                                <span class="text-emerald-400 font-medium">Selesai</span>
                            @else
                                <span class="text-amber-400 font-medium">Dipinjam</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

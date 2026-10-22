@extends('layouts.app')
@section('title', 'Dasbor Komandan — StarDeck')
@section('page_label', 'Pusat Komando')

@section('content')
<!-- Page Header -->
<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <span class="text-xs font-semibold uppercase tracking-[.2em] text-cyan-400">Selamat bertugas, Komandan {{ auth()->user()->name }}</span>
        <h1 class="mt-1 text-2xl font-bold text-white font-heading sm:text-3xl">Ringkasan stasiun</h1>
        <p class="mt-1 text-sm text-slate-400">Pantau inventaris rekreasi dan aktivitas peminjaman seluruh kru dari satu pusat kendali.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('loans.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-4 text-sm font-semibold text-slate-950 shadow-[0_8px_24px_rgba(34,211,238,.18)] transition-all hover:bg-cyan-200 active:scale-[.98]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            <span>Peminjaman Baru</span>
        </a>
    </div>
</div>

<!-- Metrics Cards Grid -->
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <!-- Total Units -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-5 backdrop-blur-sm transition hover:-translate-y-0.5 hover:border-slate-700">
        <div class="mb-5 flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-300/10 text-cyan-300">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m4 7 8-4 8 4-8 4-8-4Z" /><path d="m4 7 8 4 8-4v10l-8 4-8-4V7Z" /><path d="M12 11v10" /></svg>
            </div>
            <span class="text-[11px] text-slate-500">Unit Terdaftar</span>
        </div>
        <div class="text-2xl font-bold text-white font-heading">{{ $totalUnits }}</div>
        <div class="mt-1 text-xs text-slate-400">Total unit fisik stasiun</div>
    </div>

    <!-- Borrowed Units -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-5 backdrop-blur-sm transition hover:-translate-y-0.5 hover:border-slate-700">
        <div class="mb-5 flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-400/10 text-amber-300">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></svg>
            </div>
            <span class="text-[11px] text-slate-500">Sedang Dipinjam</span>
        </div>
        <div class="text-2xl font-bold text-white font-heading">{{ $borrowedUnits }}</div>
        <div class="mt-1 text-xs text-slate-400">{{ $totalUnits > 0 ? round(($borrowedUnits / $totalUnits) * 100) : 0 }}% dari inventaris</div>
    </div>

    <!-- Active / Overdue Loans -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-5 backdrop-blur-sm transition hover:-translate-y-0.5 hover:border-slate-700">
        <div class="mb-5 flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $overdueLoans > 0 ? 'bg-rose-400/10 text-rose-300' : 'bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.7 2.4 18a2 2 0 0 0 1.7 3h15.8a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
            </div>
            <span class="text-[11px] {{ $overdueLoans > 0 ? 'text-rose-400 font-semibold' : 'text-slate-500' }}">
                {{ $overdueLoans }} Terlambat
            </span>
        </div>
        <div class="text-2xl font-bold text-white font-heading">{{ $activeLoans }}</div>
        <div class="mt-1 text-xs text-slate-400">Total pinjaman aktif kru</div>
    </div>

    <!-- Available Units -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-5 backdrop-blur-sm transition hover:-translate-y-0.5 hover:border-slate-700">
        <div class="mb-5 flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-300">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg>
            </div>
            <span class="text-[11px] text-emerald-400 font-semibold">Siap Dipinjam</span>
        </div>
        <div class="text-2xl font-bold text-white font-heading">{{ $availableUnits }}</div>
        <div class="mt-1 text-xs text-slate-400">Unit siap di rak rekreasi</div>
    </div>
</div>

<!-- Main Section: Recent Loans & Quick Actions -->
<div class="mt-7 grid gap-6 xl:grid-cols-[1.5fr_1fr]">
    <!-- Recent Activity / Transactions Table -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 backdrop-blur-sm">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-white text-base font-heading">Aktivitas Pinjaman Terkini</h2>
                <p class="mt-1 text-xs text-slate-500">Daftar transaksi peminjaman terbaru di stasiun</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-300">
                Langsung
            </span>
        </div>

        @if($recentLoans->isEmpty())
            <div class="py-8 text-center text-slate-500 text-sm">
                Belum ada data peminjaman yang tercatat.
            </div>
        @else
            <div class="divide-y divide-slate-800/60">
                @foreach($recentLoans as $loan)
                    @php
                        $isOverdue = $loan->status === 'borrowed' && $loan->due_date < now()->toDateString();
                    @endphp
                    <div class="flex items-center justify-between gap-4 py-3.5 transition hover:bg-white/[.02] rounded-xl px-2">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $isOverdue ? 'bg-rose-400/10 text-rose-300' : 'bg-cyan-300/10 text-cyan-300' }} text-xs font-bold font-heading">
                                {{ strtoupper(substr($loan->member->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold text-white">
                                    {{ $loan->member->name }} <span class="text-slate-500 font-normal">meminjam</span> {{ $loan->unit->name }}
                                </div>
                                <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                    {{ $loan->unit->unit_code }} • Tempo: {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                                </div>
                            </div>
                        </div>

                        <div>
                            @if($loan->status === 'borrowed')
                                @if($isOverdue)
                                    <span class="inline-flex items-center rounded-full border border-rose-400/20 bg-rose-400/10 px-2.5 py-1 text-[11px] font-semibold text-rose-300">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-amber-400/20 bg-amber-400/10 px-2.5 py-1 text-[11px] font-semibold text-amber-300">
                                        Dipinjam
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-300">
                                    Kembali
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Quick Access Hub -->
    <div>
        <h2 class="mb-4 font-bold text-white text-base font-heading">Akses Cepat Modul</h2>
        <div class="grid gap-3">
            <a href="{{ route('units.index') }}" class="group flex items-center gap-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-4 transition hover:border-cyan-300/30 hover:bg-slate-900 active:scale-[.99]">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-300/10 text-cyan-300 transition group-hover:bg-cyan-300 group-hover:text-slate-950">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m4 7 8-4 8 4-8 4-8-4Z" /><path d="m4 7 8 4 8-4v10l-8 4-8-4V7Z" /><path d="M12 11v10" /></svg>
                </div>
                <div class="flex-1">
                    <div class="text-sm font-semibold text-white">Kelola Inventaris</div>
                    <div class="mt-1 text-xs text-slate-500">Tambah atau perbarui unit permainan & konsol</div>
                </div>
                <svg class="h-4 w-4 text-slate-600 transition group-hover:translate-x-1 group-hover:text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>

            <a href="{{ route('categories.index') }}" class="group flex items-center gap-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-4 transition hover:border-cyan-300/30 hover:bg-slate-900 active:scale-[.99]">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-300/10 text-cyan-300 transition group-hover:bg-cyan-300 group-hover:text-slate-950">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13 13 20l-9-9V4h7l9 9Z M8.5 8.5h.01" /></svg>
                </div>
                <div class="flex-1">
                    <div class="text-sm font-semibold text-white">Atur Kategori</div>
                    <div class="mt-1 text-xs text-slate-500">Kelompokkan koleksi board game & console</div>
                </div>
                <svg class="h-4 w-4 text-slate-600 transition group-hover:translate-x-1 group-hover:text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>

            <a href="{{ route('members.index') }}" class="group flex items-center gap-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-4 transition hover:border-cyan-300/30 hover:bg-slate-900 active:scale-[.99]">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-300/10 text-cyan-300 transition group-hover:bg-cyan-300 group-hover:text-slate-950">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                </div>
                <div class="flex-1">
                    <div class="text-sm font-semibold text-white">Data Astronot Kru</div>
                    <div class="mt-1 text-xs text-slate-500">Lihat kuota pinjaman & profil seluruh kru</div>
                </div>
                <svg class="h-4 w-4 text-slate-600 transition group-hover:translate-x-1 group-hover:text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>

            <a href="{{ route('loans.active') }}" class="group flex items-center gap-4 rounded-2xl border border-slate-800 bg-slate-900/60 p-4 transition hover:border-cyan-300/30 hover:bg-slate-900 active:scale-[.99]">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-300/10 text-cyan-300 transition group-hover:bg-cyan-300 group-hover:text-slate-950">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></svg>
                </div>
                <div class="flex-1">
                    <div class="text-sm font-semibold text-white">Proses Sirkulasi</div>
                    <div class="mt-1 text-xs text-slate-500">Catat peminjaman dan konfirmasi pengembalian</div>
                </div>
                <svg class="h-4 w-4 text-slate-600 transition group-hover:translate-x-1 group-hover:text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
    </div>
</div>
@endsection

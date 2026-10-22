@extends('layouts.app')
@section('title', 'Proses Pengembalian & Denda — StarDeck')
@section('page_label', 'Pengembalian Unit')

@section('content')
<div class="mb-6">
    <a href="{{ route('loans.active') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-cyan-300 transition">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <span>Kembali ke Pinjaman Aktif</span>
    </a>
</div>

@php
    $today = now()->toDateString();
    $isOverdue = $today > $loan->due_date;
    $lateDays = $isOverdue ? \Carbon\Carbon::parse($loan->due_date)->diffInDays($today) : 0;
    $fineAmount = $lateDays * 5000;
@endphp

<div class="max-w-xl mx-auto rounded-3xl border border-slate-800/80 bg-slate-900/60 p-6 sm:p-8 backdrop-blur-xl shadow-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white font-heading">Proses pengembalian & denda</h1>
        <p class="mt-1 text-xs text-slate-400">Verifikasi kondisi dan keterlambatan unit sebelum menyimpan status final.</p>
    </div>

    <!-- Unit & Member Capsule -->
    <div class="flex items-center gap-4 rounded-xl border border-slate-800 bg-slate-950/40 p-4 mb-5">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-300/10 text-cyan-300">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m4 7 8-4 8 4-8 4-8-4Z" /><path d="m4 7 8 4 8-4v10l-8 4-8-4V7Z" /><path d="M12 11v10" /></svg>
        </div>
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-white text-base truncate">{{ $loan->unit->name }}</div>
            <div class="mt-0.5 text-xs text-slate-500 font-mono">
                {{ $loan->unit->unit_code }} • Peminjam: <span class="text-slate-300">{{ $loan->member->name }}</span>
            </div>
        </div>
        <span class="inline-flex items-center rounded-full border border-amber-400/20 bg-amber-400/10 px-2.5 py-1 text-[11px] font-semibold text-amber-300">
            Dipinjam
        </span>
    </div>

    <!-- Date Details Grid -->
    <div class="grid gap-3 sm:grid-cols-2 mb-5">
        <div class="rounded-xl border border-slate-800 bg-slate-950/40 p-4">
            <div class="text-[10px] uppercase tracking-wider text-slate-500">Tanggal Pinjam</div>
            <div class="mt-1 text-sm font-semibold text-white font-mono">
                {{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d M Y') }}
            </div>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-950/40 p-4">
            <div class="text-[10px] uppercase tracking-wider text-slate-500">Jatuh Tempo</div>
            <div class="mt-1 text-sm font-semibold text-white font-mono">
                {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
            </div>
        </div>
        <div class="rounded-xl border border-cyan-300/20 bg-cyan-300/5 p-4">
            <div class="text-[10px] uppercase tracking-wider text-cyan-400 font-semibold">Tanggal Kembali (Hari Ini)</div>
            <div class="mt-1 text-sm font-semibold text-cyan-200 font-mono">
                {{ now()->format('d M Y') }}
            </div>
        </div>
        <div class="rounded-xl border border-slate-800 bg-slate-950/40 p-4">
            <div class="text-[10px] uppercase tracking-wider text-slate-500">Hari Terlambat</div>
            <div class="mt-1 text-sm font-semibold {{ $lateDays > 0 ? 'text-rose-400' : 'text-emerald-400' }} font-mono">
                {{ $lateDays }} hari
            </div>
            <div class="mt-0.5 text-[10px] text-slate-500">Maks. (0, kembali − tempo)</div>
        </div>
    </div>

    <!-- Fine Card -->
    <div class="rounded-2xl border p-5 mb-6 {{ $fineAmount > 0 ? 'border-rose-400/25 bg-rose-400/10' : 'border-emerald-400/20 bg-emerald-400/10' }}">
        <div class="text-xs uppercase tracking-wider text-slate-400">Total Denda Keterlambatan</div>
        <div class="mt-2 text-3xl font-bold text-white font-heading">
            Rp {{ number_format($fineAmount, 0, ',', '.') }}
        </div>
        <div class="mt-1 text-xs text-slate-400">
            Tarif denda: Rp 5.000 × {{ $lateDays }} hari keterlambatan kalender
        </div>
    </div>

    <form action="{{ route('loans.process-return', $loan->id) }}" method="POST">
        @csrf
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
            <a href="{{ route('loans.active') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 px-4 text-xs font-semibold text-slate-300 hover:text-white transition">
                Batal
            </a>
            <button
                type="submit"
                class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-5 text-xs font-semibold text-slate-950 transition hover:bg-cyan-200 active:scale-[.98]"
            >
                Konfirmasi Pengembalian
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Pinjaman Saya — StarDeck')
@section('page_label', 'Pinjaman Saya')

@section('content')
<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-2xl font-bold text-white font-heading sm:text-3xl">Pinjaman saya</h1>
        <p class="mt-1 text-sm text-slate-400">Daftar unit aktif, sisa waktu, dan tanggal pengembalian milikmu.</p>
    </div>
</div>

<div class="mb-5 rounded-2xl border border-cyan-300/20 bg-cyan-300/10 p-4 text-sm text-cyan-100 flex items-center justify-between">
    <div>
        <strong>Maksimal 2 unit pinjaman aktif.</strong> Masa pinjaman setiap unit adalah 5 hari kalender.
    </div>
    <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-cyan-300 hover:underline">
        Lihat Katalog Unit →
    </a>
</div>

<div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
    @if($loans->isEmpty())
        <div class="p-12 text-center text-slate-500 text-sm">
            <svg class="mx-auto h-10 w-10 text-slate-600 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            <div class="font-semibold text-white">Belum ada riwayat peminjaman</div>
            <p class="mt-1 text-xs text-slate-500">Anda belum pernah meminjam unit board game atau konsol.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead class="bg-slate-950/50 text-[10px] uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Unit</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Tanggal Pinjam</th>
                        <th class="px-6 py-4">Jatuh Tempo</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Pengembalian & Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($loans as $loan)
                        @php
                            $isOverdue = $loan->status === 'borrowed' && $loan->due_date < now()->toDateString();
                            $daysOverdue = $isOverdue ? \Carbon\Carbon::parse($loan->due_date)->diffInDays(now()->toDateString()) : 0;
                        @endphp
                        <tr class="{{ $isOverdue ? 'bg-rose-400/[.035]' : 'transition hover:bg-white/[.02]' }}">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-white text-sm">{{ $loan->unit->name }}</div>
                                <div class="text-xs text-cyan-300 font-mono tracking-wider">{{ $loan->unit->unit_code }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ $loan->unit->type === 'board_game' ? 'Board Game' : 'Konsol' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 font-mono">
                                {{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono {{ $isOverdue ? 'text-rose-400 font-semibold' : 'text-slate-300' }}">
                                {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($loan->status === 'borrowed')
                                    @if($isOverdue)
                                        <span class="inline-flex items-center rounded-full border border-rose-400/20 bg-rose-400/10 px-2.5 py-1 text-[11px] font-semibold text-rose-300">
                                            Terlambat {{ $daysOverdue }} hari
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full border border-amber-400/20 bg-amber-400/10 px-2.5 py-1 text-[11px] font-semibold text-amber-300">
                                            Sedang Dipinjam
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-300">
                                        Dikembalikan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($loan->status === 'returned')
                                    <div class="text-xs text-slate-400">
                                        Kembali: {{ \Carbon\Carbon::parse($loan->returned_at)->format('d M Y') }}
                                    </div>
                                    @if($loan->fine_amount > 0)
                                        <div class="text-xs text-rose-400 font-semibold">
                                            Denda: Rp{{ number_format($loan->fine_amount, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="text-[11px] text-emerald-400">Bebas Denda</div>
                                    @endif
                                @else
                                    @if($isOverdue)
                                        <div class="text-xs text-rose-400 font-semibold">
                                            Est. Denda: Rp{{ number_format($daysOverdue * 5000, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="text-xs text-slate-500">Dalam masa pinjam</div>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-800">
            {{ $loans->links() }}
        </div>
    @endif
</div>
@endsection

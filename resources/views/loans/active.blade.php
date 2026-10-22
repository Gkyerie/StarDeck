@extends('layouts.app')
@section('title', 'Pinjaman Aktif — StarDeck')
@section('page_label', 'Pinjaman Aktif')

@section('content')
<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-2xl font-bold text-white font-heading sm:text-3xl">Pinjaman aktif stasiun</h1>
        <p class="mt-1 text-sm text-slate-400">Pantau jatuh tempo, status keterlambatan, dan proses pengembalian unit.</p>
    </div>
    <div>
        <a href="{{ route('loans.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-4 text-sm font-semibold text-slate-950 shadow-[0_8px_24px_rgba(34,211,238,.18)] transition-all hover:bg-cyan-200 active:scale-[.98]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            <span>Peminjaman Baru</span>
        </a>
    </div>
</div>

<div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
    @if($loans->isEmpty())
        <div class="p-12 text-center text-slate-500 text-sm">
            <svg class="mx-auto h-10 w-10 text-emerald-400/80 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg>
            <div class="font-semibold text-white">Tidak ada pinjaman aktif</div>
            <p class="mt-1 text-xs text-slate-500">Semua unit rekreasi stasiun berada di rak inventaris.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left">
                <thead class="bg-slate-950/50 text-[10px] uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Unit Hiburan</th>
                        <th class="px-6 py-4">Tanggal Pinjam</th>
                        <th class="px-6 py-4">Jatuh Tempo</th>
                        <th class="px-6 py-4">Status Keterlambatan</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($loans as $loan)
                        @php
                            $isOverdue = $loan->due_date < now()->toDateString();
                            $daysOverdue = $isOverdue ? \Carbon\Carbon::parse($loan->due_date)->diffInDays(now()->toDateString()) : 0;
                        @endphp
                        <tr class="transition hover:bg-white/[.02]">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-white text-sm">{{ $loan->member->name }}</div>
                                <div class="text-xs text-slate-500">{{ $loan->member->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-white text-sm">{{ $loan->unit->name }}</div>
                                <div class="text-xs text-cyan-300 font-mono tracking-wider">{{ $loan->unit->unit_code }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 font-mono">
                                {{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono {{ $isOverdue ? 'text-rose-400 font-semibold' : 'text-slate-300' }}">
                                {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($isOverdue)
                                    <span class="inline-flex items-center rounded-full border border-rose-400/20 bg-rose-400/10 px-2.5 py-1 text-[11px] font-semibold text-rose-300">
                                        Terlambat {{ $daysOverdue }} hari
                                    </span>
                                    <div class="mt-1 text-[11px] text-rose-400 font-semibold">
                                        Est. Denda: Rp{{ number_format($daysOverdue * 5000, 0, ',', '.') }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-300">
                                        Tepat Waktu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('loans.return', $loan->id) }}" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl bg-cyan-300/10 border border-cyan-300/30 px-3.5 text-xs font-semibold text-cyan-200 hover:bg-cyan-300 hover:text-slate-950 transition">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                    <span>Kembalikan Unit</span>
                                </a>
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

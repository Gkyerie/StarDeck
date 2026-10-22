@extends('layouts.app')
@section('title', 'Riwayat Pinjaman: ' . $member->name . ' — StarDeck')
@section('page_label', 'Riwayat & Laporan')

@section('content')
<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <a href="{{ route('members.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-cyan-300 transition mb-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            <span>Kembali ke Data Astronot</span>
        </a>
        <h1 class="text-2xl font-bold text-white font-heading sm:text-3xl">Riwayat Pinjaman: {{ $member->name }}</h1>
        <p class="mt-1 text-sm text-slate-400 font-mono">{{ $member->email }} • {{ $member->memberProfile->rank_or_position ?? 'Astronot' }}</p>
    </div>
    <div>
        <a href="{{ route('loans.print-history', $member->id) }}" target="_blank" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl border border-cyan-300/40 bg-cyan-300/10 px-4 text-xs font-semibold text-cyan-200 hover:bg-cyan-300 hover:text-slate-950 transition">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M8 13h8M8 17h6"/></svg>
            <span>Cetak Dokumen A4</span>
        </a>
    </div>
</div>

<div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
    @if($loans->isEmpty())
        <div class="p-12 text-center text-slate-500 text-sm">
            Astronot ini belum memiliki catatan riwayat peminjaman.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] text-left">
                <thead class="bg-slate-950/50 text-[10px] uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-4">Unit Hiburan</th>
                        <th class="px-5 py-4">Tanggal Pinjam</th>
                        <th class="px-5 py-4">Jatuh Tempo</th>
                        <th class="px-5 py-4">Tanggal Kembali</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Terlambat</th>
                        <th class="px-5 py-4">Denda</th>
                        <th class="px-5 py-4">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($loans as $loan)
                        @php
                            $isOverdue = $loan->status === 'borrowed' && $loan->due_date < now()->toDateString();
                            $daysOverdue = $isOverdue ? \Carbon\Carbon::parse($loan->due_date)->diffInDays(now()->toDateString()) : 0;
                        @endphp
                        <tr class="transition hover:bg-white/[.02]">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-white text-sm">{{ $loan->unit->name }}</div>
                                <div class="text-xs text-cyan-300 font-mono tracking-wider">{{ $loan->unit->unit_code }}</div>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-400 font-mono">
                                {{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-400 font-mono">
                                {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-xs font-mono text-slate-300">
                                {{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d M Y') : '—' }}
                            </td>
                            <td class="px-5 py-4">
                                @if($loan->status === 'borrowed')
                                    <span class="inline-flex items-center rounded-full border border-amber-400/20 bg-amber-400/10 px-2.5 py-1 text-[11px] font-semibold text-amber-300">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-300">
                                        Dikembalikan
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-400 font-mono">
                                @if($loan->status === 'returned')
                                    {{ $loan->late_days > 0 ? $loan->late_days . ' hari' : '0 hari' }}
                                @else
                                    {{ $isOverdue ? $daysOverdue . ' hari' : '—' }}
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs font-semibold {{ $loan->fine_amount > 0 ? 'text-rose-400' : 'text-slate-300' }}">
                                Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-500">
                                {{ $loan->processedBy->name ?? 'Admin' }}
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

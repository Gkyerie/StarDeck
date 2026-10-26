@extends('layouts.app')
@section('title', 'Riwayat Semua Pinjaman — StarDeck')
@section('page_label', 'Riwayat Pinjaman')

@section('content')
<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-2xl font-bold text-white font-heading sm:text-3xl">Riwayat semua pinjaman</h1>
        <p class="mt-1 text-sm text-slate-400">Lihat transaksi seluruh anggota, termasuk pinjaman aktif dan yang sudah dikembalikan.</p>
    </div>
    <a href="{{ route('loans.print-all-history', request()->query()) }}" target="_blank" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl border border-cyan-300/40 bg-cyan-300/10 px-4 text-xs font-semibold text-cyan-200 transition hover:bg-cyan-300 hover:text-slate-950">
        <span>Cetak Hasil</span>
    </a>
</div>

<form method="GET" action="{{ route('loans.history-all') }}" class="mb-5 grid gap-3 rounded-2xl border border-slate-800/80 bg-slate-900/60 p-4 sm:grid-cols-2 lg:grid-cols-5">
    <div>
        <label for="borrowed_at_display" class="mb-1 block text-xs font-medium text-slate-400">Tanggal pinjam</label>
        <div class="relative" data-date-picker>
            <input type="hidden" name="borrowed_at" value="{{ $filters['borrowed_at'] ?? '' }}" data-date-value>
            <input id="borrowed_at_display" type="text" value="{{ !empty($filters['borrowed_at']) ? \Carbon\Carbon::parse($filters['borrowed_at'])->format('d/m/Y') : '' }}" placeholder="Pilih tanggal" readonly data-date-display class="w-full cursor-pointer rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 pr-10 text-sm text-white placeholder:text-slate-500">
            <button type="button" aria-label="Pilih tanggal pinjam" aria-expanded="false" data-date-toggle class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-cyan-300">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
            </button>
            <div data-date-calendar class="absolute left-0 top-full z-20 mt-2 hidden w-72 rounded-xl border border-slate-700 bg-slate-900 p-3 shadow-2xl"></div>
        </div>
    </div>
    <div>
        <label for="returned_at_display" class="mb-1 block text-xs font-medium text-slate-400">Tanggal kembali</label>
        <div class="relative" data-date-picker>
            <input type="hidden" name="returned_at" value="{{ $filters['returned_at'] ?? '' }}" data-date-value>
            <input id="returned_at_display" type="text" value="{{ !empty($filters['returned_at']) ? \Carbon\Carbon::parse($filters['returned_at'])->format('d/m/Y') : '' }}" placeholder="Pilih tanggal" readonly data-date-display class="w-full cursor-pointer rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 pr-10 text-sm text-white placeholder:text-slate-500">
            <button type="button" aria-label="Pilih tanggal kembali" aria-expanded="false" data-date-toggle class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-cyan-300">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
            </button>
            <div data-date-calendar class="absolute left-0 top-full z-20 mt-2 hidden w-72 rounded-xl border border-slate-700 bg-slate-900 p-3 shadow-2xl"></div>
        </div>
    </div>
    <div>
        <label for="status" class="mb-1 block text-xs font-medium text-slate-400">Status pinjaman</label>
        <select id="status" name="status" class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm text-white">
            <option value="">Semua status</option>
            <option value="borrowed" @selected(($filters['status'] ?? '') === 'borrowed')>Aktif</option>
            <option value="returned" @selected(($filters['status'] ?? '') === 'returned')>Dikembalikan</option>
        </select>
    </div>
    <div class="flex items-end gap-2">
        <button type="submit" class="min-h-10 rounded-xl bg-cyan-300 px-4 text-sm font-semibold text-slate-950 hover:bg-cyan-200">Terapkan</button>
        <a href="{{ route('loans.history-all') }}" class="inline-flex min-h-10 items-center rounded-xl border border-slate-700 px-4 text-sm font-semibold text-slate-300 hover:text-white">Reset</a>
    </div>
</form>

@if($errors->any())
    <div class="mb-4 rounded-xl border border-rose-400/30 bg-rose-400/10 p-3 text-sm text-rose-200">
        Periksa kembali filter tanggal yang dimasukkan.
    </div>
@endif

<div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
    @if($loans->isEmpty())
        <div class="p-12 text-center text-slate-500 text-sm">Tidak ada transaksi yang cocok dengan filter.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left">
                <thead class="bg-slate-950/50 text-[10px] uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-4">Peminjam</th>
                        <th class="px-5 py-4">Unit</th>
                        <th class="px-5 py-4">Tanggal Pinjam</th>
                        <th class="px-5 py-4">Jatuh Tempo</th>
                        <th class="px-5 py-4">Tanggal Kembali</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Terlambat</th>
                        <th class="px-5 py-4">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($loans as $loan)
                        <tr class="transition hover:bg-white/[.02]">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-white text-sm">{{ $loan->member->name }}</div>
                                <div class="text-xs text-slate-500">{{ $loan->member->email }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-white text-sm">{{ $loan->unit->name }}</div>
                                <div class="text-xs text-cyan-300 font-mono">{{ $loan->unit->unit_code }}</div>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-xs text-slate-300 font-mono">{{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d M Y') : '—' }}</td>
                            <td class="px-5 py-4">
                                @if($loan->status === 'returned')
                                    <span class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-300">Dikembalikan</span>
                                @else
                                    <span class="rounded-full border border-amber-400/20 bg-amber-400/10 px-2.5 py-1 text-[11px] font-semibold text-amber-300">Aktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-400">
                                {{ $loan->status === 'returned' ? $loan->late_days . ' hari' : ($loan->isOverdue() ? $loan->estimatedLateDays() . ' hari' : '—') }}
                            </td>
                            <td class="px-5 py-4 text-xs font-semibold text-slate-200">Rp {{ number_format($loan->status === 'returned' ? $loan->fine_amount : $loan->estimatedFine(), 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-800 p-4">{{ $loans->links() }}</div>
    @endif
</div>
@endsection

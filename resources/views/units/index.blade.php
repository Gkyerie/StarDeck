@extends('layouts.app')
@section('title', 'Unit & Inventaris — StarDeck')
@section('page_label', 'Inventaris Unit')

@section('content')
<!-- Page Title and Add Action -->
<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-2xl font-bold text-white font-heading sm:text-3xl">Kelola inventaris unit</h1>
        <p class="mt-1 text-sm text-slate-400">Atur ketersediaan dan status fisik seluruh board game dan konsol stasiun.</p>
    </div>
    <div>
        <a href="{{ route('units.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-4 text-sm font-semibold text-slate-950 shadow-[0_8px_24px_rgba(34,211,238,.18)] transition-all hover:bg-cyan-200 active:scale-[.98]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tambah Unit</span>
        </a>
    </div>
</div>

<!-- Card Container & Table -->
<div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
    <!-- Filter and Search Header -->
    <div class="border-b border-slate-800 p-4">
        <form method="GET" action="{{ route('units.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="relative w-full max-w-sm">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari unit atau kode..."
                    class="w-full rounded-xl border border-slate-700 bg-slate-950/60 py-2.5 pl-10 pr-4 text-sm text-white outline-none placeholder:text-slate-500 transition focus:border-cyan-300/50"
                >
            </div>

            <div class="flex flex-wrap gap-2">
                <select name="category" onchange="this.form.submit()" class="rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 text-xs text-slate-200 outline-none">
                    <option value="" class="bg-slate-900">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }} class="bg-slate-900">
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 text-xs text-slate-200 outline-none">
                    <option value="" class="bg-slate-900">Semua Status</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }} class="bg-slate-900">Tersedia</option>
                    <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }} class="bg-slate-900">Dipinjam</option>
                </select>

                @if(request('search') || request('category') || request('status'))
                    <a href="{{ route('units.index') }}" class="rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs text-slate-400 hover:text-white transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    @if($units->isEmpty())
        <div class="p-12 text-center text-slate-500 text-sm">
            Tidak ada unit inventaris yang cocok dengan kriteria pencarian.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-left">
                <thead class="bg-slate-950/50 text-[10px] uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-4">Unit</th>
                        <th class="px-5 py-4">Jenis</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-5 py-4">Kondisi</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($units as $unit)
                        <tr class="transition hover:bg-white/[.02]">
                            <td class="px-5 py-4">
                                <div class="text-sm font-semibold text-white">{{ $unit->name }}</div>
                                <div class="mt-0.5 text-xs text-cyan-300 font-mono tracking-wider">{{ $unit->unit_code }}</div>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-400">
                                {{ $unit->type === 'board_game' ? 'Permainan Papan' : 'Konsol' }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($unit->categories as $c)
                                        <span class="inline-flex items-center rounded-full border border-violet-400/20 bg-violet-400/10 px-2 py-0.5 text-[11px] font-semibold text-violet-300">
                                            {{ $c->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-400">
                                {{ $unit->condition }}
                            </td>
                            <td class="px-5 py-4">
                                @if($unit->status === 'available')
                                    <span class="inline-flex items-center rounded-full border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-300">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-amber-400/20 bg-amber-400/10 px-2.5 py-1 text-[11px] font-semibold text-amber-300">
                                        Dipinjam
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('units.show', $unit->id) }}" class="inline-flex h-9 items-center justify-center rounded-xl border border-slate-700 bg-slate-900/80 px-3 text-xs font-semibold text-slate-300 hover:border-cyan-300/40 hover:text-cyan-200 transition">
                                        Rincian
                                    </a>
                                    <a href="{{ route('units.edit', $unit->id) }}" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl border border-slate-700 bg-slate-900/80 px-3 text-xs font-semibold text-slate-300 hover:border-cyan-300/40 hover:text-cyan-200 transition">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                                        Ubah
                                    </a>
                                    <form action="{{ route('units.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Hapus unit {{ $unit->name }}? Unit dengan pinjaman aktif tidak dapat dihapus.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl border border-rose-400/25 bg-rose-400/10 px-3 text-xs font-semibold text-rose-300 hover:bg-rose-400/20 transition">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 15H6L5 6M10 11v6M14 11v6"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-800">
            {{ $units->links() }}
        </div>
    @endif
</div>
@endsection

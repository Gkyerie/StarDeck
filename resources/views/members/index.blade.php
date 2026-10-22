@extends('layouts.app')
@section('title', 'Data Astronot — StarDeck')
@section('page_label', 'Data Astronot')

@section('content')
<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-2xl font-bold text-white font-heading sm:text-3xl">Kelola astronot</h1>
        <p class="mt-1 text-sm text-slate-400">Kelola akun kru stasiun dan pastikan kewajiban pinjaman selesai sebelum akun dihapus.</p>
    </div>
    <div>
        <a href="{{ route('members.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-4 text-sm font-semibold text-slate-950 shadow-[0_8px_24px_rgba(34,211,238,.18)] transition-all hover:bg-cyan-200 active:scale-[.98]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M16 11h6"/></svg>
            <span>Tambah Astronot</span>
        </a>
    </div>
</div>

<div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
    @if($members->isEmpty())
        <div class="p-12 text-center text-slate-500 text-sm">
            Belum ada data anggota astronot terdaftar.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left">
                <thead class="bg-slate-950/50 text-[10px] uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Astronot</th>
                        <th class="px-6 py-4">Pangkat / Jabatan</th>
                        <th class="px-6 py-4">Modul Stasiun</th>
                        <th class="px-6 py-4">No. Telepon</th>
                        <th class="px-6 py-4">Pinjaman Aktif</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($members as $m)
                        <tr class="transition hover:bg-white/[.02]">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($m->memberProfile && $m->memberProfile->photo)
                                        <img src="{{ asset('storage/' . $m->memberProfile->photo) }}" alt="{{ $m->name }}" class="h-10 w-10 rounded-full object-cover border border-slate-700">
                                    @else
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-cyan-300/10 text-xs font-bold text-cyan-300 font-heading">
                                            {{ strtoupper(substr($m->name, 0, 2)) }}
                                        </span>
                                    @endif
                                    <div>
                                        <div class="text-sm font-semibold text-white">{{ $m->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $m->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $m->memberProfile->rank_or_position ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $m->memberProfile->station_module ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                {{ $m->memberProfile->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($m->active_loans_count > 0)
                                    <span class="inline-flex items-center rounded-full border border-amber-400/20 bg-amber-400/10 px-2.5 py-1 text-[11px] font-semibold text-amber-300">
                                        {{ $m->active_loans_count }} unit aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-800/50 px-2.5 py-1 text-[11px] font-semibold text-slate-400">
                                        0 unit
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('loans.history', $m->id) }}" class="inline-flex h-9 items-center justify-center rounded-xl border border-slate-700 bg-slate-900/80 px-3 text-xs font-semibold text-slate-300 hover:border-cyan-300/40 hover:text-cyan-200 transition" title="Riwayat Pinjaman">
                                        Riwayat
                                    </a>
                                    <a href="{{ route('members.show', $m->id) }}" class="inline-flex h-9 items-center justify-center rounded-xl border border-slate-700 bg-slate-900/80 px-3 text-xs font-semibold text-slate-300 hover:border-cyan-300/40 hover:text-cyan-200 transition">
                                        Rincian
                                    </a>
                                    <a href="{{ route('members.edit', $m->id) }}" class="inline-flex h-9 items-center justify-center gap-1 rounded-xl border border-slate-700 bg-slate-900/80 px-3 text-xs font-semibold text-slate-300 hover:border-cyan-300/40 hover:text-cyan-200 transition">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                                        Ubah
                                    </a>
                                    <form action="{{ route('members.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus astronot {{ $m->name }}? Anggota dengan pinjaman aktif tidak dapat dihapus.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 items-center justify-center gap-1 rounded-xl border border-rose-400/25 bg-rose-400/10 px-3 text-xs font-semibold text-rose-300 hover:bg-rose-400/20 transition">
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
            {{ $members->links() }}
        </div>
    @endif
</div>
@endsection

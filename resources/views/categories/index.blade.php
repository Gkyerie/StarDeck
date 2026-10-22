@extends('layouts.app')
@section('title', 'Kategori — StarDeck')
@section('page_label', 'Kategori Unit')

@section('content')
<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-2xl font-bold text-white font-heading sm:text-3xl">Kelola kategori</h1>
        <p class="mt-1 text-sm text-slate-400">Jaga nama kategori tetap unik dan pantau keterkaitannya dengan inventaris.</p>
    </div>
    <div>
        <a href="{{ route('categories.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-4 text-sm font-semibold text-slate-950 shadow-[0_8px_24px_rgba(34,211,238,.18)] transition-all hover:bg-cyan-200 active:scale-[.98]">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tambah Kategori</span>
        </a>
    </div>
</div>

<div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
    @if($categories->isEmpty())
        <div class="p-12 text-center text-slate-500 text-sm">
            Belum ada kategori yang ditambahkan.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left">
                <thead class="bg-slate-950/50 text-[10px] uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Unit Terhubung</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($categories as $cat)
                        <tr class="transition hover:bg-white/[.02]">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3 font-semibold text-white">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-400/10 text-violet-300">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13 13 20l-9-9V4h7l9 9Z M8.5 8.5h.01"/></svg>
                                    </span>
                                    <span>{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $cat->description ?: '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full border border-violet-400/20 bg-violet-400/10 px-2.5 py-1 text-[11px] font-semibold text-violet-300">
                                    {{ $cat->units_count }} unit
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('categories.edit', $cat->id) }}" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl border border-slate-700 bg-slate-900/80 px-3 text-xs font-semibold text-slate-300 hover:border-cyan-300/40 hover:text-cyan-200 transition">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                                        Ubah
                                    </a>
                                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }}? Kategori yang masih terikat unit tidak dapat dihapus.');" class="inline">
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
    @endif
</div>
@endsection

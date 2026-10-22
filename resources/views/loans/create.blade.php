@extends('layouts.app')
@section('title', 'Catat Peminjaman — StarDeck')
@section('page_label', 'Pinjaman / Baru')

@section('content')
<div class="mb-6">
    <a href="{{ route('loans.active') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-cyan-300 transition">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <span>Kembali ke Pinjaman Aktif</span>
    </a>
</div>

<div class="max-w-xl mx-auto rounded-3xl border border-slate-800/80 bg-slate-900/60 p-6 sm:p-8 backdrop-blur-xl shadow-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white font-heading">Tambah pinjaman baru</h1>
        <p class="mt-1 text-xs text-slate-400">Pilih kru astronot dan unit tersedia. Durasi standar peminjaman adalah 5 hari kalender.</p>
    </div>

    <form action="{{ route('loans.store') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Member select -->
        <div class="space-y-2">
            <label for="member_id" class="block text-sm font-medium text-slate-300">
                Pilih Astronot Kru <span class="text-rose-400">*</span>
            </label>
            <select
                id="member_id"
                name="member_id"
                required
                class="w-full rounded-xl border {{ $errors->has('member_id') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-300/60"
            >
                <option value="" class="bg-slate-900">-- Pilih Anggota Kru --</option>
                @foreach($members as $m)
                    <option value="{{ $m->id }}" {{ old('member_id') == $m->id ? 'selected' : '' }} class="bg-slate-900">
                        {{ $m->name }} ({{ $m->email }}) — {{ $m->memberProfile->rank_or_position ?? 'Astronot' }}
                    </option>
                @endforeach
            </select>
            <div class="text-[11px] text-slate-500">Maksimal 2 unit pinjaman aktif per kru.</div>
            @error('member_id')
                <div class="text-xs text-rose-400">{{ $message }}</div>
            @enderror
        </div>

        <!-- Unit select -->
        <div class="space-y-2">
            <label for="unit_id" class="block text-sm font-medium text-slate-300">
                Pilih Unit Tersedia <span class="text-rose-400">*</span>
            </label>
            <select
                id="unit_id"
                name="unit_id"
                required
                class="w-full rounded-xl border {{ $errors->has('unit_id') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-300/60"
            >
                <option value="" class="bg-slate-900">-- Pilih Unit Hiburan --</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }} class="bg-slate-900">
                        [{{ $u->unit_code }}] {{ $u->name }} ({{ $u->type === 'board_game' ? 'Permainan Papan' : 'Konsol' }})
                    </option>
                @endforeach
            </select>
            @error('unit_id')
                <div class="text-xs text-rose-400">{{ $message }}</div>
            @enderror
        </div>

        <!-- Date Preview Grid -->
        <div class="grid grid-cols-2 gap-3 pt-2">
            <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                <div class="text-[10px] uppercase tracking-wider text-slate-500">Tanggal Pinjam</div>
                <div class="mt-1 text-sm font-semibold text-white font-mono">
                    {{ now()->format('d M Y') }}
                </div>
            </div>
            <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                <div class="text-[10px] uppercase tracking-wider text-slate-500">Jatuh Tempo (+5 Hari)</div>
                <div class="mt-1 text-sm font-semibold text-cyan-300 font-mono">
                    {{ now()->addDays(5)->format('d M Y') }}
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-slate-800">
            <a href="{{ route('loans.active') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 px-4 text-xs font-semibold text-slate-300 hover:text-white transition">
                Batal
            </a>
            <button
                type="submit"
                class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-5 text-xs font-semibold text-slate-950 transition hover:bg-cyan-200 active:scale-[.98]"
            >
                Simpan Pinjaman
            </button>
        </div>
    </form>
</div>
@endsection

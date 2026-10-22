@extends('layouts.app')
@section('title', 'Rincian Astronot — StarDeck')
@section('page_label', 'Data Astronot / Rincian')

@section('content')
<div class="mb-6">
    <a href="{{ route('members.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-cyan-300 transition">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <span>Kembali ke Data Astronot</span>
    </a>
</div>

<div class="max-w-2xl mx-auto rounded-3xl border border-slate-800/80 bg-slate-900/60 p-6 sm:p-8 backdrop-blur-xl shadow-2xl">
    <div class="flex items-center gap-4 mb-6">
        @if($member->memberProfile && $member->memberProfile->photo)
            <img src="{{ asset('storage/' . $member->memberProfile->photo) }}" alt="{{ $member->name }}" class="h-16 w-16 rounded-2xl object-cover border border-slate-700">
        @else
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-cyan-300/10 text-xl font-bold text-cyan-300 font-heading">
                {{ strtoupper(substr($member->name, 0, 2)) }}
            </div>
        @endif
        <div>
            <h1 class="text-2xl font-bold text-white font-heading">{{ $member->name }}</h1>
            <div class="mt-0.5 text-xs text-slate-400 font-mono">{{ $member->email }}</div>
            <div class="mt-2">
                <span class="inline-flex items-center rounded-full border border-violet-400/20 bg-violet-400/10 px-2.5 py-0.5 text-[11px] font-semibold text-violet-300">
                    {{ $member->memberProfile->rank_or_position ?? 'Astronot Kru' }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 text-sm">
        <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-3.5">
            <div class="text-[10px] uppercase tracking-wider text-slate-500">Modul Stasiun</div>
            <div class="mt-1 font-semibold text-white">
                {{ $member->memberProfile->station_module ?? '-' }}
            </div>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-3.5">
            <div class="text-[10px] uppercase tracking-wider text-slate-500">Nomor Telepon</div>
            <div class="mt-1 font-semibold text-white font-mono">
                {{ $member->memberProfile->phone ?? '-' }}
            </div>
        </div>

        <div class="col-span-2 rounded-xl border border-slate-800 bg-slate-950/60 p-3.5">
            <div class="text-[10px] uppercase tracking-wider text-slate-500">Alamat / Kuartir Kamar</div>
            <div class="mt-1 font-semibold text-slate-300">
                {{ $member->memberProfile->address ?: '-' }}
            </div>
        </div>
    </div>

    <div class="mt-7 pt-6 border-t border-slate-800/80 flex items-center justify-between gap-4">
        <div class="flex gap-2">
            <a href="{{ route('loans.history', $member->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-cyan-300 px-4 py-2.5 text-xs font-semibold text-slate-950 hover:bg-cyan-200 transition">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                <span>Lihat Riwayat Pinjaman</span>
            </a>
            <a href="{{ route('members.edit', $member->id) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-700 bg-slate-900 px-4 py-2.5 text-xs font-semibold text-slate-300 hover:text-white transition">
                <span>Ubah</span>
            </a>
        </div>
        <a href="{{ route('members.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-900 px-4 py-2 text-xs font-semibold text-slate-300 hover:text-white transition">
            Tutup
        </a>
    </div>
</div>
@endsection

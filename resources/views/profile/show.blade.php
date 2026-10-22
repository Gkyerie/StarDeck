@extends('layouts.app')
@section('title', 'Profil Astronot — StarDeck')
@section('page_label', 'Profil Saya')

@section('content')
<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-2xl font-bold text-white font-heading sm:text-3xl">Profil astronot</h1>
        <p class="mt-1 text-sm text-slate-400">Kelola informasi identitas kru dan penugasan stasiun Anda.</p>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-[280px_1fr]">
    <!-- Avatar & Identity Card -->
    <div class="h-fit rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 text-center backdrop-blur-sm">
        <div class="mx-auto flex h-28 w-28 items-center justify-center overflow-hidden rounded-2xl border border-slate-700 bg-gradient-to-br from-violet-500/20 to-cyan-500/20 text-3xl font-bold text-white font-heading">
            @if($profile && $profile->photo)
                <img src="{{ asset('storage/' . $profile->photo) }}" alt="Foto profil astronot" class="h-full w-full object-cover">
            @else
                {{ strtoupper(substr($user->name, 0, 2)) }}
            @endif
        </div>
        <div class="mt-4 font-bold text-white font-heading text-lg">{{ $user->name }}</div>
        <div class="mt-1 text-xs text-slate-500 font-mono">{{ $user->email }}</div>

        <div class="mt-4 pt-4 border-t border-slate-800 text-left space-y-2 text-xs">
            <div>
                <span class="text-[10px] uppercase text-slate-500 block">Pangkat</span>
                <span class="text-slate-300 font-semibold">{{ $profile->rank_or_position ?? 'Astronot Kru' }}</span>
            </div>
            <div>
                <span class="text-[10px] uppercase text-slate-500 block">Modul Stasiun</span>
                <span class="text-cyan-300 font-semibold">{{ $profile->station_module ?? '-' }}</span>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('profile.edit') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-cyan-300 px-4 py-2.5 text-xs font-semibold text-slate-950 transition hover:bg-cyan-200">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                <span>Ubah Profil</span>
            </a>
        </div>
    </div>

    <!-- Personal Info Card -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 sm:p-8 backdrop-blur-sm">
        <div class="mb-6 border-b border-slate-800 pb-5">
            <h2 class="font-bold text-white text-base font-heading">Informasi penugasan</h2>
            <p class="mt-1 text-xs text-slate-500">Data identitas resmi kru stasiun luar angkasa Andromeda.</p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                <div class="text-[10px] uppercase tracking-wider text-slate-500">Nomor Telepon</div>
                <div class="mt-1.5 text-sm font-semibold text-white font-mono">
                    {{ $profile->phone ?? 'Belum diisi' }}
                </div>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                <div class="text-[10px] uppercase tracking-wider text-slate-500">Modul Stasiun</div>
                <div class="mt-1.5 text-sm font-semibold text-white">
                    {{ $profile->station_module ?? 'Belum diisi' }}
                </div>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                <div class="text-[10px] uppercase tracking-wider text-slate-500">Pangkat / Jabatan</div>
                <div class="mt-1.5 text-sm font-semibold text-white">
                    {{ $profile->rank_or_position ?? 'Belum diisi' }}
                </div>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                <div class="text-[10px] uppercase tracking-wider text-slate-500">Status Akses Kru</div>
                <div class="mt-1.5 text-sm font-semibold text-emerald-400 flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Aktif Terverifikasi
                </div>
            </div>

            <div class="sm:col-span-2 rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                <div class="text-[10px] uppercase tracking-wider text-slate-500">Alamat / Kuartir Kamar</div>
                <div class="mt-1.5 text-sm text-slate-300">
                    {{ $profile->address ?: 'Belum ada catatan lokasi kamar atau kuartir modul.' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

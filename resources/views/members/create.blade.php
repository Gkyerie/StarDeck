@extends('layouts.app')
@section('title', 'Tambah Astronot — StarDeck')
@section('page_label', 'Data Astronot / Tambah')

@section('content')
<div class="mb-6">
    <a href="{{ route('members.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-cyan-300 transition">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <span>Kembali ke Data Astronot</span>
    </a>
</div>

<div class="max-w-2xl mx-auto rounded-3xl border border-slate-800/80 bg-slate-900/60 p-6 sm:p-8 backdrop-blur-xl shadow-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white font-heading">Tambah akun astronot kru</h1>
        <p class="mt-1 text-xs text-slate-400">Daftarkan akun kru baru untuk akses fasilitas hiburan stasiun.</p>
    </div>

    <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Akun Section -->
        <div>
            <h2 class="text-xs font-semibold uppercase tracking-wider text-cyan-400 mb-3">Informasi Akun</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-slate-300">
                        Nama Lengkap <span class="text-rose-400">*</span>
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Nara Rasyid"
                        required
                        class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300/60"
                    >
                    @error('name')
                        <div class="text-xs text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-slate-300">
                        Alamat Email <span class="text-rose-400">*</span>
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nara@stardeck.local"
                        required
                        class="w-full rounded-xl border {{ $errors->has('email') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300/60"
                    >
                    @error('email')
                        <div class="text-xs text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div class="space-y-2 sm:col-span-2">
                    <label for="password" class="block text-sm font-medium text-slate-300">
                        Kata Sandi <span class="text-rose-400">*</span> <span class="text-xs text-slate-500">(Minimal 8 karakter)</span>
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full rounded-xl border {{ $errors->has('password') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-300/60"
                    >
                    @error('password')
                        <div class="text-xs text-rose-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Profil Modul Kru -->
        <div class="pt-4 border-t border-slate-800">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-cyan-400 mb-3">Profil Modul & Penugasan</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <label for="rank_or_position" class="block text-sm font-medium text-slate-300">
                        Pangkat / Jabatan <span class="text-rose-400">*</span>
                    </label>
                    <input
                        id="rank_or_position"
                        type="text"
                        name="rank_or_position"
                        value="{{ old('rank_or_position') }}"
                        placeholder="Contoh: Astronot Senior, Teknisi Modul"
                        required
                        class="w-full rounded-xl border {{ $errors->has('rank_or_position') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-300/60"
                    >
                    @error('rank_or_position')
                        <div class="text-xs text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="station_module" class="block text-sm font-medium text-slate-300">
                        Modul Stasiun <span class="text-rose-400">*</span>
                    </label>
                    <input
                        id="station_module"
                        type="text"
                        name="station_module"
                        value="{{ old('station_module') }}"
                        placeholder="Contoh: Modul Navigasi, Modul Riset"
                        required
                        class="w-full rounded-xl border {{ $errors->has('station_module') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-300/60"
                    >
                    @error('station_module')
                        <div class="text-xs text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="phone" class="block text-sm font-medium text-slate-300">
                        Nomor Telepon <span class="text-rose-400">*</span> <span class="text-xs text-slate-500">(9-15 digit)</span>
                    </label>
                    <input
                        id="phone"
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="081234567890"
                        required
                        class="w-full rounded-xl border {{ $errors->has('phone') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white font-mono outline-none transition focus:border-cyan-300/60"
                    >
                    @error('phone')
                        <div class="text-xs text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="photo" class="block text-sm font-medium text-slate-300">
                        Foto Profil <span class="text-xs text-slate-500">(JPG/PNG, maks 2MB)</span>
                    </label>
                    <input
                        id="photo"
                        type="file"
                        name="photo"
                        accept="image/jpeg,image/png"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-950/60 px-3 py-2 text-xs text-slate-300 outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-slate-800 file:text-xs file:text-cyan-300 hover:file:bg-slate-700"
                    >
                    @error('photo')
                        <div class="text-xs text-rose-400">{{ $message }}</div>
                    @enderror
                </div>

                <div class="space-y-2 sm:col-span-2">
                    <label for="address" class="block text-sm font-medium text-slate-300">
                        Alamat / Kuartir Kamar <span class="text-xs text-slate-500">(Opsional)</span>
                    </label>
                    <input
                        id="address"
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        placeholder="Kuartir Kru Sektor B, bunk 04"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-300/60"
                    >
                    @error('address')
                        <div class="text-xs text-rose-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-slate-800">
            <a href="{{ route('members.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 px-4 text-xs font-semibold text-slate-300 hover:text-white transition">
                Batal
            </a>
            <button
                type="submit"
                class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-5 text-xs font-semibold text-slate-950 transition hover:bg-cyan-200 active:scale-[.98]"
            >
                Simpan Astronot
            </button>
        </div>
    </form>
</div>
@endsection

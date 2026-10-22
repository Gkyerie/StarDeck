@extends('layouts.app')
@section('title', 'Tambah Unit — StarDeck')
@section('page_label', 'Inventaris / Tambah Unit')

@section('content')
<div class="mb-6">
    <a href="{{ route('units.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-cyan-300 transition">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <span>Kembali ke Inventaris</span>
    </a>
</div>

<div class="max-w-2xl mx-auto rounded-3xl border border-slate-800/80 bg-slate-900/60 p-6 sm:p-8 backdrop-blur-xl shadow-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white font-heading">Tambah unit baru</h1>
        <p class="mt-1 text-xs text-slate-400">Daftarkan board game atau konsol baru ke dalam inventaris stasiun.</p>
    </div>

    <form action="{{ route('units.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid gap-4 sm:grid-cols-2">
            <!-- Unit Code -->
            <div class="space-y-2">
                <label for="unit_code" class="block text-sm font-medium text-slate-300">
                    Kode Unit <span class="text-rose-400">*</span>
                </label>
                <input
                    id="unit_code"
                    type="text"
                    name="unit_code"
                    value="{{ old('unit_code') }}"
                    placeholder="Contoh: SD-BG-005 atau SD-CN-004"
                    required
                    class="w-full rounded-xl border {{ $errors->has('unit_code') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white font-mono outline-none transition placeholder:text-slate-600 focus:border-cyan-300/60 focus:ring-4 focus:ring-cyan-300/10"
                >
                <div class="text-[11px] text-slate-500">Format: <code>SD-BG-XXX</code> atau <code>SD-CN-XXX</code></div>
                @error('unit_code')
                    <div class="text-xs text-rose-400">{{ $message }}</div>
                @enderror
            </div>

            <!-- Type -->
            <div class="space-y-2">
                <label for="type" class="block text-sm font-medium text-slate-300">
                    Tipe Unit <span class="text-rose-400">*</span>
                </label>
                <select
                    id="type"
                    name="type"
                    required
                    class="w-full rounded-xl border {{ $errors->has('type') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-300/60"
                >
                    <option value="" class="bg-slate-900">-- Pilih Tipe Unit --</option>
                    <option value="board_game" {{ old('type') === 'board_game' ? 'selected' : '' }} class="bg-slate-900">Permainan Papan (Board Game)</option>
                    <option value="console" {{ old('type') === 'console' ? 'selected' : '' }} class="bg-slate-900">Konsol (Console)</option>
                </select>
                @error('type')
                    <div class="text-xs text-rose-400">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Name -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-slate-300">
                Nama Unit <span class="text-rose-400">*</span>
            </label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Contoh: Terraformasi Mars, PlayStation 5"
                required
                class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300/60 focus:ring-4 focus:ring-cyan-300/10"
            >
            @error('name')
                <div class="text-xs text-rose-400">{{ $message }}</div>
            @enderror
        </div>

        <!-- Categories checklist -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-300">
                Kategori <span class="text-rose-400">*</span> <span class="text-xs text-slate-500">(Pilih minimal satu)</span>
            </label>
            <div class="p-3.5 rounded-xl border {{ $errors->has('categories') ? 'border-rose-400/80' : 'border-slate-800' }} bg-slate-950/60 flex flex-wrap gap-2 max-h-40 overflow-y-auto">
                @foreach($categories as $category)
                    <label class="inline-flex items-center gap-2 rounded-lg border border-slate-700/80 bg-slate-900/80 px-3 py-1.5 text-xs text-slate-300 cursor-pointer hover:border-cyan-300/40">
                        <input
                            type="checkbox"
                            name="categories[]"
                            value="{{ $category->id }}"
                            {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}
                            class="rounded border-slate-700 bg-slate-950 text-cyan-400 focus:ring-0"
                        >
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('categories')
                <div class="text-xs text-rose-400">{{ $message }}</div>
            @enderror
        </div>

        <!-- Condition -->
        <div class="space-y-2">
            <label for="condition" class="block text-sm font-medium text-slate-300">
                Kondisi Fisik <span class="text-rose-400">*</span>
            </label>
            <input
                id="condition"
                type="text"
                name="condition"
                value="{{ old('condition', 'Sangat Baik') }}"
                placeholder="Contoh: Sangat Baik, Lengkap komponen"
                required
                class="w-full rounded-xl border {{ $errors->has('condition') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300/60"
            >
            @error('condition')
                <div class="text-xs text-rose-400">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="space-y-2">
            <label for="description" class="block text-sm font-medium text-slate-300">
                Deskripsi Unit <span class="text-xs text-slate-500">(Opsional)</span>
            </label>
            <textarea
                id="description"
                name="description"
                rows="3"
                placeholder="Deskripsi singkat mengenai tema permainan atau kelengkapan..."
                class="w-full rounded-xl border {{ $errors->has('description') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300/60"
            >{{ old('description') }}</textarea>
            @error('description')
                <div class="text-xs text-rose-400">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Actions -->
        <div class="pt-4 flex justify-end gap-3 border-t border-slate-800">
            <a href="{{ route('units.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 px-4 text-xs font-semibold text-slate-300 hover:text-white transition">
                Batal
            </a>
            <button
                type="submit"
                class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-5 text-xs font-semibold text-slate-950 transition hover:bg-cyan-200 active:scale-[.98]"
            >
                Simpan Unit
            </button>
        </div>
    </form>
</div>
@endsection

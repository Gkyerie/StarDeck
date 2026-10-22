@extends('layouts.app')
@section('title', 'Ubah Kategori — StarDeck')
@section('page_label', 'Kategori / Ubah')

@section('content')
<div class="mb-6">
    <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-cyan-300 transition">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <span>Kembali ke Kategori</span>
    </a>
</div>

<div class="max-w-xl mx-auto rounded-3xl border border-slate-800/80 bg-slate-900/60 p-6 sm:p-8 backdrop-blur-xl shadow-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white font-heading">Ubah kategori: {{ $category->name }}</h1>
        <p class="mt-1 text-xs text-slate-400">Pastikan nama kategori tetap unik di seluruh stasiun.</p>
    </div>

    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-slate-300">
                Nama Kategori <span class="text-rose-400">*</span>
            </label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name', $category->name) }}"
                required
                class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-400/80' : 'border-slate-700/80' }} bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-300/60 focus:ring-4 focus:ring-cyan-300/10"
            >
            @error('name')
                <div class="text-xs text-rose-400">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="description" class="block text-sm font-medium text-slate-300">
                Keterangan / Deskripsi <span class="text-xs text-slate-500">(Opsional)</span>
            </label>
            <textarea
                id="description"
                name="description"
                rows="3"
                class="w-full rounded-xl border border-slate-700/80 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-300/60"
            >{{ old('description', $category->description) }}</textarea>
            @error('description')
                <div class="text-xs text-rose-400">{{ $message }}</div>
            @enderror
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-slate-800">
            <a href="{{ route('categories.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 px-4 text-xs font-semibold text-slate-300 hover:text-white transition">
                Batal
            </a>
            <button
                type="submit"
                class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-5 text-xs font-semibold text-slate-950 transition hover:bg-cyan-200 active:scale-[.98]"
            >
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Edit Unit — StarDeck')

@section('content')
<div class="mb-4">
    <a href="{{ route('units.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Unit
    </a>
    <h1 class="h4 mb-0"><i class="bi bi-pencil me-2"></i>Edit Unit: {{ $unit->name }}</h1>
</div>

<div class="card shadow-sm border-0" style="max-width: 750px;">
    <div class="card-body p-4">
        <form action="{{ route('units.update', $unit->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="unit_code" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('unit_code') is-invalid @enderror" id="unit_code" name="unit_code" value="{{ old('unit_code', $unit->unit_code) }}" required>
                    <div class="form-text">Pola wajib: <code>^SD-(BG|CN)-[0-9]{3}$</code></div>
                    @error('unit_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="type" class="form-label">Tipe Unit <span class="text-danger">*</span></label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="board_game" {{ old('type', $unit->type) === 'board_game' ? 'selected' : '' }}>Board Game (BG)</option>
                        <option value="console" {{ old('type', $unit->type) === 'console' ? 'selected' : '' }}>Console (CN)</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nama Unit <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $unit->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Kategori <span class="text-danger">*</span> <small class="text-muted">(Pilih minimal satu)</small></label>
                <div class="p-3 border rounded bg-light @error('categories') border-danger @enderror" style="max-height: 180px; overflow-y: auto;">
                    @php
                        $selectedCategories = old('categories', $unit->categories->pluck('id')->toArray());
                    @endphp
                    @foreach($categories as $category)
                        <div class="form-check form-check-inline me-3 mb-2">
                            <input class="form-check-input" type="checkbox" name="categories[]" id="cat_{{ $category->id }}" value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cat_{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
                @error('categories')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="condition" class="form-label">Kondisi Fisik <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('condition') is-invalid @enderror" id="condition" name="condition" value="{{ old('condition', $unit->condition) }}" required>
                @error('condition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="form-label">Deskripsi <small class="text-muted">(Opsional)</small></label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $unit->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Perbarui Unit</button>
            </div>
        </form>
    </div>
</div>
@endsection

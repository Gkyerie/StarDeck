@extends('layouts.app')
@section('title', 'Edit Kategori — StarDeck')
@section('content')
<h1 class="h4 mb-4"><i class="bi bi-pencil me-2"></i>Edit Kategori</h1>
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required maxlength="50">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-teal"><i class="bi bi-check-lg me-1"></i> Simpan</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

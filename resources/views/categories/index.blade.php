@extends('layouts.app')
@section('title', 'Kategori — StarDeck')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="bi bi-tags me-2"></i>Kategori</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-teal btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($categories->isEmpty())
            <div class="empty-state">
                <i class="bi bi-tags d-block"></i>
                <p>Belum ada kategori.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Nama</th><th>Deskripsi</th><th>Unit</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <td class="fw-medium">{{ $cat->name }}</td>
                                <td class="text-muted small">{{ Str::limit($cat->description, 60) ?: '-' }}</td>
                                <td>{{ $cat->units_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

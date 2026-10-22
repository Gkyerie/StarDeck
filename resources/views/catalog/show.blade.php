@extends('layouts.app')
@section('title', $unit->name . ' — StarDeck')

@section('content')
<div class="mb-4">
    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog
    </a>
    <h1 class="h4 mb-0"><i class="bi bi-box-seam me-2"></i>Detail Unit</h1>
</div>

<div class="card shadow-sm border-0" style="max-width: 800px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <span class="badge bg-secondary font-monospace fs-6 mb-2">{{ $unit->unit_code }}</span>
                <h2 class="h4 mb-1">{{ $unit->name }}</h2>
                <span class="badge bg-light text-dark border">
                    {{ $unit->type === 'board_game' ? 'Board Game' : 'Console' }}
                </span>
            </div>
            <div>
                @if($unit->status === 'available')
                    <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Tersedia</span>
                @else
                    <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock-history me-1"></i>Sedang Dipinjam</span>
                @endif
            </div>
        </div>

        <hr>

        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <span class="text-muted d-block small">Kategori</span>
                <div class="mt-1">
                    @forelse($unit->categories as $category)
                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle me-1">{{ $category->name }}</span>
                    @empty
                        <span class="text-muted">-</span>
                    @endforelse
                </div>
            </div>
            <div class="col-sm-6">
                <span class="text-muted d-block small">Kondisi Fisik</span>
                <span class="fw-semibold">{{ $unit->condition }}</span>
            </div>
            <div class="col-12">
                <span class="text-muted d-block small mb-1">Deskripsi</span>
                <p class="text-secondary bg-light p-3 rounded mb-0">
                    {{ $unit->description ?: 'Tidak ada deskripsi rinci untuk unit ini.' }}
                </p>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
            <div class="d-flex gap-2">
                <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i>Edit Unit (Admin)
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

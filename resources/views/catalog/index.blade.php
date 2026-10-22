@extends('layouts.app')
@section('title', 'Katalog Unit — StarDeck')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1"><i class="bi bi-grid-3x3-gap me-2"></i>Katalog Unit</h1>
        <p class="text-muted small mb-0">Jelajahi koleksi board game dan console stasiun StarDeck.</p>
    </div>
</div>

<!-- Search & Filter -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form method="GET" action="{{ route('catalog.index') }}" class="row g-2">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama unit..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-filter me-1"></i>Filter</button>
                @if(request('search') || request('category'))
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary" title="Reset filter"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Units Grid -->
@if($units->isEmpty())
    <div class="text-center py-5 bg-white rounded shadow-sm">
        <i class="bi bi-inbox text-muted display-4 d-block mb-3"></i>
        <h5 class="text-muted">Tidak ada unit yang ditemukan</h5>
        <p class="text-muted small">Coba ubah kata kunci pencarian atau kategori filter.</p>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
        @foreach($units as $unit)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-secondary font-monospace">{{ $unit->unit_code }}</span>
                            @if($unit->status === 'available')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Tersedia</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i>Dipinjam</span>
                            @endif
                        </div>
                        <h5 class="card-title mb-1 text-truncate" title="{{ $unit->name }}">{{ $unit->name }}</h5>
                        <div class="text-muted small mb-2">
                            <span class="badge bg-light text-dark border me-1">
                                {{ $unit->type === 'board_game' ? 'Board Game' : 'Console' }}
                            </span>
                            <span>Kondisi: <strong>{{ $unit->condition }}</strong></span>
                        </div>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($unit->description ?? 'Tidak ada deskripsi.', 90) }}
                        </p>
                        <div class="mb-3">
                            @foreach($unit->categories as $category)
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle me-1">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        <a href="{{ route('catalog.show', $unit->id) }}" class="btn btn-outline-primary btn-sm w-100 mt-auto">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $units->links() }}
    </div>
@endif
@endsection

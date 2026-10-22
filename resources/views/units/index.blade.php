@extends('layouts.app')
@section('title', 'Manajemen Unit — StarDeck')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1"><i class="bi bi-boxes me-2"></i>Manajemen Unit</h1>
        <p class="text-muted small mb-0">Kelola inventaris board game dan console stasiun.</p>
    </div>
    <a href="{{ route('units.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Unit
    </a>
</div>

<!-- Filter & Search -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form method="GET" action="{{ route('units.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama unit..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-filter"></i> Filter</button>
                @if(request('search') || request('category') || request('status'))
                    <a href="{{ route('units.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        @if($units->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-boxes text-muted display-4 d-block mb-3"></i>
                <h5 class="text-muted">Tidak ada unit ditemukan</h5>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Unit</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Kategori</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($units as $unit)
                            <tr>
                                <td><span class="badge bg-secondary font-monospace">{{ $unit->unit_code }}</span></td>
                                <td class="fw-semibold">{{ $unit->name }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $unit->type === 'board_game' ? 'Board Game' : 'Console' }}
                                    </span>
                                </td>
                                <td>
                                    @foreach($unit->categories as $c)
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle me-1">{{ $c->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $unit->condition }}</td>
                                <td>
                                    @if($unit->status === 'available')
                                        <span class="badge bg-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('units.show', $unit->id) }}" class="btn btn-outline-secondary" title="Lihat"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus unit ini? Unit dengan pinjaman aktif tidak dapat dihapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $units->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

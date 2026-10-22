@extends('layouts.app')
@section('title', 'Manajemen Anggota — StarDeck')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1"><i class="bi bi-people me-2"></i>Manajemen Anggota</h1>
        <p class="text-muted small mb-0">Kelola akun dan profil seluruh anggota kru stasiun.</p>
    </div>
    <a href="{{ route('members.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>Tambah Anggota
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        @if($members->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-people text-muted display-4 d-block mb-3"></i>
                <h5 class="text-muted">Belum ada anggota terdaftar</h5>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Anggota</th>
                            <th>Pangkat / Posisi</th>
                            <th>Modul Stasiun</th>
                            <th>No. Telepon</th>
                            <th>Pinjaman Aktif</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $m)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($m->memberProfile && $m->memberProfile->photo)
                                            <img src="{{ asset('storage/' . $m->memberProfile->photo) }}" alt="{{ $m->name }}" class="rounded-circle me-2" style="width: 38px; height: 38px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 38px; height: 38px;">
                                                {{ strtoupper(substr($m->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $m->name }}</div>
                                            <div class="text-muted small">{{ $m->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $m->memberProfile->rank_or_position ?? '-' }}</td>
                                <td>{{ $m->memberProfile->station_module ?? '-' }}</td>
                                <td>{{ $m->memberProfile->phone ?? '-' }}</td>
                                <td>
                                    @if($m->active_loans_count > 0)
                                        <span class="badge bg-warning text-dark">{{ $m->active_loans_count }} unit</span>
                                    @else
                                        <span class="badge bg-light text-muted border">0 unit</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('loans.history', $m->id) }}" class="btn btn-outline-info" title="Riwayat Peminjaman"><i class="bi bi-clock-history"></i></a>
                                        <a href="{{ route('members.show', $m->id) }}" class="btn btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('members.edit', $m->id) }}" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('members.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus anggota ini? Anggota dengan pinjaman aktif tidak dapat dihapus.');">
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
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

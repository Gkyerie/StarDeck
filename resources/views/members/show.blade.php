@extends('layouts.app')
@section('title', 'Detail Anggota — StarDeck')

@section('content')
<div class="mb-4">
    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Anggota
    </a>
    <h1 class="h4 mb-0"><i class="bi bi-person me-2"></i>Detail Anggota: {{ $member->name }}</h1>
</div>

<div class="card shadow-sm border-0" style="max-width: 800px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-4">
            @if($member->memberProfile && $member->memberProfile->photo)
                <img src="{{ asset('storage/' . $member->memberProfile->photo) }}" alt="{{ $member->name }}" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
            @else
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3 fw-bold fs-2" style="width: 80px; height: 80px;">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h2 class="h4 mb-1">{{ $member->name }}</h2>
                <div class="text-muted">{{ $member->email }}</div>
                <span class="badge bg-primary mt-1">Astronot (Member)</span>
            </div>
        </div>

        <hr>

        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <span class="text-muted d-block small">Pangkat / Posisi</span>
                <span class="fw-semibold">{{ $member->memberProfile->rank_or_position ?? '-' }}</span>
            </div>
            <div class="col-sm-6">
                <span class="text-muted d-block small">Modul Stasiun</span>
                <span class="fw-semibold">{{ $member->memberProfile->station_module ?? '-' }}</span>
            </div>
            <div class="col-sm-6">
                <span class="text-muted d-block small">Nomor Telepon</span>
                <span class="fw-semibold">{{ $member->memberProfile->phone ?? '-' }}</span>
            </div>
            <div class="col-sm-6">
                <span class="text-muted d-block small">Alamat / Lokasi Kamar</span>
                <span>{{ $member->memberProfile->address ?: '-' }}</span>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('members.edit', $member->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i>Edit Data
            </a>
            <a href="{{ route('loans.history', $member->id) }}" class="btn btn-info text-white">
                <i class="bi bi-clock-history me-1"></i>Riwayat Pinjaman
            </a>
            <form action="{{ route('members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus anggota ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash me-1"></i>Hapus Anggota
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
